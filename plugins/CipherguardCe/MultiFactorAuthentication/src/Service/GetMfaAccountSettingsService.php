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
 * @since         2.12.0
 */

namespace Cipherguard\MultiFactorAuthentication\Service;

use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Exception;
use Cipherguard\MultiFactorAuthentication\Utility\MfaAccountSettings;

class GetMfaAccountSettingsService
{
    /**
     * @param \App\Model\Entity\User $user User to get MfaSettings
     * @return \Cipherguard\MultiFactorAuthentication\Utility\MfaAccountSettings
     * @throws \Exception
     */
    public function getSettingsForUser(User $user): MfaAccountSettings
    {
        /** @var \Cipherguard\AccountSettings\Model\Entity\AccountSetting $mfaSettings */
        $mfaSettings = $user->get(Query\IsMfaEnabledQueryService::MFA_SETTINGS_PROPERTY) ?? null;
        if (empty($mfaSettings)) {
            throw new Exception('Unable to retrieve MFA settings for user');
        }

        return new MfaAccountSettings(
            new UserAccessControl($user->role->name, $user->id),
            json_decode($mfaSettings->value, true)
        );
    }
}
