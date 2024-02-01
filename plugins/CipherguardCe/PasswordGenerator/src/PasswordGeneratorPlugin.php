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
namespace Cipherguard\PasswordGenerator;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;

class PasswordGeneratorPlugin extends BasePlugin
{
    public const DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY = 'cipherguard.plugins.passwordGenerator.defaultPasswordGenerator';

    public const DEFAULT_PASSWORD_GENERATOR_ENV_KEY = 'CIPHERGURD_PLUGINS_PASSWORD_GENERATOR_DEFAULT_GENERATOR';

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        Configure::load('Cipherguard/PasswordGenerator.config', 'default', true);
    }
}
