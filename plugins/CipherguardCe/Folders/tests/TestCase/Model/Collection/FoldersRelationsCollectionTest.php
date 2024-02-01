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
 * @since         4.1.0
 */

namespace Cipherguard\Folders\Test\TestCase\Model\Collection;

use App\Utility\UuidFactory;
use Cipherguard\Folders\Model\Collection\FolderRelationDtoCollection;
use Cipherguard\Folders\Model\Dto\FolderRelationDto;
use Cipherguard\Folders\Model\Entity\FoldersRelation;
use Cipherguard\Folders\Test\Lib\FoldersTestCase;

class FoldersRelationsCollectionTest extends FoldersTestCase
{
    public function testFoldersRelationsCollection_getFoldersIds(): void
    {
        $folderAId = UuidFactory::uuid();
        $folderBId = UuidFactory::uuid();
        $resourceAId = UuidFactory::uuid();
        $items = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderAId),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceAId),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderBId),
        ];

        $collection = new FolderRelationDtoCollection($items);
        $foreignIds = $collection->getFoldersIds();
        $this->assertContains($folderAId, $foreignIds);
        $this->assertContains($folderBId, $foreignIds);
    }
}
