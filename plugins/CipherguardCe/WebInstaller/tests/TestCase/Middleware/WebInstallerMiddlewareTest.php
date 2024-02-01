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
namespace Cipherguard\WebInstaller\Test\TestCase\Middleware;

use Cake\Core\Configure;
use Cipherguard\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class WebInstallerMiddlewareTest extends WebInstallerIntegrationTestCase
{
    public function testWebInstallerMiddleware_testConfiguredRedirect()
    {
        $backUpConfigured = Configure::read('cipherguard.webInstaller.configured');
        Configure::write('cipherguard.webInstaller.configured', true);
        $this->get('/users');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/auth/login');
        Configure::write('cipherguard.webInstaller.configured', $backUpConfigured);
    }

    public function testWebInstallerMiddleware_testConfiguredForbidden()
    {
        $backUpConfigured = Configure::read('cipherguard.webInstaller.configured');
        Configure::write('cipherguard.webInstaller.configured', true);
        // We load the plugin here manually in order to ensure the routes to be defined.
        $this->loadPlugins(['Cipherguard/WebInstaller' => ['bootstrap' => true, 'routes' => true]]);
        $this->get('/install');
        $this->assertResponseCode(403);
        Configure::write('cipherguard.webInstaller.configured', $backUpConfigured);
    }

    public function testWebInstallerMiddleware_testMockNotConfiguredStartPage()
    {
        $this->mockCipherguardIsNotconfigured();
        $this->get('/install');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('<div id="container" class="page setup start', $data);
    }

    public function testWebInstallerMiddleware_testNotConfiguredRedirect()
    {
        $this->mockCipherguardIsNotconfigured();
        $uris = ['/', 'auth/login', 'resources.json', 'users/recover'];
        foreach ($uris as $uri) {
            $this->get($uri);
            $this->assertResponseCode(302);
            $this->assertRedirectContains('/install');
            $this->_response = null; // Free the memory usage.
        }
    }
}
