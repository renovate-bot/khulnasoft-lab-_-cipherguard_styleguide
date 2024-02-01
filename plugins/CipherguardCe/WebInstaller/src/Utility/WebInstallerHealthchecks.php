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
namespace Cipherguard\WebInstaller\Utility;

use Cake\Core\Configure;

class WebInstallerHealthchecks
{
    /**
     * Run all databases health checks
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function all(?array $checks = []): array
    {
        $checks = self::canWriteConfig($checks);

        return $checks;
    }

    /**
     * Check if application can write into the config folder
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function canWriteConfig(?array $checks = []): array
    {
        $configFolderWritable = is_writable(CONFIG);

        $cipherguardConfigPath = CONFIG . 'cipherguard.php';
        $cipherguardConfigFileIsWritable = file_exists($cipherguardConfigPath) ?
            is_writable($cipherguardConfigPath) : $configFolderWritable;
        $checks['webInstaller']['cipherguardConfigWritable'] = $cipherguardConfigFileIsWritable;

        $keyFolderWritable = is_writable(dirname(Configure::read('cipherguard.gpg.serverKey.public')));

        $publicKeyPath = Configure::read('cipherguard.gpg.serverKey.public');
        $publicKeyFileIsWritable = file_exists($publicKeyPath) ? is_writable($publicKeyPath) : $keyFolderWritable;
        $checks['webInstaller']['publicKeyWritable'] = $publicKeyFileIsWritable;

        $privateKeyPath = Configure::read('cipherguard.gpg.serverKey.private');
        $privateKeyFileIsWritable = file_exists($privateKeyPath) ? is_writable($privateKeyPath) : $keyFolderWritable;
        $checks['webInstaller']['privateKeyWritable'] = $privateKeyFileIsWritable;

        return $checks;
    }
}
