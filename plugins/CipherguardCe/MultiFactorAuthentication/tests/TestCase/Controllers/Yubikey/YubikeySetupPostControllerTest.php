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
namespace Cipherguard\MultiFactorAuthentication\Test\TestCase\Controllers\Yubikey;

use App\Test\Factory\AuthenticationTokenFactory;
use Cipherguard\MultiFactorAuthentication\Form\Yubikey\YubikeySetupForm;
use Cipherguard\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Cipherguard\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyOrganizationOnlyScenario;
use Cipherguard\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class YubikeySetupPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     */
    public function testMfaSetupPostYubikeyNotAuthenticated()
    {
        $this->post('/mfa/setup/yubikey.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     */
    public function testMfaSetupPostYubikey_Success()
    {
        $user = $this->logInAsUser();
        $sessionID = 'Foo';
        $this->loadFixtureScenario(MfaYubikeyOrganizationOnlyScenario::class);
        $this->mockValidMfaFormInterface(YubikeySetupForm::class, $this->makeUac($user));
        $this->mockSessionId($sessionID);
        $this->post('/mfa/setup/yubikey?api-version=v2', [
            'hotp' => 'i-am-mocked',
        ]);
        $this->assertResponseSuccess();
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertTrue($mfaToken->checkSessionId($sessionID));
        $this->assertCookieIsSecure($mfaToken->token, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}
