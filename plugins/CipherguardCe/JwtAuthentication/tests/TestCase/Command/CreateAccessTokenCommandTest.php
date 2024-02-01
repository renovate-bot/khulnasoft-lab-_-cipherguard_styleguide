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
namespace Cipherguard\JwtAuthentication\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CipherguardCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * @uses \Cipherguard\JwtAuthentication\Command\CreateAccessTokenCommand
 */
class CreateAccessTokenCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use CipherguardCommandTestTrait;
    use TruncateDirtyTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->enableFeaturePlugin('JwtAuthentication');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('JwtAuthentication');
    }

    /**
     * Basic help test
     */
    public function testCreateAccessTokenCommandHelp()
    {
        $this->exec('cipherguard create_access_token -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Create a JSON Web Token.');
        $this->assertOutputContains('cake cipherguard create_access_token');
    }

    public function testCreateAccessTokenCommandWithUsername()
    {
        $user = UserFactory::make()->user()->persist();
        $this->exec('cipherguard create_access_token -u ' . $user->username);
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . $user->username . ' valid 5 minutes:');
    }

    public function testCreateAccessTokenCommandWithUserId()
    {
        $user = UserFactory::make()->user()->persist();
        $this->exec('cipherguard create_access_token -i ' . $user->id);
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . $user->username . ' valid 5 minutes:');
    }

    public function testCreateAccessTokenCommandWithExpiry()
    {
        $user = UserFactory::make()->user()->persist();
        $expiry = 10;
        $this->exec('cipherguard create_access_token -i ' . $user->id . ' -e ' . $expiry);
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . $user->username . " valid {$expiry} minutes:");
    }

    public function testCreateAccessTokenCommandWithExpiryInWordFormat()
    {
        $user = UserFactory::make()->user()->persist();
        $expiry = '5 seconds';
        $this->exec('cipherguard create_access_token -i ' . $user->id . ' -e "' . $expiry . '"');
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . $user->username . " valid {$expiry}:");
    }

    public function testCreateAccessTokenCommandWithNoUserParams()
    {
        $this->exec('cipherguard create_access_token');
        $this->assertExitError();
    }

    public function testCreateAccessTokenCommandWithWrongUsername()
    {
        $this->exec('cipherguard create_access_token -u foo');
        $this->assertExitError();
    }

    public function testCreateAccessTokenCommandInProdMod()
    {
        $user = UserFactory::make()->user()->persist();
        $debug = Configure::read('debug');
        Configure::write('debug', false);
        $this->exec('cipherguard create_access_token -i ' . $user->id);
        $this->assertExitError();
        Configure::write('debug', $debug);
    }
}
