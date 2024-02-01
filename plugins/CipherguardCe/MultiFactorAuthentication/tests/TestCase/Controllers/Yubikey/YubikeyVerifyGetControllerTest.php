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

use Cipherguard\MultiFactorAuthentication\Form\Yubikey\YubikeyVerifyForm;
use Cipherguard\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Cipherguard\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;

class YubikeyVerifyGetControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     */
    public function testMfaVerifyGetYubikeyNotAuthenticated()
    {
        $this->get('/mfa/verify/yubikey.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testMfaVerifyGetYubikey_Success_With_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $this->mockValidMfaFormInterface(YubikeyVerifyForm::class, $this->makeUac($user));

        $this->get('/mfa/verify/yubikey?api-version=v2&redirect=/app/users');

        $this->assertResponseSuccess();
        $this->assertResponseContains('<form');
        $this->assertResponseContains('<input type="password" name="hotp"');
        $this->assertResponseContains('<input type="checkbox" name="remember"');
        $this->assertResponseContains('/app/users');
    }
}
