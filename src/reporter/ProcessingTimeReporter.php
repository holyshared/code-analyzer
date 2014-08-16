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

//use cloak\Result;
use cloak\event\StartEventInterface;
use cloak\event\StopEventInterface;
//use cloak\result\File;
//use cloak\value\Coverage;
//use cloak\CoverageResultInterface;
use cloak\writer\ConsoleWriter;
//use Zend\Console\ColorInterface as Color;


/**
 * Class ProcessingTimeReporter
 * @package cloak\reporter
 */
class ProcessingTimeReporter implements ReporterInterface
{

    use Reportable;

    /**
     * @var \cloak\writer\ConsoleWriter
     */
    private $console;

    /**
     * @var float
     */
    private $startAt;


    public function __construct()
    {
        $this->console = new ConsoleWriter();
    }

    /**
     * @param \cloak\event\StartEventInterface $event
     */
    public function onStart(StartEventInterface $event)
    {
        $this->timeMeasureStart($event);
    }

    /**
     * @param \cloak\event\StopEventInterface $event
     */
    public function onStop(StopEventInterface $event)
    {
        $this->timeMeasureFinish();
    }

    private function timeMeasureStart(StartEventInterface $event)
    {
        $startAt = $event->getSendAt();
        $formatStartTime = $startAt->format('j F Y \a\t H:i');

        $this->console->writeEOL();
        $this->console->writeLine("Start at: " . $formatStartTime);

        $this->startAt = microtime(true);
    }

    private function timeMeasureFinish()
    {
        $endAt = microtime(true);
        $runningTime = round($endAt - $this->startAt, 5);

        $this->console->writeEOL();
        $this->console->writeLine("Finished in $runningTime seconds");
    }

}