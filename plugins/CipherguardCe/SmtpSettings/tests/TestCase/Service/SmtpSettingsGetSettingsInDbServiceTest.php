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

use App\Error\Exception\FormValidationException;
use App\Test\Factory\OrganizationSettingFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;
use Cipherguard\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Cipherguard\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService
 */
class SmtpSettingsGetSettingsInDbServiceTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SmtpSettingsGetSettingsInDbService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsGetSettingsInDbService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSmtpSettingsGetSettingsInDbService_Valid()
    {
        $data = $this->getSmtpSettingsData();
        $this->encryptAndPersistSmtpSettings($data);

        $settings = $this->service->getSettings();

        $this->assertDBSettingsHaveTheRightKeys($settings);
    }

    public function testSmtpSettingsGetSettingsInDbService_No_Valid_Data()
    {
        $this->encryptAndPersistSmtpSettings(['Foo' => 'Bar']);
        $this->expectException(FormValidationException::class);
        $this->service->getSettings();
    }

    public function testSmtpSettingsGetSettingsInDbService_No_Data_In_DB()
    {
        OrganizationSettingFactory::make()->persist();
        $settings = $this->service->getSettings();
        $this->assertSame(null, $settings);
    }
}
