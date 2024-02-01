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
namespace Cipherguard\MultiFactorAuthentication\Utility;

use Cake\Datasource\Exception\RecordNotFoundException;

trait MfaAccountSettingsYubikeyTrait
{
    /**
     * Return the yubikey id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if URI is not set
     * @return mixed
     */
    public function getYubikeyId()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_YUBIKEY][self::YUBIKEY_ID])) {
            throw new RecordNotFoundException(__('MFA setting Yubikey Id is not set.'));
        }

        return $this->settings[MfaSettings::PROVIDER_YUBIKEY][self::YUBIKEY_ID];
    }

    /**
     * Check if YubikeyUserId is set
     *
     * @return bool
     */
    public function isYubikeyUserIdSet()
    {
        try {
            $this->getYubikeyId();
        } catch (RecordNotFoundException $exception) {
            return false;
        }

        return true;
    }
}
