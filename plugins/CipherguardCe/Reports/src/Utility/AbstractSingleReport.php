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

abstract class AbstractSingleReport extends AbstractReport
{
    public const SINGLE_REPORT_TEMPLATE = 'Cipherguard/Reports.SingleReport';
    public const SINGLE_REPORT_TYPE = 'single';

    public const STATUS_SUCCESS = 'success';
    public const STATUS_IN_PROGRESS = 'in-progress';
    public const STATUS_FAIL = 'fail';

    /**
     * Return the template associated to the generated report by the report generator.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        return $this->template ?? self::SINGLE_REPORT_TEMPLATE;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return self::SINGLE_REPORT_TYPE;
    }
}
