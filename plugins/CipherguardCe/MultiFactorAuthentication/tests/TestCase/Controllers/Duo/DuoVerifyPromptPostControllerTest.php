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
namespace Cipherguard\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\OrganizationSettingFactory;
use Duo\DuoUniversal\Client;
use Cipherguard\MultiFactorAuthentication\Service\Duo\MfaDuoStateCookieService;
use Cipherguard\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Cipherguard\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Cipherguard\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Cipherguard\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyPromptPostControllerTest extends MfaIntegrationTestCase
{
    public function testDuoVerifyPromptPostController_Error_NotAuthenticated()
    {
        $this->post('/mfa/verify/duo/prompt');
        $this->assertRedirect();
        $this->assertRedirectContains('/auth/login?redirect=%2Fmfa%2Fverify%2Fduo%2Fprompt');
    }

    public function testDuoVerifyPromptPostController_Error_JsonNotAllowed()
    {
        $user = $this->logInAsUser();
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->postJson('/mfa/verify/duo/prompt.json');
        $errorMessageRegex = 'This functionality is not available using AJAX\/JSON.';
        $this->assertError(400, $errorMessageRegex);
    }

    public function testDuoVerifyPromptPostController_Error_VerifiedNotRequired()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->post('/mfa/verify/duo/prompt');
        $this->assertResponseError('This authentication provider is already verify. Disable it first');
        $this->assertSame(1, OrganizationSettingFactory::count());
    }

    public function testDuoVerifyPromptPostController_Error_InvalidOrgSettings()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->post('/mfa/verify/duo/prompt');
        $this->assertRedirect();
        $this->assertRedirectContains('/');
    }

    public function testDuoVerifyPromptPostController_Success()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user, true, 'Bar');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });
        $redirect = '/app/users';

        $this->post('/mfa/verify/duo/prompt?redirect=' . $redirect);

        $this->assertResponseCode(302);
        $this->assertRedirectContains('https://api-45e9f2ca.duosecurity.com/oauth/v1/authorize?sate=duo-not-so-random-state');

        $authToken = AuthenticationTokenFactory::find()->firstOrFail();
        $storedRedirect = json_decode($authToken->get('data'), true)['redirect'] ?? null;
        $this->assertEquals($redirect, $storedRedirect);
        $token = $authToken->get('token');
        $this->assertCookie($token, MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
        $this->assertCookieNotExpired(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoVerifyPromptPostController_Success_Without_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user, true, 'Bar');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $this->post('/mfa/verify/duo/prompt');

        $this->assertResponseCode(302);
        $this->assertRedirectContains('https://api-45e9f2ca.duosecurity.com/oauth/v1/authorize?sate=duo-not-so-random-state');

        $authToken = AuthenticationTokenFactory::find()->firstOrFail();
        $storedRedirect = json_decode($authToken->get('data'), true)['redirect'] ?? null;
        $this->assertEquals('', $storedRedirect);
        $token = $authToken->get('token');
        $this->assertCookie($token, MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
        $this->assertCookieNotExpired(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }
}
