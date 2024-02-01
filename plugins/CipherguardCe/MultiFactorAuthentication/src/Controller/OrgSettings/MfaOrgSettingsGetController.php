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
 * @since         2.6.0
 */
namespace Cipherguard\MultiFactorAuthentication\Controller\OrgSettings;

use Cake\Http\Exception\BadRequestException;
use Cipherguard\MultiFactorAuthentication\Controller\MfaController;
use Cipherguard\MultiFactorAuthentication\Utility\MfaOrgSettingsDuoBackwardCompatible;

class MfaOrgSettingsGetController extends MfaController
{
    /**
     * Handle Org Settings get request
     *
     * @return void
     */
    public function get()
    {
        $this->User->assertIsAdmin();

        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }
        $config = $this->mfaSettings->getOrganizationSettings()->getConfig();
        /** TODO: Remove this line and its class once the frontend has been updated to use the new format/names */
        $config = MfaOrgSettingsDuoBackwardCompatible::remapGetDuoSettings($config);

        $this->success(__('The operation was successful.'), $config);
    }
}
