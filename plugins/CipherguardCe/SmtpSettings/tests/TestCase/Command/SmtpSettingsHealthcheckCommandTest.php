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
 * @since         3.8.0
 */
namespace Cipherguard\SmtpSettings\Test\TestCase\Command;

use App\Command\HealthcheckCommand;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CipherguardCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cipherguard\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsHealthcheckCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use CipherguardCommandTestTrait;
    use SmtpSettingsTestTrait;

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

    public function testHealthcheckCommand_SmtpSettings_Valid()
    {
        $this->setTransportConfig();
        $this->exec('cipherguard healthcheck --smtpSettings');
        $this->assertExitSuccess();
        if ($this->isFeaturePluginEnabled('SmtpSettings')) {
            $this->assertOutputContains('<success>[PASS]</success> The SMTP Settings plugin is enabled.');
            $this->assertOutputContains('<warning>[WARN] The SMTP Settings source is: ');
        } else {
            $this->assertOutputContains(' <warning>[WARN] The {0} plugin is disabled. Enable the plugin in order to define SMTP settings in the database.</warning>');
        }
        $this->assertOutputContains('No error found. Nice one sparky!');
    }

    public function testHealthcheckCommand_SmtpSettings_Invalid()
    {
        if ($this->isFeaturePluginEnabled('SmtpSettings')) {
            $data = $this->getSmtpSettingsData('host', '');
            $this->encryptAndPersistSmtpSettings($data);
        }

        $this->exec('cipherguard healthcheck --smtpSettings');
        $this->assertExitSuccess();
        if ($this->isFeaturePluginEnabled('SmtpSettings')) {
            $validationErrorMessage = '<error>[FAIL] SMTP Setting errors: {"host":{"_empty":"The host name should not be empty."}}</error>';
            $this->assertOutputContains('<success>[PASS]</success> The SMTP Settings plugin is enabled.');
            $this->assertOutputContains('<success>[PASS]</success> The SMTP Settings source is: database.');
            $this->assertOutputContains($validationErrorMessage);
            $this->assertOutputContains(' 1 error(s) found. Hang in there!');
        } else {
            $this->assertOutputContains(' <warning>[WARN] The {0} plugin is disabled. Enable the plugin in order to define SMTP settings in the database.</warning>');
            $this->assertOutputContains('No error found. Nice one sparky!');
        }
    }

    public function testHealthcheckCommand_SmtpSettings_Plugin_Deactivated()
    {
        $wasPluginEnabled = $this->isFeaturePluginEnabled('SmtpSettings');
        $this->disableFeaturePlugin('SmtpSettings');
        $this->exec('cipherguard healthcheck --smtpSettings');
        $this->assertExitSuccess();
        $this->assertOutputContains(' <warning>[WARN] The SMTP Settings plugin is disabled. Enable the plugin in order to define SMTP settings in the database.</warning>');
        $this->assertOutputContains('No error found. Nice one sparky!');
        if ($wasPluginEnabled) {
            $this->enableFeaturePlugin('SmtpSettings');
        }
    }
}
