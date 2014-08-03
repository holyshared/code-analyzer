<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\report;

/**
 * Interface FileSavableReportInterface
 * @package cloak\report
 */
interface FileSavableReportInterface extends ReportInterface
{

    /**
     * Save the report to a file
     *
     * @param string $path report file name
     */
    public function saveAs($path);

}