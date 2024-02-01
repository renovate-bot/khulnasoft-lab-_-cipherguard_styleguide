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
use App\Error\Exception\CustomValidationException;
use Cipherguard\MultiFactorAuthentication\Controller\MfaVerifyController;
use Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface;
use Cipherguard\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeyVerifyPostController extends MfaVerifyController
{
    /**
     * Yubikey verify post
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface $verifyForm MFA Form
     * @param \Cipherguard\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember a month setting.
     * @throws \Cake\Http\Exception\InternalErrorException
     * @throws \Cake\Http\Exception\BadRequestException
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $verifyForm,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting
    ) {
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_YUBIKEY);

        // Verify hotp
        try {
            $verifyForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            $this->request = $this->request
                ->withData('hotp', '');

            if ($this->request->is('json')) {
                throw $exception;
            }
            // Display form with error msg
            $this->set('providers', $this->mfaSettings->getEnabledProviders());
            $this->set('verifyForm', $verifyForm);
            $this->set('isRememberMeForAMonthEnabled', $rememberMeForAMonthSetting->isEnabled());
            $this->viewBuilder()
                ->setLayout('mfa_verify')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_YUBIKEY))
                ->setTemplate('verifyForm');

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $this->_generateMfaToken(
            MfaSettings::PROVIDER_YUBIKEY,
            $sessionIdentificationService,
            $rememberMeForAMonthSetting
        );
        $this->_handleVerifySuccess();
    }
}
