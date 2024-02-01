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
 * @since         3.3.0
 */
namespace Cipherguard\JwtAuthentication\Authenticator;

use App\Authenticator\AbstractSessionIdentificationService;
use Cake\Http\ServerRequest;
use Cipherguard\JwtAuthentication\Service\Middleware\JwtAuthenticationService;

class JwtSessionIdentificationService extends AbstractSessionIdentificationService
{
    /**
     * @var string|null
     */
    private $accessToken;

    /**
     * On login, one can pass the access token generated as parameter.
     * This way, the newly login user has a session id.
     * If not set, the token in the header is taken.
     *
     * @param string|null $accessToken Access Token
     */
    public function __construct(?string $accessToken = null)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function getSessionIdentifier(ServerRequest $request): ?string
    {
        // The access token generated is injected after logging in. This is considered as the session ID
        if (isset($this->accessToken)) {
            return $this->accessToken;
        }
        if (!$this->isAuthenticated($request)) {
            return null;
        }

        $token = $request->getHeaderLine(JwtAuthenticationService::JWT_HEADER);

        return str_replace('Bearer ', '', $token);
    }
}
