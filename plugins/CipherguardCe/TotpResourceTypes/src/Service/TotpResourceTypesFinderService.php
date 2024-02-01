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
 * @since         4.0.0
 */
namespace Cipherguard\TotpResourceTypes\Service;

use Cake\ORM\Query;
use Cipherguard\ResourceTypes\Model\Table\ResourceTypesTable;
use Cipherguard\ResourceTypes\Service\ResourceTypesFinderService;

class TotpResourceTypesFinderService extends ResourceTypesFinderService
{
    /**
     * Returns all the available resource types (mainly including TOTP related).
     *
     * @return \Cake\ORM\Query
     */
    public function find(): Query
    {
        return $this->resourceTypesTable
            ->find()
            ->formatResults(ResourceTypesTable::resultFormatter());
    }
}
