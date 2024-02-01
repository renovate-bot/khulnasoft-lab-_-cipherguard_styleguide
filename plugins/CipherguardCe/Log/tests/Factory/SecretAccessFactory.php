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
 * @since         4.0.0
 */

namespace Cipherguard\Log\Test\Factory;

use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * SecretAccessFactory
 *
 * @method \Cipherguard\Log\Model\Entity\SecretAccess|\Cipherguard\Log\Model\Entity\SecretAccess[] persist()
 * @method \Cipherguard\Log\Model\Entity\SecretAccess getEntity()
 * @method \Cipherguard\Log\Model\Entity\SecretAccess[] getEntities()
 * @method static \Cipherguard\Log\Model\Entity\SecretAccess get($primaryKey, array $options = [])
 */
class SecretAccessFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Cipherguard/Log.SecretAccesses';
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
                'user_id' => $faker->uuid(),
                'resource_id' => $faker->uuid(),
                'secret_id' => $faker->uuid(),
                'created' => Chronos::now()->subMinutes($faker->randomNumber(8)),
            ];
        });
    }
}
