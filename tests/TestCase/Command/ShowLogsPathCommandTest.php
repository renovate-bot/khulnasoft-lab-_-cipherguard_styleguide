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

class ShowLogsPathCommandTest extends AppTestCase
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
     * Basic help test
     */
    public function testShowLogsPathCommandHelp()
    {
        $this->exec('cipherguard show_logs_path -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Shows error logs path for the current environment.');
        $this->assertOutputContains('cake cipherguard show_logs_path');
    }

    /**
     * Basic check with a bit of data.
     */
    public function testShowLogsPathCommand()
    {
        $this->exec('cipherguard show_logs_path');
        $this->assertExitSuccess();
        $this->assertOutputContains(LOGS . 'error.log');
    }
}
