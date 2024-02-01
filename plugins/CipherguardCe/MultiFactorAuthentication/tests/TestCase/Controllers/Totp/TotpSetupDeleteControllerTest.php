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
 * @since         2.5.0
 */
namespace Cipherguard\MultiFactorAuthentication\Test\TestCase\Controllers\Totp;

use Cipherguard\AccountSettings\Test\Factory\AccountSettingFactory;
use Cipherguard\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Cipherguard\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Cipherguard\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Cipherguard\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class TotpSetupDeleteControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteTotp
     */
    public function testMfaSetupDeleteTotpNotAuthenticated()
    {
        $this->delete('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteTotp
     */
    public function testMfaSetupDeleteTotpSuccessNothingToDelete()
    {
        $this->authenticateAs('ada');
        $this->delete('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('Nothing to delete');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteTotp
     */
    public function testMfaSetupDeleteTotpSuccessDeleted()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP);
        $this->delete('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('The configuration was deleted.');
        $this->assertSame(0, AccountSettingFactory::count());
        $this->assertSame(1, MfaOrganizationSettingFactory::count());
        $this->assertSame(1, MfaAuthenticationTokenFactory::count());
        $this->assertSame(0, MfaAuthenticationTokenFactory::find()->where(['active' => true])->count());
    }
}
