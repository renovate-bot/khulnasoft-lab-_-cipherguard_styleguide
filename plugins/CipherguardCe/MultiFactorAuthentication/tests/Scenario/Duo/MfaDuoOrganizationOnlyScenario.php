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
 * @since         3.3.0
 */
namespace Cipherguard\MultiFactorAuthentication\Test\Scenario\Duo;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Cipherguard\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * MfaDuoOrganizationOnlyScenario
 */
class MfaDuoOrganizationOnlyScenario implements FixtureScenarioInterface
{
    public function load(...$args): array
    {
        $isSupported = $args[0] ?? true;
        $apiHostName = $args[1] ?? null;
        $orgSetting = MfaOrganizationSettingFactory::make()
            ->setProviders(MfaSettings::PROVIDER_DUO, $isSupported)
            ->duo($isSupported, $apiHostName)
            ->persist();

        return [$orgSetting];
    }
}
