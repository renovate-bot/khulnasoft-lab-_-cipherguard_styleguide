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
 * @since         2.5.0
 */
namespace Cipherguard\MultiFactorAuthentication\Test\Lib;

use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

trait MfaAccountSettingsTestTrait
{
    /**
     * @param string|UserAccessControl $user
     * @param array $data data
     * @throws \Exception
     */
    public function mockMfaAccountSettings($user, array $data)
    {
        if ($user instanceof UserAccessControl) {
            $userId = $user->getId();
        } else {
            $userId = UuidFactory::uuid('user.id.' . $user);
        }
        $data = json_encode($data);
        /** @var \Cipherguard\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings */
        $AccountSettings = TableRegistry::getTableLocator()->get('Cipherguard/AccountSettings.AccountSettings');
        $AccountSettings->createOrUpdateSetting($userId, MfaSettings::MFA, $data);
    }
}
