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
 * @since         4.3.0
 */

namespace App\Service\Setup;

interface RecoverStartInfoServiceInterface
{
    /**
     * Retrieves user and token information for the recover start controller
     *
     * @param string $userId User uuid
     * @param string $token Register token
     * @param array|null $data Result data from previous service
     * @return array data to pass to the view
     */
    public function getInfo(string $userId, string $token, ?array $data): array;
}
