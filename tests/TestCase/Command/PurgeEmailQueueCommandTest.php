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
 * @since         4.2.0
 */
namespace App\Test\TestCase\Command;

use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class PurgeEmailQueueCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

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
    public function testPurgeEmailQueueCommandHelp()
    {
        $this->exec('cipherguard purge_email_queue -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('cake cipherguard purge_email_queue');
    }

    /**
     * Basic test
     */
    public function testPurgeEmailQueueCommand()
    {
        $this->exec('cipherguard purge_email_queue');
        $this->assertExitSuccess();
        $this->assertOutputContains('Nothing to delete.');
    }
}
