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
 * @since         3.5.0
 */
namespace Cipherguard\Folders\Test\Factory;

use App\Test\Factory\ResourceFactory as ResourceCoreFactory;
use Cipherguard\Folders\FoldersPlugin;
use Cipherguard\Folders\Model\Entity\Folder;
use Cipherguard\Folders\Model\Entity\FoldersRelation;

/**
 * ResourceFactory
 */
class ResourceFactory extends ResourceCoreFactory
{
    public function initialize(): void
    {
        parent::initialize();
        FoldersPlugin::addAssociationsToResourcesTable($this->getTable());
    }

    /**
     * Define the associated folders relation to create for a given list of users.
     *
     * @param array $users Array of users to create the folder for
     * @param Folder|null $folderParent The target folder parent
     * @return ResourceFactory
     */
    public function withFoldersRelationsFor(array $users, ?Folder $folderParent = null): ResourceFactory
    {
        foreach ($users as $user) {
            $folderParentId = !is_null($folderParent) ? $folderParent->id : FoldersRelation::ROOT;
            $folderRelationMeta = ['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'user_id' => $user->id, 'folder_parent_id' => $folderParentId];
            $this->with('FoldersRelations', $folderRelationMeta);
        }

        return $this;
    }
}
