<?php
declare(strict_types=1);

namespace Cipherguard\Log\Test\Factory;

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
 * @since         4.0.0
 */

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ActionFactory
 *
 * @method \Cipherguard\Log\Model\Entity\Action|\Cipherguard\Log\Model\Entity\Action[] persist()
 * @method \Cipherguard\Log\Model\Entity\Action getEntity()
 * @method \Cipherguard\Log\Model\Entity\Action[] getEntities()
 * @method static \Cipherguard\Log\Model\Entity\Action get($primaryKey, array $options = [])
 */
class ActionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Cipherguard/Log.Actions';
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
}
