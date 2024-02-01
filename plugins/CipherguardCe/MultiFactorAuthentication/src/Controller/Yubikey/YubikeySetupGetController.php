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
namespace Cipherguard\MultiFactorAuthentication\Controller\Yubikey;

use Cake\Http\Exception\BadRequestException;
use Cipherguard\MultiFactorAuthentication\Controller\MfaSetupController;
use Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeySetupGetController extends MfaSetupController
{
    /**
     * Totp Get Qr Code and provisioning urls
     *
     * @param \Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    public function get(MfaFormInterface $setupForm)
    {
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_YUBIKEY);
        try {
            $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_YUBIKEY);
            $this->_handleGetNewSettings($setupForm);
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(MfaSettings::PROVIDER_YUBIKEY);
        }
    }

    /**
     * Handle get request when new settings are needed
     *
     * @param \Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    protected function _handleGetNewSettings(MfaFormInterface $setupForm)
    {
        if (!$this->request->is('json')) {
            $this->set('yubikeySetupForm', $setupForm);
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('mfa_setup')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_YUBIKEY))
                ->setTemplate('setupForm');
        } else {
            $this->success(__('Please setup the Yubikey settings.'));
        }
    }
}
