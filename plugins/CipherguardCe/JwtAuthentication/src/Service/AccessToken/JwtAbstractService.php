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

use Cipherguard\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;

abstract class JwtAbstractService
{
    public const USER_ACCESS_TOKEN_KEY = 'access_token';
    public const JWT_CONFIG_DIR = CONFIG . 'jwt' . DS;

    /**
     * @var string
     */
    protected $keyPath;

    /**
     * @param string $path Path to the secret/private key file
     * @return self
     */
    public function setKeyPath(string $path): self
    {
        $this->keyPath = $path;

        return $this;
    }

    /**
     * @return string Path to the secret/private key file
     */
    public function getKeyPath(): string
    {
        return $this->keyPath;
    }

    /**
     * @return string Content of the secret/private key file
     * @throws \Cipherguard\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException if the file is not found or not readable.
     */
    public function readKeyFileContent(): string
    {
        if (!is_readable($this->getKeyPath())) {
            $userErrorMessage = __('The key pair for JWT Authentication is not complete.');
            throw new InvalidJwtKeyPairException($userErrorMessage);
        }

        return file_get_contents($this->getKeyPath());
    }
}
