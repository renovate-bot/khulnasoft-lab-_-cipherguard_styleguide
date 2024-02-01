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
 * @since         4.1.0
 */

namespace Cipherguard\Rbacs\Test\TestCase\Controller\Rbacs;

use App\Test\Factory\RoleFactory;
use App\Utility\UuidFactory;
use Cipherguard\Rbacs\Model\Entity\Rbac;
use Cipherguard\Rbacs\Service\Rbacs\RbacsInsertDefaultsService;
use Cipherguard\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Cipherguard\Rbacs\Test\Factory\RbacFactory;
use Cipherguard\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * Cipherguard\Rbacs\Controller\Rbacs\RbacsUpdateController Test Case
 *
 * @uses \Cipherguard\Rbacs\Controller\Rbacs\RbacsUpdateController
 */
class RbacsUpdateControllerTest extends RbacsIntegrationTestCase
{
    /**
     * @return \Cipherguard\Rbacs\Model\Entity\Rbac entity
     * @throws \Exception
     */
    private function setupDefaultRbacs(): Rbac
    {
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();
        (new UiActionsInsertDefaultsService())->insertDefaultsIfNotExist();
        $rbacs = (new RbacsInsertDefaultsService())->allowAllUiActionsForUsers();

        /** @var \Cipherguard\Rbacs\Model\Entity\Rbac $rbac */
        $rbac = $rbacs[0];

        return $rbac;
    }

    public function testRbacsUpdateController_Success(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        $this->assertSuccess();

        $c = RbacFactory::find()->where(['control_function' => Rbac::CONTROL_FUNCTION_DENY])->count();
        $this->assertEquals(1, $c);
    }

    public function testRbacsUpdateController_Error_NotExist(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ],[
            'id' => UuidFactory::uuid(),
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        $this->assertResponseCode(404);

        $c = RbacFactory::find()->where(['control_function' => Rbac::CONTROL_FUNCTION_DENY])->count();
        $this->assertEquals(0, $c);
    }

    public function testRbacsUpdateController_Error_NotValid(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [[
            'id' => $rbac->id,
            'control_function' => 'test',
        ]]);
        $this->assertResponseCode(400);
    }

    public function testRbacsUpdateController_Error_NotLoggedIn(): void
    {
        RoleFactory::make()->guest()->persist();
        $this->postJson('/rbacs.json', []);
        $this->assertResponseCode(401);
    }

    public function testRbacsUpdateController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/rbacs.json', []);
        $this->assertResponseCode(403);
    }

    public function testRbacsUpdateController_Error_NotJson(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->put('/rbacs', [[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        $this->assertResponseCode(404);
    }
}
