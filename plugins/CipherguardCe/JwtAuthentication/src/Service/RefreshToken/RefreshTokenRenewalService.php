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
namespace Cipherguard\JwtAuthentication\Service\RefreshToken;

use App\Model\Entity\AuthenticationToken;
use Cake\Http\ServerRequest;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class RefreshTokenRenewalService extends RefreshTokenAbstractService
{
    /**
     * 1. Consume the refresh token passed in the request
     * 2. Return a new token
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @param \App\Model\Entity\AuthenticationToken $oldRefreshToken Refresh token to consume
     * @param string $accessToken access token
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException if the token is not found
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException if the token was already consumed
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException if the token is expired
     */
    public function renewToken(
        ServerRequest $request,
        AuthenticationToken $oldRefreshToken,
        string $accessToken
    ): AuthenticationToken {
        $this->consumeToken($oldRefreshToken);

        return (new RefreshTokenCreateService())->createToken($request, $oldRefreshToken->user_id, $accessToken);
    }
}
