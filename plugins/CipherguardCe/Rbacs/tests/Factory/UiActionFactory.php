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

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * UiActionFactory
 *
 * @method \Cipherguard\Rbacs\Model\Entity\UiAction|\Cipherguard\Rbacs\Model\Entity\UiAction[] persist()
 * @method \Cipherguard\Rbacs\Model\Entity\UiAction getEntity()
 * @method \Cipherguard\Rbacs\Model\Entity\UiAction[] getEntities()
 * @method static \Cipherguard\Rbacs\Model\Entity\UiAction get($primaryKey, array $options = [])
 */
class UiActionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Cipherguard/Rbacs.UiActions';
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
                'name' => $faker->text(100),
            ];
        });
    }

    /**
     * @return $this
     */
    public function name(string $name)
    {
        return $this->patchData(['name' => $name]);
    }
}
