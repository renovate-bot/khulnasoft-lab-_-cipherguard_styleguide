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

namespace Cipherguard\SmtpSettings\Test\TestCase\Service;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\UserFactory;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\SmtpSettings\Service\SmtpSettingsMigrationService;
use Cipherguard\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Cipherguard\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Cipherguard\SmtpSettings\Service\SmtpSettingsMigrationService
 */
class SmtpSettingsMigrationServiceTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->gpgSetup();
        UserFactory::make()->admin()->persist();
    }

    public function tearDown(): void
    {
        $this->deleteCipherguardDummyFile();
        parent::tearDown();
    }

    public function testSmtpSettingsMigrationServiceTest_Plugin_Disabled()
    {
        $this->disableFeaturePlugin('SmtpSettings');

        $service = new SmtpSettingsMigrationService($this->dummyCipherguardFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
        $this->enableFeaturePlugin('SmtpSettings');
    }

    /**
     * The case will happen on instance where an administrator has not yet completed its registration (extension setup).
     */
    public function testSmtpSettingsMigrationServiceTest_Valid_File_Source_No_Admin()
    {
        // Remove the user in setUp.
        UserFactory::make()->getTable()->deleteAll([]);
        $this->setTransportConfig();
        $this->makeDummyCipherguardFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $service = new SmtpSettingsMigrationService($this->dummyCipherguardFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
    }

    public function testSmtpSettingsMigrationServiceTest_Valid_File_Source()
    {
        $this->setTransportConfig();
        $this->makeDummyCipherguardFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $service = new SmtpSettingsMigrationService($this->dummyCipherguardFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(1, SmtpSettingFactory::count());
        $this->assertInstanceOf(OrganizationSetting::class, $settings);
        $this->assertSourceInSettingsIs('file', $settings);
    }

    /**
     * If the cipherguard SMTP Settings are not completely defined in cipherguard.php
     * Then the file is ignored, the source is considered as env and the settings
     * are not saved in the DB
     */
    public function testSmtpSettingsMigrationServiceTest_Incomplete_File_Source()
    {
        $this->setTransportConfig();
        $this->makeDummyCipherguardFile([
            'EmailTransport' => 'Foo',
        ]);

        $service = new SmtpSettingsMigrationService($this->dummyCipherguardFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
    }

    public function testSmtpSettingsMigrationServiceTest_Valid_Env_Source()
    {
        $this->setTransportConfig();

        $service = new SmtpSettingsMigrationService($this->dummyCipherguardFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
    }

    private function assertSourceInSettingsIs(string $source, ?OrganizationSetting $settings): void
    {
        $this->assertSame($source, $settings['source'] ?? null);
    }
}
