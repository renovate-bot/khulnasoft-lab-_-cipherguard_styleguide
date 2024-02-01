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

use App\Command\KeyringInitCommand;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CipherguardCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class KeyringInitCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use CipherguardCommandTestTrait;
    use TruncateDirtyTables;

    /**
     * @var string
     */
    public $key;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        KeyringInitCommand::$isUserRoot = false;
        $this->key = Configure::read('cipherguard.gpg.serverKey.private');
    }

    /**
     * Basic help test
     */
    public function testKeyringInitCommandHelp()
    {
        $this->exec('cipherguard keyring_init -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('GnuPG Keyring init shell for the cipherguard application.');
        $this->assertOutputContains('cake cipherguard keyring_init');
    }

    /**
     * @Given I am root
     * @When I run "cipherguard keyring_init"
     * @Then the command cannot be run.
     */
    public function testKeyringInitCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(KeyringInitCommand::class);
    }

    /**
     * @Given I am not root
     * @When I run "cipherguard keyring_init"
     * @Then it is all O.K..
     */
    public function testKeyringInitCommandWithCorrectKey()
    {
        $this->exec('cipherguard keyring_init');
        $this->assertExitSuccess();
        $this->assertOutputContains('Importing ' . $this->key);
        $this->assertOutputContains('Keyring init OK');
    }

    /**
     * Init an non existing key
     */
    public function testKeyringInitCommandWithNonCorrectKey()
    {
        $wrongFile = 'Blah';
        Configure::write('cipherguard.gpg.serverKey.private', $wrongFile);
        $this->exec('cipherguard keyring_init');
        $this->assertExitError();
        $this->assertOutputContains("The file does not exist: $wrongFile");
        Configure::write('cipherguard.gpg.serverKey.private', $this->key);
    }
}
