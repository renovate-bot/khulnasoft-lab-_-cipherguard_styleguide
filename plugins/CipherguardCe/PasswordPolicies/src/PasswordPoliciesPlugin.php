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
 * @since         4.2.0
 */
namespace Cipherguard\PasswordPolicies;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cipherguard\PasswordPolicies\Service\PasswordPoliciesGetSettingsInterface;
use Cipherguard\PasswordPolicies\Service\PasswordPoliciesGetSettingsService;

class PasswordPoliciesPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    public const DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY = 'cipherguard.plugins.passwordPolicies.defaultPasswordGenerator';

    public const DEFAULT_PASSWORD_GENERATOR_ENV_KEY =
        'CIPHERGURD_PLUGINS_PASSWORD_POLICIES_DEFAULT_PASSWORD_GENERATOR_TYPE';

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(PasswordPoliciesGetSettingsInterface::class)
            ->setConcrete(PasswordPoliciesGetSettingsService::class);
    }
}
