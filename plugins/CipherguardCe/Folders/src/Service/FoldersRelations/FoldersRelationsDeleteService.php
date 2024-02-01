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

use Cake\ORM\TableRegistry;

class FoldersRelationsDeleteService
{
    /**
     * @var \Cipherguard\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Cipherguard/Folders.FoldersRelations');
    }

    /**
     * Delete a folder relation
     *
     * @param string $userId The target user
     * @param string $foreignId The target item
     * @return void
     * @throws \Exception
     */
    public function delete(string $userId, string $foreignId)
    {
        $this->foldersRelationsTable->getConnection()->transactional(function () use ($userId, $foreignId) {
            $this->foldersRelationsTable->deleteAll([
                'foreign_id' => $foreignId,
                'user_id' => $userId,
            ]);
        });
    }
}
