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
 * @since         3.7.0
 */
namespace Cipherguard\Folders;

use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cipherguard\Folders\EventListener\AddFolderizableBehavior;
use Cipherguard\Folders\EventListener\GroupsUsersEventListener;
use Cipherguard\Folders\EventListener\PermissionsModelInitializeEventListener;
use Cipherguard\Folders\EventListener\ResourcesEventListener;
use Cipherguard\Folders\Notification\Email\FoldersEmailRedactorPool;
use Cipherguard\Folders\Notification\NotificationSettings\FolderNotificationSettingsDefinition;

class FoldersPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $this->registerListeners($app);
        $this->addAssociationsToResourcesTable(TableRegistry::getTableLocator()->get('Resources'));
        $this->addAssociationsToPermissionsTable(TableRegistry::getTableLocator()->get('Permissions'));
    }

    /**
     * Register Tags related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new ResourcesEventListener()) //Add / remove folders relations when a resources is created / deleted
            ->on(new GroupsUsersEventListener()) // Add / remove folders relations when a group members list is updated
            ->on(new AddFolderizableBehavior()) // Decorate the core/other plugins table classes that can be organized in folder
            ->on(new PermissionsModelInitializeEventListener()) // Decorate the permissions table class to add cleanup method
            ->on(new FolderNotificationSettingsDefinition())// Add email notification settings definition
            ->on(new FoldersEmailRedactorPool()); // Register email redactors
    }

    /**
     * @param \Cake\ORM\Table $table Resources table
     * @return void
     */
    public static function addAssociationsToResourcesTable(Table $table): void
    {
        if (!($table instanceof ResourcesTable)) {
            return;
        }

        $table->hasMany('FoldersRelations', [
            'className' => 'Cipherguard/Folders.FoldersRelations',
            'foreignKey' => 'foreign_id',
            'conditions' => [
                'FoldersRelations.foreign_model' => 'Resource',
            ],
            'dependent' => true,
        ]);
    }

    /**
     * @param \Cake\ORM\Table $table Permissions table
     * @return void
     */
    public static function addAssociationsToPermissionsTable(Table $table): void
    {
        if (!($table instanceof PermissionsTable)) {
            return;
        }

        $table->belongsTo('Folders', [
            'className' => 'Cipherguard/Folders.Folders',
            'foreignKey' => 'aco_foreign_key',
        ]);
    }
}
