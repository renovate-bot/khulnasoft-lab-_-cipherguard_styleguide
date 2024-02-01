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

use InvalidArgumentException;

/**
 * Create report service instance. Use the ReportServicePool.
 *
 * @package Cipherguard\Reports\Factory
 */
class ReportViewService
{
    /**
     * @var \Cipherguard\Reports\Service\ReportPool
     */
    private $reportPool;

    /**
     * @param \Cipherguard\Reports\Service\ReportPool $reportPool An instance of ReportPool
     */
    public function __construct(?ReportPool $reportPool = null)
    {
        $this->reportPool = $reportPool ?? ReportPool::getInstance();
    }

    /**
     * Build a object implementing ReportInterface for given slug
     *
     * @param string $reportSlug Slug of the report
     * @param array|null $parameters The report parameters
     * @throws \ReflectionException
     * @return \Cipherguard\Reports\Utility\ReportInterface
     */
    public function getReport(string $reportSlug, ?array $parameters = [])
    {
        $reports = $this->reportPool->getReports();
        /** @var class-string $reportClass */
        $reportClass = $reports[$reportSlug] ?? false;

        if (!$reportClass) {
            throw new InvalidArgumentException();
        }

        $reflectionClass = new \ReflectionClass($reportClass);

        /** @var \Cipherguard\Reports\Utility\ReportInterface $report */
        $report = $reflectionClass->newInstance(...$parameters);

        return $report;
    }
}
