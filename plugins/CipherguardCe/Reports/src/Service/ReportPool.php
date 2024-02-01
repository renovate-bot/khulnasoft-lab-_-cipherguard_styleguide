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
namespace Cipherguard\Reports\Service;

use Cipherguard\Reports\Utility\AbstractReport;

/**
 * Singleton class.
 * Used to add and list available reports.
 *
 * @package Cipherguard\Reports\Utility
 */
class ReportPool
{
    /**
     * Instance of class used for singleton.
     *
     * @var \Cipherguard\Reports\Service\ReportPool|null
     */
    private static $instance;

    /**
     * Reports list.
     *
     * @var array
     */
    private static $reports = [];

    /**
     * Get ReportPool singleton.
     *
     * @return \Cipherguard\Reports\Service\ReportPool
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ReportPool();
        }

        return self::$instance;
    }

    /**
     * Add a report in the report pool.
     *
     * @param \Cipherguard\Reports\Utility\AbstractReport $report The report to add
     * @return \Cipherguard\Reports\Utility\AbstractReport[] list of reports
     */
    public function addReport(AbstractReport $report)
    {
        self::$reports[$report->getSlug()] = $report;

        return self::$reports;
    }

    /**
     * Add reports in the report pool.
     *
     * @param Callable[] $reports list of callable Reports (AbstractReport)
     *
     * Example:
     * [
     *    // Combined reports
     *    EmployeeOnBoardingReport::SLUG => function () {
     *      return new EmployeeOnBoardingReport();
     *    },

     *    // The sky the limit reports -> make setCallableGetData
     *    'my-dynamic-report' => function () {
     *      return (new EmptyCombinedReport())
     *        ->setSlug('my-dynamic-report')
     *        ->setDescription('Out of control reports')
     *        ->setName('No limits')
     *        ->addReport(new NonActiveUsersCountReport());
     *    },
     * ];
     * @return Callable[] list of callable reports (AbstractReport)
     */
    public function addReports(array $reports)
    {
        self::$reports = array_merge(self::$reports, $reports);

        return self::$reports;
    }

    /**
     * Return a an array of callable to instantiate to get a report
     * Each Report is created callable to avoid to instantiate a report collection when it will
     * not be used, but still maintain of a list of the report somewhere
     *
     * @return callable[]
     */
    public function getReports()
    {
        return self::$reports;
    }
}
