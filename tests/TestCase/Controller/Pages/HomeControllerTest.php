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

namespace App\Test\TestCase\Controller\Pages;

use App\Test\Lib\AppIntegrationTestCase;

class HomeControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'plugin.Cipherguard/AccountSettings.AccountSettings',
    ];

    public function testHomeNotLoggedInError(): void
    {
        $this->get('/app/passwords');
        $this->assertRedirectContains('/auth/login?redirect=%2Fapp%2Fpasswords');
    }

    public function testHomeSuccess(): void
    {
        $this->logInAsUser();
        $this->get('/app/passwords');
        $this->assertResponseOk();
        $this->assertResponseContains('skeleton');
    }
}
