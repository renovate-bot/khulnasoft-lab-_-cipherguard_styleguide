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
 * @since         3.11.0
 */

namespace Cipherguard\MultiFactorAuthentication\Service;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Utility\UserAccessControl;
use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;
use Cipherguard\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Cipherguard\MultiFactorAuthentication\Utility\MfaVerifiedToken;

/**
 * Class MfaVerifiedCookieService
 */
class MfaVerifiedCookieService
{
    /**
     * Create MFA verified cookie.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Cake\Http\ServerRequest $request Server request
     * @return \Cake\Http\Cookie\Cookie
     */
    public function createDuoMfaVerifiedCookie(
        UserAccessControl $uac,
        SessionIdentificationServiceInterface $sessionIdentificationService,
        ServerRequest $request
    ): Cookie {
        $sessionId = $sessionIdentificationService->getSessionIdentifier($request);
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_DUO, $sessionId);

        return MfaVerifiedCookie::get($request, $token);
    }
}
