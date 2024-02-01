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

namespace Cipherguard\SelfRegistration\Test\TestCase\Service\Healthcheck;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckService;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Cipherguard\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckServiceTest
 */
class SelfRegistrationHealthcheckServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SelfRegistrationHealthcheckService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationHealthcheckService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSelfRegistrationHealthcheckService_Valid()
    {
        $this->setSelfRegistrationSettingsData();
        $expected = [
            'isSelfRegistrationPluginEnabled' => true,
            'selfRegistrationProvider' => 'Email domain safe list',
            'isRegistrationPublicRemovedFromCipherguard' => is_null(Configure::read('cipherguard.registration.public')),
        ];
        $result = $this->service->getHealthcheck();

        $this->assertSame($expected, $result);
    }

    public function testSelfRegistrationHealthcheckService_Invalid_Settings_In_DB()
    {
        $this->setSelfRegistrationSettingsData('provider', 'invalid');
        $expected = [
            'isSelfRegistrationPluginEnabled' => true,
            'selfRegistrationProvider' => null,
            'isRegistrationPublicRemovedFromCipherguard' => true,
        ];
        $result = $this->service->getHealthcheck();

        $this->assertSame($expected, $result);
    }
}
