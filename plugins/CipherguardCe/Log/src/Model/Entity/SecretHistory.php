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
 * @since         2.0.0
 */

namespace Cipherguard\Log\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property string $id
 * @property string $user_id
 * @property string $resource_id
 * @property \Cipherguard\Log\Model\Entity\EntityHistory|null $entities_history
 * @property \App\Model\Entity\Resource $resource
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\User $secrets_history_user
 * @property \App\Model\Entity\Resource $secrets_history_resource
 */
class SecretHistory extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => false,
        'user_id' => false,
        'resource_id' => false,
    ];
}
