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
 * @since         2.13.0
 */

namespace Cipherguard\Folders\EventListener;

use App\Model\Table\PermissionsTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cipherguard\Folders\Model\Behavior\PermissionsCleanupBehavior;

/**
 * Listen when the PermissionsTable class is initialized and attach the folders permissions cleanup behaviors to it.
 *
 * Class PermissionsModelInitializeEventListener
 *
 * @package Cipherguard\Folders\EventListener
 */
class PermissionsModelInitializeEventListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.initialize' => $this,
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        if ($event->getSubject() instanceof PermissionsTable) {
            /** @var \Cake\ORM\Table $table */
            $table = $event->getSubject();
            $table->addBehavior(PermissionsCleanupBehavior::class);
        }
    }
}
