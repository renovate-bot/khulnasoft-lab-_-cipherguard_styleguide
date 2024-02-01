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

namespace Cipherguard\TotpResourceTypes\Test\TestCase\Service;

use App\Test\Lib\AppTestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Cipherguard\ResourceTypes\Test\Scenario\ResourceTypesScenario;
use Cipherguard\TotpResourceTypes\Service\TotpResourceTypesFinderService;
use Cipherguard\TotpResourceTypes\Test\Scenario\TotpResourceTypesScenario;

/**
 * @covers \Cipherguard\ResourceTypes\Service\ResourceTypesFinderService
 */
class TotpResourceTypesFinderServiceTest extends AppTestCase
{
    use ScenarioAwareTrait;

    /**
     * @var TotpResourceTypesFinderService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new TotpResourceTypesFinderService();
    }

    public function testFindReturnsAllResourceTypesIncludeTotp()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->loadFixtureScenario(TotpResourceTypesScenario::class);

        $result = $this->service->find();

        $this->assertCount(4, $result->toArray());
    }
}
