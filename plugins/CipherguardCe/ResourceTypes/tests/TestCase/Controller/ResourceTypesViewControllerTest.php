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
use App\Utility\UuidFactory;
use Cipherguard\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Cipherguard\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;

/**
 * @covers \Cipherguard\ResourceTypes\Controller\ResourceTypesViewController
 */
class ResourceTypesViewControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public function testResourceTypesView_Success()
    {
        $this->logInAsUser();
        /** @var \Cipherguard\ResourceTypes\Model\Entity\ResourceType[] $resourceTypes */
        $resourceTypes = ResourceTypeFactory::make(2)->persist();
        $resourceType = $resourceTypes[0]->id;
        $this->getJson("/resource-types/$resourceType.json?api-version=2");
        $this->assertSuccess();
        $this->assertResourceTypeAttributes($this->_responseJsonBody);
    }

    public function testResourceTypesView_ErrorNotValidId()
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->getJson("/resource-types/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testResourceTypesView_ErrorNotFound()
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->getJson("/resource-types/$resourceId.json");
        $this->assertError(404, 'The resource type does not exist.');
    }

    public function testResourceTypesView_ErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid();
        $this->getJson("/resource-types/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
