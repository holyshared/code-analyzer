<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak;

use cloak\value\Coverage;
use cloak\result\File;
use cloak\result\LineSet;
use cloak\driver\Result as AnalyzeResult;
use PhpCollection\Sequence;
use PhpCollection\AbstractSequence;
use \UnexpectedValueException;


/**
 * Class Result
 * @package cloak
 */
class Result implements CoverageResultInterface
{

    /**
     * @var AbstractSequence
     */
    private $files;


    /**
     * @param AbstractSequence $files
     */
    public function __construct(AbstractSequence $files = null)
    {
        if (is_null($files)) {
            $this->files = new Sequence();
        } else {
            $this->files = $files;
        }
    }

    /**
     * @param driver\Result $result
     * @return Result
     */
    public static function fromAnalyzeResult(AnalyzeResult $result)
    {
        $files = static::parseResult($result);
        return new self($files);
    }

    /**
     * @param driver\Result $result
     * @return Sequence
     */
    public static function parseResult(AnalyzeResult $result)
    {
        $files = new Sequence();
        $fileResults = $result->getFiles();

        foreach ($fileResults as $fileResult) {
            $path = $fileResult->getPath();
            $lineResults = LineSet::from( $fileResult->getLineResults() );
            $file = new File($path, $lineResults);
            $files->add($file);
        }

        return $files;
    }

    public function includeFile(\Closure $filter)
    {
        $files = $this->files->filter($filter);
        return new self($files);
    }

    public function includeFiles(array $filters)
    {
        $files = $this->files;

        foreach ($filters as $filter) {
            $files = $files->filter($filter);
        }

        return new self($files);
    }

    public function excludeFile(\Closure $filter)
    {
        $files = $this->files->filterNot($filter);
        return new self($files);
    }

    public function excludeFiles(array $filters)
    {
        $files = $this->files;

        foreach ($filters as $filter) {
            $files = $files->filterNot($filter);
        }

        return new self($files);
    }

    public function setFiles(AbstractSequence $files)
    {
        $this->files = $files;
        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function addFile(File $file)
    {
        $this->files->add($file);
        return $this;
    }

    public function removeFile(File $file)
    {
        $indexAt = $this->files->indexOf($file);

        if ($indexAt === -1) {
            throw new UnexpectedValueException("File that does not exist {$file->getPath()}");
        }
        $this->files->remove($indexAt);

        return $this;
    }

    public function getLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getLineCount();
        }

        return $totalLineCount;
    }

    public function getDeadLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getDeadLineCount();
        }

        return $totalLineCount;
    }

    public function getExecutedLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getExecutedLineCount();
        }

        return $totalLineCount;
    }

    public function getUnusedLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getUnusedLineCount();
        }

        return $totalLineCount;
    }

    public function getExecutableLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getExecutableLineCount();
        }

        return $totalLineCount;
    }

    public function getCodeCoverage()
    {
        $executedLineCount = $this->getExecutedLineCount();
        $executableLineCount = $this->getExecutableLineCount();
        $realCoverage = ($executedLineCount / $executableLineCount) * 100;

        $coverage = (float) round($realCoverage, 2);

        return new Coverage($coverage);
    }

    public function isCoverageLessThan(Coverage $coverage)
    {
        return $this->getCodeCoverage()->lessThan($coverage);
    }

    public function isCoverageGreaterEqual(Coverage $coverage)
    {
        return $this->getCodeCoverage()->greaterEqual($coverage);
    }

}
