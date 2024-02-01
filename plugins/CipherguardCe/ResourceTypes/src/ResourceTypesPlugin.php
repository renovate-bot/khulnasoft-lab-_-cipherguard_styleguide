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
 * @since         3.8.0
 */
namespace Cipherguard\ResourceTypes;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cipherguard\ResourceTypes\Service\ResourceTypesFinderInterface;
use Cipherguard\ResourceTypes\Service\ResourceTypesFinderService;

class ResourceTypesPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(ResourceTypesFinderInterface::class)
            ->setConcrete(ResourceTypesFinderService::class);
    }
}
