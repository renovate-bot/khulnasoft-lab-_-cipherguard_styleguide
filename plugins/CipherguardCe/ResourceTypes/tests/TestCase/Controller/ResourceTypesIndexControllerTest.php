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

namespace Cipherguard\ResourceTypes\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cipherguard\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;
use Cipherguard\ResourceTypes\Test\Scenario\ResourceTypesScenario;

/**
 * @covers \Cipherguard\ResourceTypes\Controller\ResourceTypesIndexController
 */
class ResourceTypesIndexControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public function testResourceTypesIndex_Success()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->logInAsUser();

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertResourceTypeAttributes($this->_responseJsonBody[0]);
        $this->assertCount(2, $this->_responseJsonBody);
    }

    public function testResourceTypesIndex_ErrorNotAuthenticated()
    {
        $this->getJson('/resource-types.json');
        $this->assertAuthenticationError();
    }
}
