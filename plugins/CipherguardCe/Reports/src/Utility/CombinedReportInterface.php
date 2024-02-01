<?php
declare(strict_types=1);

/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         2.13.0
 */
namespace Cipherguard\Reports\Utility;

interface CombinedReportInterface
{
    /**
     * Add a report to the combined report list
     *
     * @param \Cipherguard\Reports\Utility\ReportInterface $report report
     * @return \Cipherguard\Reports\Utility\ReportInterface $this
     */
    public function addReport(ReportInterface $report): ReportInterface;

    /**
     * Get the sub reports list
     *
     * @return \Cipherguard\Reports\Utility\ReportInterface[]
     */
    public function getReports(): array;
}
