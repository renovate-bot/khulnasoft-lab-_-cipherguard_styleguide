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

use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\SelfRegistration\Service\SelfRegistrationGetSettingsService;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Cipherguard\SelfRegistration\Service\SelfRegistrationGetSettingsService
 */
class SelfRegistrationGetSettingsServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SelfRegistrationGetSettingsService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationGetSettingsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSelfRegistrationGetSettingsService_Valid()
    {
        $settingInDB = $this->setSelfRegistrationSettingsData();
        $retrievedSettings = $this->service->getSettings();
        $this->assertSame($this->getExpectedKeys(), array_keys($retrievedSettings));
        $this->assertSame(json_decode($settingInDB->value, true), [
            'provider' => $retrievedSettings['provider'],
            'data' => $retrievedSettings['data'],
        ]);
    }

    public function testSelfRegistrationGetSettingsService_No_Settings_In_DB()
    {
        $retrievedSettings = $this->service->getSettings();
        $this->assertSame(['provider' => null, 'data' => null,], $retrievedSettings);
    }

    public function testSelfRegistrationGetSettingsService_Non_Supported_Provider()
    {
        $this->setSelfRegistrationSettingsData('provider', 'foo');
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Could not validate the self registration settings found in database.');
        $this->service->getSettings();
    }
}
