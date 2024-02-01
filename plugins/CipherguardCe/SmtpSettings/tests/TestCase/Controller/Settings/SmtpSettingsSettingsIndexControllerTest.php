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
 * @since         3.8.0
 */

namespace Cipherguard\SmtpSettings\Test\TestCase\Controller\Settings;

use App\Test\Lib\AppIntegrationTestCase;

class SmtpSettingsSettingsIndexControllerTest extends AppIntegrationTestCase
{
    public function testSmtpSettingsSettingsIndexControllerTest()
    {
        $url = '/settings.json?api-version=2';
        $this->getJson($url);
        $this->assertSuccess();
        // Assert that the plugin setting is not visible to non-logged users
        $this->assertFalse(isset($this->_responseJsonBody->cipherguard->plugins->smtpSettings));

        $this->logInAsUser();
        $this->getJson($url);
        $this->assertSuccess();

        // Assert that the plugin is enabled by default
        $this->assertTrue(isset($this->_responseJsonBody->cipherguard->plugins->smtpSettings->enabled));
    }
}
