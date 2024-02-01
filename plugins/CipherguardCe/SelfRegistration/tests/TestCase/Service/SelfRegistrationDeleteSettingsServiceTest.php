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

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\SelfRegistration\Service\SelfRegistrationDeleteSettingsService;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Cipherguard\SelfRegistration\Service\SelfRegistrationDeleteSettingsService
 */
class SelfRegistrationDeleteSettingsServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SelfRegistrationDeleteSettingsService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationDeleteSettingsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSelfRegistrationDeleteSettingsService_Valid()
    {
        $settingInDB = $this->setSelfRegistrationSettingsData();
        $result = $this->service->deleteSettings(
            UserFactory::make()->admin()->nonPersistedUAC(),
            $settingInDB->get('id')
        );
        $this->assertTrue($result);
        $this->assertSame(0, OrganizationSettingFactory::count());
    }

    public function testSelfRegistrationDeleteSettingsService_Wrong_ID()
    {
        $this->setSelfRegistrationSettingsData();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The self registration setting does not exist.');
        $this->service->deleteSettings(
            UserFactory::make()->admin()->nonPersistedUAC(),
            'foo'
        );
    }

    public function testSelfRegistrationDeleteSettingsService_Empty_ID()
    {
        $this->setSelfRegistrationSettingsData();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The self registration setting does not exist.');
        $this->service->deleteSettings(
            UserFactory::make()->admin()->nonPersistedUAC(),
            ''
        );
    }
}
