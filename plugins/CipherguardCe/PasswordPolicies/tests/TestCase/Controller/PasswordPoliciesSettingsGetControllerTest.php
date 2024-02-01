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

namespace Cipherguard\PasswordPolicies\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cipherguard\PasswordPolicies\PasswordPoliciesPlugin;
use Cipherguard\PasswordPolicies\Test\Lib\Controller\PasswordPoliciesModelTrait;

/**
 * @covers \Cipherguard\PasswordPolicies\Controller\PasswordPoliciesSettingsGetController
 */
class PasswordPoliciesSettingsGetControllerTest extends AppIntegrationTestCase
{
    use PasswordPoliciesModelTrait;

    public function testPasswordPoliciesSettingsGetController_ErrorUnauthenticated()
    {
        $this->getJson('/password-policies/settings.json');

        $this->assertResponseCode(401);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDefaultSettingsUser()
    {
        $this->logInAsUser();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDefaultSettingsAdmin()
    {
        $this->logInAsAdmin();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody);
    }

    public function testPasswordPoliciesSettingsGetController_ErrorInvalidSetting()
    {
        $this->logInAsAdmin();
        Configure::write(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'invalid-password-generator-type');

        $this->getJson('/password-policies/settings.json');

        $this->assertError(500, 'Could not retrieve the password policies.');
    }
}
