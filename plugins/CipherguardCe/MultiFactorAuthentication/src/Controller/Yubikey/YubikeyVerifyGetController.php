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

use App\Authenticator\SessionIdentificationServiceInterface;
use Cipherguard\MultiFactorAuthentication\Controller\MfaVerifyController;
use Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface;
use Cipherguard\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeyVerifyGetController extends MfaVerifyController
{
    /**
     * Yubikey verify get
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface $verifyForm MFA Form
     * @param \Cipherguard\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember a month setting.
     * @throws \Cake\Http\Exception\InternalErrorException if there is no MFA settings for the user
     * @throws \Cake\Http\Exception\BadRequestException if valid Verification token is already present in cookie
     * @throws \Cake\Http\Exception\BadRequestException if there is no MFA settings for this provider
     * @return void
     */
    public function get(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $verifyForm,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting
    ) {
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_YUBIKEY);

        // Build and return some URI and QR code to work from
        // even though they can be set manually in the post as well
        if (!$this->request->is('json')) {
            $this->set('providers', $this->mfaSettings->getEnabledProviders());
            $this->set('verifyForm', $verifyForm);
            $this->set('theme', $this->User->theme());
            $this->set('isRememberMeForAMonthEnabled', $rememberMeForAMonthSetting->isEnabled());
            $this->viewBuilder()
                ->setLayout('mfa_verify')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_YUBIKEY))
                ->setTemplate('verifyForm');
        } else {
            $this->success(__('Please provide the one-time password.'));
        }
    }
}
