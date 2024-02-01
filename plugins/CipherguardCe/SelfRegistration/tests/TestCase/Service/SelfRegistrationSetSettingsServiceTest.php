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
 * @since         3.10.0
 */

namespace Cipherguard\SelfRegistration\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\SelfRegistration\Service\SelfRegistrationBaseSettingsService;
use Cipherguard\SelfRegistration\Service\SelfRegistrationSetSettingsService;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Cipherguard\SelfRegistration\Service\SelfRegistrationSetSettingsService
 */
class SelfRegistrationSetSettingsServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SelfRegistrationSetSettingsService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationSetSettingsService(
            UserFactory::make()->admin()->nonPersistedUAC()
        );
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function isInvalidDataInDb(): array
    {
        return [[true], [false]];
    }

    /**
     * @dataProvider isInvalidDataInDb
     */
    public function testSelfRegistrationSetSettingsService_Valid(bool $isInvalidDataInDb)
    {
        if ($isInvalidDataInDb) {
            $this->setSelfRegistrationSettingsData('provider', 'foo');
        }
        $data = $this->getSelfRegistrationSettingsData();
        $result = $this->service->saveSettings($data);

        $this->assertSame(1, OrganizationSettingFactory::count());
        $organizationSetting = OrganizationSettingFactory::find()->firstOrFail();

        $this->assertSame($this->getExpectedKeys(), array_keys($result));
        $this->assertSame($data, json_decode($organizationSetting->get('value'), true));
        $this->assertSame(
            SelfRegistrationBaseSettingsService::USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME,
            $organizationSetting->get('property')
        );
    }

    public function testSelfRegistrationSetSettingsService_Non_Supported_Provider()
    {
        $data = $this->getSelfRegistrationSettingsData('provider', 'foo');
        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the self registration settings.');
        $this->service->saveSettings($data);
    }
}
