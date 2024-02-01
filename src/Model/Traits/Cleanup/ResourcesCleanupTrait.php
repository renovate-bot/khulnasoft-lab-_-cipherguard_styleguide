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
 * @since         2.0.0
 */
namespace App\Model\Traits\Cleanup;

trait ResourcesCleanupTrait
{
    /**
     * Delete all records where associated users are soft deleted
     *
     * @param bool|null $dryRun false
     * @return int number of affected records
     */
    public function cleanupSoftDeletedResources(?bool $dryRun = false): int
    {
        return $this->cleanupSoftDeleted('Resources', $dryRun);
    }

    /**
     * Delete all records where associated users are deleted
     *
     * @param bool|null $dryRun false
     * @return int number of affected records
     */
    public function cleanupHardDeletedResources(?bool $dryRun = false): int
    {
        return $this->cleanupHardDeleted('Resources', $dryRun);
    }
}
