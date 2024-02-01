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
 * @since         3.10.0
 */

namespace Cipherguard\MultiFactorAuthentication\Service\MfaPolicies;

class DefaultRememberAMonthSettingService implements RememberAMonthSettingInterface
{
    /**
     * {@inheritDoc}
     *
     * **Note:** This method will always return `true` since this is the current default behavior. When MfaPolicies
     *           plugin is enabled this service will be overridden by another service which actually does the check.
     */
    public function isEnabled(): bool
    {
        return true;
    }
}
