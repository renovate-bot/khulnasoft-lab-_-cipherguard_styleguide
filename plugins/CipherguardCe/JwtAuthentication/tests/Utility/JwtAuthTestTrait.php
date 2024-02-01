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
namespace Cipherguard\JwtAuthentication\Test\Utility;

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use Authentication\Identifier\IdentifierInterface;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
use Cipherguard\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Cipherguard\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Cipherguard\JwtAuthentication\Service\Middleware\JwtAuthenticationService;

trait JwtAuthTestTrait
{
    /**
     * Sets the given JWT token in the request header.
     *
     * @param string $token
     * @return void
     */
    public function setJwtTokenInHeader(string $token): void
    {
        $this->configRequest([
            'headers' => [JwtAuthenticationService::JWT_HEADER => 'Bearer ' . $token], // Prefix with Bearer
        ]);
    }

    /**
     * Creates a JWT access token and sets it in the request header.
     * Returns that token
     *
     * @param string|null $userId
     * @return string
     */
    public function createJwtTokenAndSetInHeader(?string $userId = null): string
    {
        if ($userId === null) {
            $userId = UserFactory::make()->user()->persist()->id;
        }
        $token = (new JwtTokenCreateService())->createToken($userId);
        $this->setJwtTokenInHeader($token);

        return $token;
    }

    ////////////// GPG Utils ///////////////////

    protected function getGpgJwtAuth(User $user): GpgJwtAuthenticator
    {
        $request = new ServerRequest();
        $request = $request->withData('user_id', $user->id);

        $GpgJwtAuth = new GpgJwtAuthenticator($this->createMock(IdentifierInterface::class));
        $GpgJwtAuth->setRequest($request);
        $GpgJwtAuth->init();

        return $GpgJwtAuth;
    }

    protected function makeChallenge(User $user, string $verifyToken): string
    {
        return $this->getGpgJwtAuth($user)->getGpg()->encryptSign(json_encode([
            'version' => GpgJwtAuthenticator::PROTOCOL_VERSION,
            'domain' => Router::url('/', true),
            'verify_token' => $verifyToken,
            'verify_token_expiry' => FrozenTime::now()->addMinutes(1)->toUnixString(),
        ]));
    }

    protected function decryptChallenge(User $user, string $challenge): string
    {
        return $this->getGpgJwtAuth($user)->getGpg()->decrypt($challenge);
    }
}
