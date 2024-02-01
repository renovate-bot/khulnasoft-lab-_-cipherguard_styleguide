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
 * @since         3.8.0
 */
namespace Cipherguard\SmtpSettings\Controller;

use App\Controller\AppController;
use Cipherguard\SmtpSettings\Service\SmtpSettingsGetService;
use Cipherguard\SmtpSettings\Service\SmtpSettingsSetService;

class SmtpSettingsPostController extends AppController
{
    /**
     * SmtpSettings POST action
     *
     * @return void
     */
    public function post()
    {
        $this->User->assertIsAdmin();

        $service = new SmtpSettingsSetService($this->User->getAccessControl());
        $service->saveSettings($this->getRequest()->getData());

        $settings = (new SmtpSettingsGetService())->getSettings();

        $this->success(__('The operation was successful.'), $settings);
    }
}
