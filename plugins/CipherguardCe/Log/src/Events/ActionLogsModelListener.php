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
 */
namespace Cipherguard\Log\Events;

use Cake\Event\EventListenerInterface;
use Cipherguard\Log\Events\Traits\EntitiesHistoryTrait;

class ActionLogsModelListener implements EventListenerInterface
{
    use EntitiesHistoryTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        /**
         * Return a list if implemented Events, with their callback.
         * The callback is based on the camelized name of the event slug.
         * Example: event "user.add" will have callback "logUserAdd"
         */
        return [
            'Model.afterSave' => 'logEntityHistory',
            'Model.afterDelete' => 'logEntityHistory',
            'Model.afterRead' => 'logEntityHistory',
            'Model.initialize' => 'entityAssociationsInitialize',
        ];
    }
}
