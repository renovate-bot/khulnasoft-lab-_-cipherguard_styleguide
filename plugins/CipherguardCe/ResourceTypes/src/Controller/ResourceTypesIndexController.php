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
 * @since         3.0.0
 */

namespace Cipherguard\ResourceTypes\Controller;

use App\Controller\AppController;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cipherguard\ResourceTypes\Service\ResourceTypesFinderInterface;

class ResourceTypesIndexController extends AppController
{
    use FeaturePluginAwareTrait;

    /**
     * Resource Types Index action
     *
     * @param \Cipherguard\ResourceTypes\Service\ResourceTypesFinderInterface $resourceTypesFinder Resource types finder service.
     * @throws \Cake\Http\Exception\NotFoundException if plugin is disabled by admin
     * @return void
     */
    public function index(ResourceTypesFinderInterface $resourceTypesFinder)
    {
        $this->assertJson();
        $resourceTypes = $resourceTypesFinder->find();

        $this->success(__('The operation was successful.'), $resourceTypes->all());
    }
}
