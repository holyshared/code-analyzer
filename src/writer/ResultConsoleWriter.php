<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\writer;

use cloak\value\CoverageBounds;
use cloak\result\CoverageResultNode;
use Zend\Console\Console;
use Zend\Console\ColorInterface as Color;


/**
 * Class ResultConsoleWriter
 * @package cloak\writer
 */
class ResultConsoleWriter extends AbstractConsoleWriter implements StdoutWriter, ResultWriter
{

    /**
     * @var CoverageBounds
     */
    private $bounds;


    /**
     * @param CoverageBounds $coverageBounds
     */
    public function __construct(CoverageBounds $coverageBounds)
    {
        $this->console = Console::getInstance();
        $this->bounds = $coverageBounds;
    }

    /**
     * @{inheritDoc}
     */
    public function writeResult(CoverageResultNode $result)
    {
        $coverage = $result->getCodeCoverage();
        $coverageText = $coverage->formattedValue();

        if ($this->bounds->isSatisfactory($coverage)) {
            $this->console->writeText($coverageText, Color::GREEN);
        } else if ($this->bounds->isCritical($coverage)) {
            $this->console->writeText($coverageText, Color::RED);
        } else {
            $this->console->writeText($coverageText, Color::YELLOW);
        }
    }

}
