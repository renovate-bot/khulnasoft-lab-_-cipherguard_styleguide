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

use App\Command\MysqlExportCommand;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CipherguardCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\ORM\TableRegistry;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class MysqlImportCommandTest extends AppTestCase
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
    }

    /**
     * Basic help test
     */
    public function testMysqlImportCommandHelp()
    {
        $this->exec('cipherguard mysql_import -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Utility to import a mysql database backups.');
        $this->assertOutputContains('cake cipherguard mysql_import');
    }

    /**
     * @Given A backup has been created creating a resource
     * @When I run "cipherguard mysql_import"
     * @Then the command must return success status code
     * @And the resource must have been imported.
     */
    public function testMysqlImportCommandOnDump()
    {
        // Create a file with a simple sql command
        $dir = MysqlExportCommand::CACHE_DATABASE_DIRECTORY;
        $fileName = 'dummy_dump.sql';
        $cmd = "
            INSERT INTO avatars (id, profile_id, created, modified)
            VALUES (
                '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                '2021-03-25 05:48:54',
                '2021-03-25 05:48:54'
            );
        ";
        file_put_contents($dir . DS . $fileName, $cmd);

        // Import that file
        $this->exec("cipherguard mysql_import --file $fileName --dir $dir -d test");
        $this->assertExitSuccess();
        $this->assertOutputContains('Success: SQL file imported');

        // Assert that the avatar was created
        TableRegistry::getTableLocator()->get('Avatars')->find()->firstOrFail();
    }

    /**
     * Various scenarios when specifying a file name or not.
     * This test covers IMO all the cases where $dir is specified.
     */
    public function testMysqlImportCommandWrongDataSource()
    {
        // Create a file with a simple sql command
        $dir = MysqlExportCommand::CACHE_DATABASE_DIRECTORY;
        $fileName = 'dummy_dump.sql';
        $sql = 'THIS IS NOT SQL, AND WILL THROW AN ERROR!!!';
        file_put_contents($dir . DS . $fileName, $sql);

        // Import
        $this->exec("cipherguard mysql_import --file $fileName --dir $dir");
        $this->assertOutputContains('Error: Something went wrong when importing the SQL file');

        // Import with a wrong datasource
        $this->exec("cipherguard mysql_import -file $fileName -dir $dir -d wrong_data_source");
        $this->assertOutputContains('Error: Something went wrong when importing the SQL file');
    }
}
