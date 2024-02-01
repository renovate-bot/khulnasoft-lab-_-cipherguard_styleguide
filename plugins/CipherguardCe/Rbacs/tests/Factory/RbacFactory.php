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
 * @copyright     Copyright (c) Khulnasoft Ltd'RL (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         4.1.0
 */

namespace Cipherguard\Rbacs\Test\Factory;

use App\Model\Entity\Role;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Cipherguard\Log\Model\Entity\Action;
use Cipherguard\Rbacs\Model\Entity\Rbac;
use Cipherguard\Rbacs\Model\Entity\UiAction;

/**
 * RbacFactory
 *
 * @method \Cipherguard\Rbacs\Model\Entity\Rbac|\Cipherguard\Rbacs\Model\Entity\Rbac[] persist()
 * @method \Cipherguard\Rbacs\Model\Entity\Rbac getEntity()
 * @method \Cipherguard\Rbacs\Model\Entity\Rbac[] getEntities()
 * @method static \Cipherguard\Rbacs\Model\Entity\Rbac get($primaryKey, array $options = [])
 */
class RbacFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Cipherguard/Rbacs.Rbacs';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'role_id' => $faker->uuid(),
                'control_function' => Rbac::CONTROL_FUNCTION_ALLOW,
                'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
                'foreign_id' => $faker->uuid(),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => FrozenDate::now()->subDays($faker->randomNumber(4)),
                'modified' => FrozenDate::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * @param UiAction $uiAction
     * @return $this
     */
    public function setUiAction(UiAction $uiAction)
    {
        return $this
            ->setField('foreign_id', $uiAction->get('id'))
            ->setField('foreign_model', Rbac::FOREIGN_MODEL_UI_ACTION);
    }

    /**
     * @param Action $action
     * @return $this
     */
    public function setAction(Action $action)
    {
        return $this
            ->setField('foreign_id', $action->get('id'))
            ->setField('foreign_model', Rbac::FOREIGN_MODEL_ACTION);
    }

    /**
     * @return $this
     */
    public function setControlFunction(string $name)
    {
        return $this->setField('control_function', $name);
    }

    /**
     * @return $this
     */
    public function deny()
    {
        return $this->setField('control_function', Rbac::CONTROL_FUNCTION_DENY);
    }

    /**
     * @return $this
     */
    public function allow()
    {
        return $this->setField('control_function', Rbac::CONTROL_FUNCTION_ALLOW);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withRole(string $name)
    {
        return $this->with('Role', compact('name'));
    }

    /**
     * @return $this
     */
    public function admin()
    {
        return $this->withRole(Role::ADMIN);
    }

    /**
     * @return $this
     */
    public function user()
    {
        return $this->withRole(Role::USER);
    }
}
