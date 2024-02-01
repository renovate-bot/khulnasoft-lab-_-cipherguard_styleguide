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

use App\Test\Lib\AppTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * App\Command\CipherguardCommand Test Case
 *
 * @uses \App\Command\CipherguardCommand
 */
class CipherguardCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
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
    }

    /**
     * Test the help option
     *
     * @return void
     */
    public function testCipherguardCommandHelp(): void
    {
        $this->exec('cipherguard -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('The Cipherguard CLI');
        $this->assertOutputContains('console.');
        $this->assertOutputContains('cake cipherguard');
    }
}
