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
namespace Cipherguard\TotpResourceTypes\Test\Scenario;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Cipherguard\ResourceTypes\Model\Definition\SlugDefinition;
use Cipherguard\ResourceTypes\Model\Entity\ResourceType;
use Cipherguard\ResourceTypes\Test\Factory\ResourceTypeFactory;

class TotpResourceTypesScenario implements FixtureScenarioInterface
{
    /**
     * @inheritDoc
     */
    public function load(...$args)
    {
        return ResourceTypeFactory::make([
            ['slug' => ResourceType::SLUG_STANDALONE_TOTP, 'definition' => SlugDefinition::totp()],
            [
                'slug' => ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP,
                'definition' => SlugDefinition::passwordDescriptionTotp(),
            ],
        ])->persist();
    }
}
