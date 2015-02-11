<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\reporter;

use cloak\Result;
use cloak\result\FileResult;
use cloak\writer\FileWriter;
use cloak\event\InitEvent;
use cloak\event\StartEvent;
use cloak\event\StopEvent;
use cloak\result\collection\CoverageResultCollection;
use cloak\value\CoverageBounds;
use Eloquent\Pathogen\Factory\PathFactory;
use Eloquent\Pathogen\RelativePath;



/**
 * Class MarkdownReporter
 * @package cloak\reporter
 */
class MarkdownReporter
    implements ReporterInterface, InitEventListener, StartEventListener, StopEventListener
{

    use Reportable;

    const TABLE_SEPARATOR_CHAR = '|';

    const ALIGN_LEFT = ':--';
    const ALIGN_RIGTH = '--:';

    private $columnHeaders = [
        ' No. ',
        ' File ',
        ' Line ',
        ' Coverage '
    ];

    private $columnHeaderAlignments = [
        self::ALIGN_RIGTH,
        self::ALIGN_LEFT,
        self::ALIGN_RIGTH,
        self::ALIGN_RIGTH
    ];

    /**
     * @var string Report file name
     */
    private $fileName;

    /**
     * @var CoverageBounds
     */
    private $bounds;

    /**
     * @var \cloak\writer\FileWriter
     */
    private $reportWriter;

    /**
     * @var \DateTime
     */
    private $generatedAt;


    /**
     * @param string $fileName
     */
    public function __construct ($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param \cloak\event\InitEvent $event
     */
    public function onInit(InitEvent $event)
    {
        $this->bounds = $event->getCoverageBounds();

        $factory = PathFactory::instance();
        $reportDirectoryPath = $factory->create( $event->getReportDirectory() );

        $fileRelativePath = RelativePath::fromString($this->fileName);

        $reportFile = $reportDirectoryPath->join($fileRelativePath);
        $absoluteReportFilePath = $reportFile->normalize()->string();

        $this->reportWriter = new FileWriter($absoluteReportFilePath);
    }

    /**
     * @param StartEvent $event
     */
    public function onStart(StartEvent $event)
    {
        $this->generatedAt = $event->getSendAt();
    }

    /**
     * @param StopEvent $event
     */
    public function onStop(StopEvent $event)
    {
        $this->writeMarkdownReport($event->getResult());
    }

    /**
     * @param Result $result
     */
    private function writeMarkdownReport(Result $result)
    {
        $this->writeTitle();
        $this->writeDescription();
        $this->writeResult($result);
    }

    private function writeTitle()
    {
        $this->reportWriter->writeLine('# Code Coverage Report');
        $this->reportWriter->writeEOL();
    }

    private function writeDescription()
    {
        $generatedDateTime = $this->generatedAt->format('j F Y \a\t H:i');

        $this->reportWriter->writeLine('Generator: cloak  ');
        $this->reportWriter->writeLine("Generated at: $generatedDateTime  ");
        $this->reportWriter->writeEOL();
    }

    /**
     * @param Result $result
     */
    private function writeResult(Result $result)
    {
        $files = $result->getFiles();

        $criticalValue = $this->bounds->getCriticalCoverage();
        $criticalResults = $files->selectByCoverageLessThan($criticalValue);

        $satisfactoryValue = $this->bounds->getSatisfactoryCoverage();
        $satisfactoryResults = $files->selectByCoverageGreaterEqual($satisfactoryValue);

        $warningResults = $files->exclude($criticalResults)
            ->exclude($satisfactoryResults);

        $this->writeGroup('Critical', $criticalResults);
        $this->writeGroup('Warning', $warningResults);
        $this->writeGroup('Satisfactory', $satisfactoryResults);
    }

    /**
     * @param string $title
     * @param CoverageResultCollection $files
     */
    private function writeGroup($title, CoverageResultCollection $files)
    {
        $this->writeResultHeader($title);
        $this->writeFilesResultHeader();
        $this->writeFileResults($files);
    }

    /**
     * @param string $title
     */
    private function writeResultHeader($title)
    {
        $this->reportWriter->writeLine('## ' . $title);
        $this->reportWriter->writeEOL();
    }

    private function writeFilesResultHeader()
    {
        $record = $this->toTableRow($this->columnHeaders);
        $this->reportWriter->writeLine($record);

        $record = $this->toTableRow($this->columnHeaderAlignments);
        $this->reportWriter->writeLine($record);
    }

    /**
     * @param CoverageResultCollection $files
     */
    private function writeFileResults(CoverageResultCollection $files)
    {
        $orderNumber = 1;

        foreach ($files as $key => $file) {
            $this->writeFileResult($orderNumber, $file);
            $orderNumber++;
        }

        $this->reportWriter->writeEOL();
    }

    /**
     * @param int $orderNumber
     * @param FileResult $file
     */
    private function writeFileResult($orderNumber, FileResult $file)
    {

        $lineResult = sprintf("%2d/%2d",
            $file->getExecutedLineCount(),
            $file->getExecutableLineCount()
        );

        $coverageResult = sprintf('%6.2f%%', $file->getCodeCoverage()->value());

        $parts = [
            $orderNumber,
            $file->getRelativePath(getcwd()),
            $lineResult,
            $coverageResult
        ];

        $record = $this->toTableRow($parts);
        $this->reportWriter->writeLine($record);
    }

    /**
     * @param array $values
     * @return string
     */
    private function toTableRow(array $values)
    {
        $record = implode(static::TABLE_SEPARATOR_CHAR, $values);
        $record = static::TABLE_SEPARATOR_CHAR . $record . static::TABLE_SEPARATOR_CHAR;

        return $record;
    }

}
