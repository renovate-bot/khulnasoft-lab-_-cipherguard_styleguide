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
 * @since         2.1.0
 */
namespace Cipherguard\AccountSettings\Controller\AccountSettings;

use App\Controller\AppController;

/**
 * @property \Cipherguard\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings
 */
class AccountSettingsIndexController extends AppController
{
    /**
     * AccountSettings Index action
     *
     * @return void
     */
    public function index()
    {
        /** @var \Cipherguard\AccountSettings\Model\Table\AccountSettingsTable $accountSettingsTable */
        $accountSettingsTable = $this->fetchTable('Cipherguard/AccountSettings.AccountSettings');
        $response = $accountSettingsTable->findIndex($this->User->id(), ['theme', 'locale']);
        $this->success(__('The operation was successful.'), $response);
    }
}
