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

namespace Cipherguard\SelfRegistration\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class SelfRegistrationGetSettingsControllerTest extends AppIntegrationTestCase
{
    use SelfRegistrationTestTrait;

    public function testSelfRegistrationGetSettingsControllerTest_Success()
    {
        $this->logInAsAdmin();
        $settingInDB = $this->setSelfRegistrationSettingsData();

        $this->getJson('/self-registration/settings.json');
        $this->assertResponseOk();

        $retrievedSettings = $this->getResponseBodyAsArray();
        $this->assertSame($this->getExpectedKeys(), array_keys($retrievedSettings));
        $this->assertSame(json_decode($settingInDB->value, true), [
            'provider' => $retrievedSettings['provider'],
            'data' => $retrievedSettings['data'],
        ]);
    }

    public function testSelfRegistrationGetSettingsControllerTest_Guest_Have_No_Access()
    {
        $this->getJson('/self-registration/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSelfRegistrationGetSettingsControllerTest_Admin_Access_Only()
    {
        $this->logInAsUser();
        $this->getJson('/self-registration/settings.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }
}
