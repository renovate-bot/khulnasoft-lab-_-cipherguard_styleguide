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
 * @since         4.0.0
 */

namespace Cipherguard\TotpResourceTypes\Test\TestCase\Controller\ResourceTypes;

use App\Test\Lib\AppIntegrationTestCase;
use Cipherguard\ResourceTypes\ResourceTypesPlugin;
use Cipherguard\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;
use Cipherguard\ResourceTypes\Test\Scenario\ResourceTypesScenario;
use Cipherguard\TotpResourceTypes\Test\Scenario\TotpResourceTypesScenario;
use Cipherguard\TotpResourceTypes\TotpResourceTypesPlugin;

/**
 * @see \Cipherguard\ResourceTypes\Controller\ResourceTypesIndexController
 */
class ResourceTypesIndexControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public function testResourceTypesIndex_Success_WithTotpResourceTypes()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->loadFixtureScenario(TotpResourceTypesScenario::class);
        $this->logInAsUser();

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertCount(4, $this->_responseJsonBody);
    }

    public function testResourceTypesIndex_Success_WithoutTotpResourceTypes()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->loadFixtureScenario(TotpResourceTypesScenario::class);
        $this->logInAsUser();
        // Disable plugin
        $this->disableFeaturePlugin(TotpResourceTypesPlugin::class);

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertResourceTypeAttributes($this->_responseJsonBody[0]);
        $this->assertCount(2, $this->_responseJsonBody);
    }

    public function testResourceTypesIndex_ResourceTypesPlugin_Disabled()
    {
        $this->disableFeaturePlugin(ResourceTypesPlugin::class);
        $this->get('/resource-types.json');
        $this->assertResponseCode(404);
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }
}
