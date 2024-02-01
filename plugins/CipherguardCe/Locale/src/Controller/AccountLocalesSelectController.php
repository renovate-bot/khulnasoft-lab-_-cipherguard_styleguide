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
 * @since         3.2.0
 */
namespace Cipherguard\Locale\Controller;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use Cake\Http\Exception\BadRequestException;
use Cipherguard\Locale\Service\SetUserLocaleService;

/**
 * @property \Cipherguard\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings
 */
class AccountLocalesSelectController extends AppController
{
    /**
     * Create or update the user's locale setting.
     *
     * @return void
     */
    public function select()
    {
        $service = new SetUserLocaleService();

        try {
            $setting = $service->save(
                $this->User->id(),
                $this->getRequest()->getData($service::REQUEST_DATA_KEY)
            );
        } catch (ValidationException $e) {
            throw new BadRequestException(__('This is not a valid locale.'));
        }
        $this->success(__('The operation was successful.'), $setting);
    }
}
