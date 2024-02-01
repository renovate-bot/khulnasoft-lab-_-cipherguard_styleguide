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

use Cake\Datasource\Exception\RecordNotFoundException;
use Cipherguard\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class RefreshTokenAuthenticationService extends RefreshTokenAbstractService
{
    /**
     * Fetch the user from a provided refresh token.
     *
     * @param ?string $token Token to retrieve
     * @return string refresh token
     * @throws \InvalidArgumentException if the token is not a valid UUIDs
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException When there is no user associated to this token.
     */
    public function getUserIdFromToken(?string $token): string
    {
        $this->validateRefreshToken($token);

        try {
            return $this->queryRefreshToken($token)->firstOrFail()->get('user_id');
        } catch (RecordNotFoundException $e) {
            throw new RefreshTokenNotFoundException();
        }
    }
}
