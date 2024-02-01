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
 * @since         3.4.0
 */
namespace Cipherguard\Folders\Test\Factory;

use App\Model\Entity\User;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\Traits\FactoryDeletedTrait;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Cipherguard\Folders\Model\Entity\Folder;
use Cipherguard\Folders\Model\Entity\FoldersRelation;

/**
 * FolderFactory
 *
 * @method \Cipherguard\Folders\Model\Entity\Folder|\Cipherguard\Folders\Model\Entity\Folder[] persist()
 * @method \Cipherguard\Folders\Model\Entity\Folder getEntity()
 * @method \Cipherguard\Folders\Model\Entity\Folder[] getEntities()
 */
class FolderFactory extends CakephpBaseFactory
{
    use FactoryDeletedTrait;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Cipherguard/Folders.Folders';
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
                'name' => $faker->text(Folder::MAX_NAME_LENGTH),
                'created' => Chronos::now()->subMinutes($faker->randomNumber(8)),
                'modified' => Chronos::now()->subMinutes($faker->randomNumber(8)),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
            ];
        });
    }

    /**
     * Define the associated permissions to create for a given list of users.
     *
     * @param array $aros Array of users or groups to create a permission for
     * @return FolderFactory
     */
    public function withPermissionsFor(array $aros): FolderFactory
    {
        foreach ($aros as $aro) {
            $aroType = $aro instanceof User ? PermissionsTable::USER_ARO : PermissionsTable::GROUP_ARO;
            $permissionsMeta = ['aco' => PermissionsTable::FOLDER_ACO, 'aro' => $aroType, 'aro_foreign_key' => $aro->id];
            $this->with('Permissions', $permissionsMeta);
        }

        return $this;
    }

    /**
     * Define the associated folders relation to create for a given list of users.
     *
     * @param array $users Array of users to create the folder for
     * @param Folder|null $folderParent The target folder parent
     * @return FolderFactory
     */
    public function withFoldersRelationsFor(array $users, ?Folder $folderParent = null): FolderFactory
    {
        foreach ($users as $user) {
            $folderParentId = !is_null($folderParent) ? $folderParent->id : FoldersRelation::ROOT;
            $folderRelationMeta = ['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER, 'user_id' => $user->id, 'folder_parent_id' => $folderParentId];
            $this->with('FoldersRelations', $folderRelationMeta);
        }

        return $this;
    }
}
