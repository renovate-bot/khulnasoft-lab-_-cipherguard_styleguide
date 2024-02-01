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
 * @since         2.4.0
 */
namespace Cipherguard\MultiFactorAuthentication\Controller\Totp;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Error\Exception\CustomValidationException;
use Cipherguard\MultiFactorAuthentication\Controller\MfaSetupController;
use Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class TotpSetupPostController extends MfaSetupController
{
    /**
     * Handle TOTP setup POST request
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service
     * @param \Cipherguard\MultiFactorAuthentication\Form\MfaFormInterface $totpSetupForm MFA Form
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $totpSetupForm
    ) {
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_TOTP);
        $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_TOTP);

        try {
            $totpSetupForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            } else {
                $this->set('totpSetupForm', $totpSetupForm);
                $this->set('theme', $this->User->theme());
                $this->request = $this->request
                    ->withData('otpQrCodeSvg', $this->request->getData('otpQrCodeSvg'));
                $this->viewBuilder()
                    ->setLayout('mfa_setup')
                    ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_TOTP))
                    ->setTemplate('setupForm');
            }

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $this->_handlePostSuccess(MfaSettings::PROVIDER_TOTP, $sessionIdentificationService);
    }
}
