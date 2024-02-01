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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Auth;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;

class AuthVerifyControllerTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;

    /**
     * Test 200 returns public key and fingerprint
     */
    public function testAuthVerifyController_Success_Get(): void
    {
        $this->get('/auth/verify.json');
        $this->assertResponseOk();

        // Check props
        $data = json_decode($this->_getBodyAsString());
        $this->assertTrue(isset($data->body->fingerprint));
        $this->assertTrue(isset($data->body->keydata));
        $this->assertEquals(Configure::read('cipherguard.gpg.serverKey.fingerprint'), $data->body->fingerprint);
        $this->assertTextContains('-----BEGIN PGP PUBLIC KEY BLOCK-----', $data->body->keydata);
    }

    /**
     * Test 200 using address provided in the headers
     */
    public function testAuthVerifyController_Success_FollowingAuthLoginHeaders(): void
    {
        // get the server public key
        $this->get('/auth/login');
        $verifyUrl = $this->_response->getHeader('X-GPGAuth-Pubkey-URL')[0];

        // Follow the white rabbit
        $this->get($verifyUrl);
        $this->assertResponseOk();

        // Check props
        $data = json_decode($this->_getBodyAsString());
        $this->assertTrue(isset($data->body->fingerprint));
        $this->assertTrue(isset($data->body->keydata));
        $this->assertEquals(Configure::read('cipherguard.gpg.serverKey.fingerprint'), $data->body->fingerprint);
        $this->assertTextContains('-----BEGIN PGP PUBLIC KEY BLOCK-----', $data->body->keydata);
    }

    /**
     * Test error 500 if config is invalid
     */
    public function testAuthVerifyController_Error_BadConfig(): void
    {
        Configure::write('cipherguard.gpg.serverKey.public', 'wrong');
        $this->get('/auth/verify.json');
        $this->assertResponseFailure();
        $data = $this->_getBodyAsString();
        $expect = 'The OpenPGP public key for this cipherguard instance was not found.';
        $this->assertStringContainsString($expect, $data);
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testAuthVerifyController_Error_NotJson(): void
    {
        $this->get('/auth/verify');
        $this->assertResponseCode(404);
    }
}
