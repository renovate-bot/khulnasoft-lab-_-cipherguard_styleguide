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

use App\Model\Entity\User;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cipherguard\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Cipherguard\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Cipherguard\JwtAuthentication\Service\VerifyToken\VerifyTokenCreateService;

class JwtArmoredChallengeService implements JwtArmoredChallengeInterface
{
    /**
     * @inheritDoc
     */
    public function makeArmoredChallenge(ServerRequest $request, User $user, string $verifyToken): array
    {
        $accessToken = (new JwtTokenCreateService())->createToken($user->id);
        $refreshToken = (new RefreshTokenCreateService())->createToken($request, $user->id, $accessToken)->token;
        $verifyToken = (new VerifyTokenCreateService())->createToken($verifyToken, $user->id)->token;

        return [
            'version' => GpgJwtAuthenticator::PROTOCOL_VERSION,
            'domain' => Router::url('/', true),
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'verify_token' => $verifyToken,
        ];
    }
}
