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
 * @since         3.7.4
 */
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;

class HealthcheckErrorControllerTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;

    public function testHealthcheckErrorController_Error_Disabled(): void
    {
        $og = Configure::read('cipherguard.healthcheck.error');
        Configure::write('cipherguard.healthcheck.error', false);
        $this->get('/healthcheck/error.json');
        $this->assertResponseCode(404);
        Configure::write('cipherguard.healthcheck.error', $og);
    }

    public function testHealthcheckErrorController_Error_Enabled(): void
    {
        $og = Configure::read('cipherguard.healthcheck.error');
        Configure::write('cipherguard.healthcheck.error', true);
        $this->get('/healthcheck/error.json');
        $this->assertResponseCode(500);
        Configure::write('cipherguard.healthcheck.error', $og);
    }
}
