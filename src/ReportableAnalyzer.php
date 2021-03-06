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

/**
 * Interface ReportableAnalyzer
 * @package cloak
 */
interface ReportableAnalyzer
{

    /**
     * @return void
     */
    public function start();

    /**
     * @return void
     */
    public function stop();

    /**
     * @return bool
     */
    public function isStarted();

    /**
     * @return \cloak\AnalyzedCoverageResult
     */
    public function getResult();

}
