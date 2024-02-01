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
namespace Cipherguard\JwtAuthentication\Service\AccessToken;

use Firebase\JWT\JWT;

class JwksGetService extends JwtAbstractService
{
    public const PUBLIC_KEY_PATH = self::JWT_CONFIG_DIR . 'jwt.pem';

    /**
     * @var string
     */
    protected $keyPath = self::PUBLIC_KEY_PATH;

    /**
     * @return string[]
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException if the public key file is not found or not readable.
     */
    public function getPublicKey(): array
    {
        $pubKey = $this->readKeyFileContent();
        $res = openssl_pkey_get_public($pubKey);
        $detail = openssl_pkey_get_details($res);

        return [
            'kty' => 'RSA',
            'alg' => JwtTokenCreateService::JWT_ALG,
            'use' => 'sig',
            'e' => JWT::urlsafeB64Encode($detail['rsa']['e']),
            'n' => JWT::urlsafeB64Encode($detail['rsa']['n']),
        ];
    }

    /**
     * @return string
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException if the public key file is not found or not readable.
     */
    public function getRawPublicKey(): string
    {
        return $this->readKeyFileContent();
    }
}
