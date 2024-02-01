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
 * @since         3.11.0
 */
namespace App;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cipherguard\WebInstaller\Middleware\WebInstallerMiddleware;

/**
 * A solution bootstrapper to:
 * - Load plugins required by a given solution
 * - Load/overwrite configs of a given solution
 */
class BaseSolutionBootstrapper
{
    use FeaturePluginAwareTrait;

    /**
     * Loads all the plugins relative to the solution
     *
     * @param \App\Application $app Application
     * @return void
     */
    public function addFeaturePlugins(Application $app): void
    {
        if (Configure::read('debug') && Configure::read('cipherguard.selenium.active')) {
            $app->addPlugin('CipherguardSeleniumApi', ['bootstrap' => true, 'routes' => true]);
            $app->addPlugin('CipherguardTestData', ['bootstrap' => true, 'routes' => false]);
        }

        $this->addFeaturePluginIfEnabled($app, 'JwtAuthentication');

        // Add webinstaller plugin if not configured.
        if (!WebInstallerMiddleware::isConfigured()) {
            $app->addPlugin('Cipherguard/WebInstaller', ['bootstrap' => true, 'routes' => true]);

            return;
        }

        // Add Common plugins.
        $this->addFeaturePluginIfEnabled($app, 'Rbacs');
        $app->addPlugin('Cipherguard/AccountSettings', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Cipherguard/Import', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Cipherguard/InFormIntegration', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Cipherguard/Locale', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Cipherguard/Export', ['bootstrap' => true, 'routes' => false]);
        $this->addFeaturePluginIfEnabled($app, 'ResourceTypes');
        $this->addFeaturePluginIfEnabled($app, 'TotpResourceTypes', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Cipherguard/RememberMe', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Cipherguard/EmailNotificationSettings', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Cipherguard/EmailDigest', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Cipherguard/Reports', ['bootstrap' => true, 'routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'Mobile');
        $this->addFeaturePluginIfEnabled($app, 'SelfRegistration');
        $app->addPlugin('Cipherguard/PasswordGenerator', ['routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'SmtpSettings');

        $this->addFeaturePluginIfEnabled(
            $app,
            'MultiFactorAuthentication',
            ['bootstrap' => true, 'routes' => true],
            true
        );

        $logEnabled = Configure::read('cipherguard.plugins.log.enabled');
        if (!isset($logEnabled) || $logEnabled) {
            $app->addPlugin('Cipherguard/Log', ['bootstrap' => true, 'routes' => false]);
        }

        $folderEnabled = Configure::read('cipherguard.plugins.folders.enabled');
        if (!isset($folderEnabled) || $folderEnabled) {
            $app->addPlugin('Cipherguard/Folders', ['bootstrap' => true, 'routes' => true]);
        }

        $this->addFeaturePluginIfEnabled($app, 'PasswordPolicies');
    }

    /**
     * Adds a plugin to the application according to the feature flag configuration.
     *
     * In order to enable a plugin, you may set is as enabled in the config/default.php file
     * under the cipherguard.plugins config namespace key.
     *
     * The name of the plugin is without the "Cipherguard/" prefix, either upper- or lower-case first.
     * By default, a feature (aka plugin) is disabled. You may force the enabling as parameter by passing a boolean
     * or a callable returning a boolean.
     *
     * @param \Cake\Core\PluginApplicationInterface $app Application
     * @param string $name Name of the plugin (without the "Cipherguard/" prefix)
     * @param array $config Plugin loading config, will be merged with ['bootstrap' => true, 'routes' => true]
     * @param bool|callable $isEnabledByDefault Boolean or callback indicating if the plugin should be loaded by default, if not priorly enabled in configurations. False by default.
     * @return self
     * @throws \TypeError if the callable $isEnabledByDefault does not return a boolean.
     */
    final public function addFeaturePluginIfEnabled(
        PluginApplicationInterface $app,
        string $name,
        array $config = [],
        $isEnabledByDefault = false
    ): self {
        $config = array_merge(['bootstrap' => true, 'routes' => true], $config);

        if (is_callable($isEnabledByDefault)) {
            $isEnabledByDefault = $isEnabledByDefault();
        }

        if ($this->isFeaturePluginEnabled($name, $isEnabledByDefault)) {
            $fullPluginName = 'Cipherguard/' . ucfirst($name);
            $app->addPlugin($fullPluginName, $config);
        }

        return $this;
    }
}
