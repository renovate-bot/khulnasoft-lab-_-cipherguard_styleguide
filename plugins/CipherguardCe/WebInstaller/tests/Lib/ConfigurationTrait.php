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
namespace Cipherguard\WebInstaller\Test\Lib;

use Cake\Core\Configure;

trait ConfigurationTrait
{
    // Keep a copy of the original cipherguard config.
    private $backupConfig = [];
    private $installerFriendly = null;

    /*
     * Skip the test if the environment is production like:
     * - config/cipherguard.php not writable
     * - config/license not writable
     */

    protected function skipTestIfNotWebInstallerFriendly()
    {
        if (!$this->isWebInstallerFriendly()) {
            $this->markTestSkipped('Config directory not writable, skipping test');
        }
    }

    /*
     * The environment is considered as production (and not friendly) like if :
     * - config/cipherguard.php not writable
     * - or config/license not writable
     */

    protected function isWebInstallerFriendly()
    {
        if (isset($this->installerFriendly)) {
            return $this->installerFriendly;
        }

        $configFolderWritable = is_writable(CONFIG);

        $cipherguardConfigPath = CONFIG . 'cipherguard.php';
        $cipherguardConfigFileIsWritable = file_exists($cipherguardConfigPath) ? is_writable($cipherguardConfigPath) : $configFolderWritable;
        if (!$cipherguardConfigFileIsWritable) {
            $this->installerFriendly = false;

            return $this->installerFriendly;
        }

        $this->installerFriendly = true;

        return $this->installerFriendly;
    }

    /*
     * Backup the cipherguard configuration
     */

    protected function backupConfiguration()
    {
        // Backup the config and restore it after each test.
        $this->backupConfig = [];
        if (file_exists(CONFIG . 'cipherguard.php')) {
            $this->backupConfig['cipherguardConfig'] = file_get_contents(CONFIG . 'cipherguard.php');
        }
        if (file_exists(CONFIG . 'license')) {
            $this->backupConfig['license'] = file_get_contents(CONFIG . 'license');
        }
        $this->backupConfig['public'] = Configure::read('cipherguard.gpg.serverKey.public');
        $this->backupConfig['private'] = Configure::read('cipherguard.gpg.serverKey.private');

        // Write the keys
        Configure::write('cipherguard.gpg.serverKey.public', TMP . 'tests' . DS . 'testkey.asc');
        Configure::write('cipherguard.gpg.serverKey.private', TMP . 'tests' . DS . 'testkey_private.asc');
    }

    /*
     * Restore the cipherguard backup configuration
     */
    protected function restoreConfiguration()
    {
        if (!$this->isWebInstallerFriendly()) {
            return;
        }

        if (is_dir(CONFIG)) {
            chmod(CONFIG, 0770);
        }
        if (file_exists(CONFIG . 'cipherguard.php')) {
            chmod(CONFIG . 'cipherguard.php', 0770);
        }
        if (file_exists(CONFIG . 'license')) {
            chmod(CONFIG . 'license', 0644);
        }
        if (file_exists(CONFIG . 'subscription_key.txt')) {
            chmod(CONFIG . 'subscription_key.txt', 0644);
        }
        if (is_dir(CONFIG . 'gpg')) {
            chmod(CONFIG . 'gpg', 0770);
        }
        if (file_exists(CONFIG . 'gpg/unsecure.key')) {
            chmod(CONFIG . 'gpg/unsecure.key', 0644);
        }
        if (file_exists(CONFIG . 'gpg/unsecure_private.key')) {
            chmod(CONFIG . 'gpg/unsecure_private.key', 0644);
        }

        if (is_dir(CONFIG . 'jwt')) {
            chmod(CONFIG . 'jwt', 0770);
        }

        if (isset($this->backupConfig['cipherguardConfig'])) {
            file_put_contents(CONFIG . 'cipherguard.php', $this->backupConfig['cipherguardConfig']);
        } else {
            if (file_exists(CONFIG . 'cipherguard.php')) {
                unlink(CONFIG . 'cipherguard.php');
            }
        }
        if (file_exists(TMP . 'tests' . DS . 'testkey.asc')) {
            unlink(TMP . 'tests' . DS . 'testkey.asc');
        }
        if (file_exists(TMP . 'tests' . DS . 'testkey_private.asc')) {
            unlink(TMP . 'tests' . DS . 'testkey_private.asc');
        }

        // Write the keys
        Configure::write('cipherguard.gpg.serverKey.public', $this->backupConfig['public']);
        Configure::write('cipherguard.gpg.serverKey.private', $this->backupConfig['private']);
    }
}
