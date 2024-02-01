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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Command\HealthcheckCommand;
use App\Model\Table\RolesTable;
use App\Model\Validation\EmailValidationRule;
use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CipherguardCommandTestTrait;
use App\Utility\Healthchecks;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class HealthcheckCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use CipherguardCommandTestTrait;
    use SelfRegistrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        HealthcheckCommand::$isUserRoot = false;
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();

        TableRegistry::getTableLocator()->clear();
    }

    /**
     * Basic help test
     */
    public function testHealthcheckCommandHelp()
    {
        $this->exec('cipherguard healthcheck -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the configuration of this installation and associated environment.');
        $this->assertOutputContains('cake cipherguard healthcheck');
        // Ensure that all checks are displayed in the help
        foreach (HealthcheckCommand::ALL_HEALTH_CHECKS as $check) {
            $this->assertOutputContains($check);
        }
    }

    /**
     * Will fail if run as root
     */
    public function testHealthcheckCommandRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(HealthcheckCommand::class);
    }

    /**
     * Basic test
     */
    public function testHealthcheckCommand()
    {
        $this->exec('cipherguard healthcheck -d test');
        $this->assertExitSuccess();
        $this->assertOutputContains('<warning>[WARN] SSL peer certificate does not validate</warning>');
        $this->assertOutputContains('<warning>[WARN] Hostname does not match when validating certificates.</warning>');
        // Since the tests run with debug on, here will always be at least one error in the healthcheck.
        $this->assertOutputContains('error(s) found. Hang in there!');
    }

    public function testHealthcheckCommand_Environment_Unhappy_Path()
    {
        Configure::write(Healthchecks::PHP_MIN_VERSION_CONFIG, '40');
        Configure::write(Healthchecks::PHP_NEXT_MIN_VERSION_CONFIG, '50');
        $this->exec('cipherguard healthcheck -d test --environment');

        $this->assertExitSuccess();

        $this->assertOutputContains('[FAIL] PHP version is too low, cipherguard need PHP 40 or higher.');
        $this->assertOutputContains('[WARN] PHP version less than 50 will soon be not supported by cipherguard, so consider upgrading your operating system or PHP environment.');
    }

    public function testHealthcheckCommand_Application_Happy_Path()
    {
        Configure::write('cipherguard.version', '4.1.1');
        Configure::write('cipherguard.remote.version', 'v4.1.0');
        Configure::write('cipherguard.ssl.force', true);
        Configure::write('App.fullBaseUrl', 'https://cipherguard.local');
        Configure::write('cipherguard.selenium.active', false);
        Configure::write('cipherguard.meta.robots', 'noindex');
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        Configure::write('cipherguard.js.build', 'production');
        Configure::write('cipherguard.email.send', '');

        $this->exec('cipherguard healthcheck -d test --application');

        $this->assertExitSuccess();
        $this->assertOutputContains('Using latest cipherguard version');
        $this->assertOutputContains('Cipherguard is configured to force SSL use.');
        $this->assertOutputContains('App.fullBaseUrl is set to HTTPS.');
        $this->assertOutputContains('Selenium API endpoints are disabled.');
        $this->assertOutputContains('Search engine robots are told not to index content.');
        $this->assertOutputContains('The Self Registration plugin is enabled.');
        $this->assertOutputContains('<info>[INFO]</info> Registration is closed, only administrators can add users.');
        $this->assertOutputContains('Host availability will be checked.');
        $this->assertOutputContains('Serving the compiled version of the javascript app.');
        $this->assertOutputContains('All email notifications will be sent.');
        $this->assertOutputContains('No error found. Nice one sparky!');
    }

    public function testHealthcheckCommand_Application_Unhappy_Path()
    {
        Configure::write('cipherguard.version', '1.0.0');
        Configure::write('cipherguard.remote.version', '9.9.9');
        Configure::write('cipherguard.ssl.force', false);
        Configure::write('App.fullBaseUrl', 'http://cipherguard.local');
        Configure::write('cipherguard.selenium.active', true);
        Configure::write('cipherguard.meta.robots', '');
        Configure::write('cipherguard.registration.public', true);
        $this->setSelfRegistrationSettingsData();
        Configure::write(EmailValidationRule::MX_CHECK_KEY, false);
        Configure::write('cipherguard.js.build', 'test');
        Configure::write('cipherguard.email.send', 'false');

        $this->exec('cipherguard healthcheck -d test --application');

        $this->assertExitSuccess();
        $this->assertOutputContains('This installation is not up to date. Currently using 1.0.0 and it should be 9.9.9.');
        $this->assertOutputContains('Cipherguard is not configured to force SSL use.');
        $this->assertOutputContains('App.fullBaseUrl is not set to HTTPS.');
        $this->assertOutputContains('Selenium API endpoints are active.');
        $this->assertOutputContains('Search engine robots are not told not to index content.');
        $this->assertOutputContains('<info>[INFO]</info> The self registration provider is: Email domain safe list.');
        $this->assertOutputContains('You may remove the "cipherguard.registration.public" setting.');
        $this->assertOutputContains('Host availability checking is disabled.');
        $this->assertOutputContains('Using non-compiled Javascript.');
        $this->assertOutputContains('Some email notifications are disabled by the administrator.');
        $this->assertOutputContains('error(s) found');
    }

    public function testHealthcheckCommand_Database_ConnectionError()
    {
        /**
         * Create a dummy database connection, so we can get error.
         *
         * Here we have to use alias since we are only allowing 'default' and 'test' connection to run healthcheck on.
         */
        ConnectionManager::setConfig('invalid', ['url' => 'mysql://foo:bar@localhost/invalid_database']);
        ConnectionManager::alias('invalid', 'default');

        $this->exec('cipherguard healthcheck -d default --database');

        $this->assertExitSuccess();
        $this->assertOutputContains('not able to connect to the database');
        $this->assertOutputContains('No table found');
        $this->assertOutputContains('No default content found');
        $this->assertOutputContains('database schema is not up to date');
        $this->assertOutputContains('4 error(s) found. Hang in there');
        /**
         * Clean up: Drop connection created for testing and reinstate default alias to 'test'.
         *
         * @see https://book.cakephp.org/4/en/development/testing.html#test-connections
         */
        ConnectionManager::alias('test', 'default');
        ConnectionManager::drop('invalid');
    }

    public function testHealthcheckCommand_Database_Happy_Path()
    {
        RoleFactory::make(RolesTable::ALLOWED_ROLE_NAMES)->persist();

        $this->exec('cipherguard healthcheck --database');
        $this->assertExitSuccess();

        $this->assertOutputContains('The application is able to connect to the database');
        $this->assertOutputContains('tables found');
        $this->assertOutputContains('Some default content is present');
        $this->assertOutputContains('The database schema up to date.');
    }
}
