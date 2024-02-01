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

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;

class WebInstallerIntegrationTestCase extends AppIntegrationTestCase
{
    use ConfigurationTrait;
    use DatabaseTrait;

    protected $_recover;
    protected $_configured;

    public function setUp(): void
    {
        TableRegistry::getTableLocator()->clear();
        parent::setUp();
        $this->_recover = false;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if ($this->_recover) {
            if ($this->_configured !== null) {
                Configure::write('cipherguard.webInstaller.configured', $this->_configured);
            } else {
                Configure::delete('cipherguard.webInstaller.configured');
            }
        }
        if ($this->isWebInstallerFriendly()) {
            $this->restoreTestConnection();
        }
    }

    public function mockCipherguardIsNotconfigured()
    {
        $this->_recover = true;
        $this->_configured = Configure::read('cipherguard.webInstaller.configured');
        Configure::write('cipherguard.webInstaller.configured', false);
    }

    public function getTestDatasourceFromConfig(): array
    {
        $engine = new PhpConfig();
        try {
            $appValues = $engine->read('app');
        } catch (\Exception $exception) {
            throw new InternalErrorException('config/app.php is missing an needed for this test.', 500, $exception);
        }

        $cipherguardValues = [];
        try {
            $cipherguardValues = $engine->read('cipherguard');
        } catch (\Exception $exception) {
        }

        if (isset($appValues['Datasources']['test']) && isset($cipherguardValues['Datasources']['test'])) {
            $config = array_merge($appValues['Datasources']['test'], $cipherguardValues['Datasources']['test']);
        } else {
            if (!isset($appValues['Datasources']['test']) && !isset($cipherguardValues['Datasources']['test'])) {
                throw new InternalErrorException('A test connection is missing in Datasources config.');
            }
            if (!isset($appValues['Datasources']['test'])) {
                $config = $cipherguardValues['Datasources']['test'];
            } else {
                $config = $appValues['Datasources']['test'];
            }
        }

        return $config;
    }

    public function initWebInstallerSession(?array $options = [])
    {
        $session = ['initialized' => true] + $options;
        $this->session(['webinstaller' => $session]);
    }

    public function restoreTestConnection()
    {
        // Some test may drop the default test connection and replace it
        // with something invalid, we rebuild test connection after each tests
        ConnectionManager::drop('test');
        ConnectionManager::setConfig('test', $this->getTestDatasourceFromConfig());
    }
}
