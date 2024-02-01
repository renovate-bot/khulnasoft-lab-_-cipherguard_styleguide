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
 * @since         3.10.0
 */

namespace Cipherguard\Folders\Model\Collection;

use Cake\Collection\Collection;
use Cipherguard\Folders\Model\Dto\FolderRelationDto;
use Cipherguard\Folders\Model\Entity\FoldersRelation;

class FolderRelationDtoCollection extends Collection
{
    /**
     * Callback to map items and return their foreign id.
     *
     * @param \Cipherguard\Folders\Model\Dto\FolderRelationDto $folderRelationDto The folder relation to map.
     * @return string|null
     */
    public static function mapForeignId(FolderRelationDto $folderRelationDto): ?string
    {
        return $folderRelationDto->foreignId;
    }

    /**
     * Callback to filter by items relative to folders.
     *
     * @param \Cipherguard\Folders\Model\Dto\FolderRelationDto $folderRelationDto The folder relation to filter.
     * @return bool
     */
    public static function filterByFolder(FolderRelationDto $folderRelationDto): bool
    {
        return $folderRelationDto->foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER;
    }

    /**
     * Check if the collection contains an item relative to a folder.
     *
     * @return bool
     */
    public function containsFolder(): bool
    {
        return $this->some([self::class, 'filterByFolder']);
    }

    /**
     * Get the identifiers of the folders present in the list.
     *
     * @return array
     */
    public function getFoldersIds(): array
    {
        return $this->filter([FolderRelationDtoCollection::class, 'filterByFolder'])
            ->map([FolderRelationDtoCollection::class, 'mapForeignId'])
            ->toArray();
    }
}
