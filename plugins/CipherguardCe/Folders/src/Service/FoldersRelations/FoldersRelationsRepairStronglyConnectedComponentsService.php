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

namespace Cipherguard\Folders\Service\FoldersRelations;

use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cipherguard\Folders\Model\Entity\FoldersRelation;

class FoldersRelationsRepairStronglyConnectedComponentsService
{
    /**
     * @var \Cipherguard\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \Cipherguard\Folders\Service\FoldersRelations\FoldersRelationsSortService
     */
    private $foldersRelationsSortService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Cipherguard/Folders.FoldersRelations');
        $this->foldersRelationsSortService = new FoldersRelationsSortService();
    }

    /**
     * Repair a set of strongly connected components.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The user tree that is at the origin at the conflict. Most of the time it's the modified
     *                        tree.
     * @param array<\Cipherguard\Folders\Model\Entity\FoldersRelation> $foldersRelations The relations forming a strongly connected components set
     * @return \Cipherguard\Folders\Model\Entity\FoldersRelation|null Return the folder relation that was broken to repair the SCC.
     */
    public function repair(UserAccessControl $uac, string $userId, array $foldersRelations)
    {
        $folderRelationToBreak = $this->identifyFolderRelationToBreak($uac, $userId, $foldersRelations);
        if (!is_null($folderRelationToBreak)) {
            $this->foldersRelationsTable->moveItemFrom(
                $folderRelationToBreak->foreign_id,
                [$folderRelationToBreak->folder_parent_id],
                FoldersRelation::ROOT
            );
        }

        return $folderRelationToBreak;
    }

    /**
     * Identify the folder relation to break in order to solve an SCC.
     *
     * The list of folders relations involved in the SCC will be sorted and the folder relation to break will
     * follow the rules (On top the folder relation to break).
     * 1. The folder relation presence in the operator tree. Priority to the non operator view.
     * 2. The folder relation usage. Priority to the less used.
     * 3. The folder relation presence in the target user tree. Priority to the non target user view.
     * 4. The folder relation age. Priority to the newest folder relation.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The user tree that is at the origin at the conflict. Most of the time it's the modified
     * tree.
     * @param array<\Cipherguard\Folders\Model\Entity\FoldersRelation> $foldersRelations The list of folders relations involved in the conflict.
     * @return \Cipherguard\Folders\Model\Entity\FoldersRelation|null
     */
    private function identifyFolderRelationToBreak(
        UserAccessControl $uac,
        string $userId,
        array $foldersRelations
    ) {
        if (empty($foldersRelations)) {
            return null;
        }

        $foldersRelationsToSort = $foldersRelations;
        $this->foldersRelationsSortService->sort($foldersRelationsToSort, $uac, $userId);

        return $foldersRelationsToSort[count($foldersRelationsToSort) - 1];
    }

    /**
     * Repair an SCC related to a personal folder.
     *
     * @param array<\Cipherguard\Folders\Model\Entity\FoldersRelation> $foldersRelations The list of folders relations involved in the conflict.
     * @return \Cipherguard\Folders\Model\Entity\FoldersRelation|null Return the folder relation that was broken to repair the SCC.
     */
    public function repairPersonal(array $foldersRelations)
    {
        $folderRelationToBreak = $this->identifyPersonalFolderRelationToBreak($foldersRelations);
        if ($folderRelationToBreak) {
            $this->foldersRelationsTable->moveItemFrom(
                $folderRelationToBreak->foreign_id,
                [$folderRelationToBreak->folder_parent_id],
                FoldersRelation::ROOT
            );
        }

        return $folderRelationToBreak;
    }

    /**
     * Identify which personal folder relation should be broken to solve the cycle.
     * Return the first personal relation found in the list of folders relations involved in the cycle.
     *
     * @param array $foldersRelations The list of folders relations involved in a cycle
     * @return \Cipherguard\Folders\Model\Entity\FoldersRelation|null
     */
    private function identifyPersonalFolderRelationToBreak(array $foldersRelations)
    {
        foreach ($foldersRelations as $folderRelation) {
            $isPersonal = $this->foldersRelationsTable->isItemPersonal($folderRelation['folder_parent_id']);
            if ($isPersonal) {
                return $folderRelation;
            }
        }

        return null;
    }
}
