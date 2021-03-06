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

use cloak\event\AnalyzeStopEvent;


/**
 * Interface AnalyzeStopEventListener
 * @package cloak\reporter
 */
interface AnalyzeStopEventListener
{

    /**
     * @param AnalyzeStopEvent $event
     * @return mixed
     */
    public function onAnalyzeStop(AnalyzeStopEvent $event);

}
