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
namespace App\Test\TestCase;

use App\BaseSolutionBootstrapper;
use App\Test\Lib\SolutionBootstrapperTestCase;
use Cake\Core\Configure;
use Cake\Core\PluginCollection;
use Cake\Http\Exception\InternalErrorException;
use Cipherguard\JwtAuthentication\JwtAuthenticationPlugin;
use Cipherguard\Mobile\MobilePlugin;
use Cipherguard\SelfRegistration\SelfRegistrationPlugin;
use Cipherguard\SmtpSettings\SmtpSettingsPlugin;

/**
 * BasePluginAdderTest class
 *
 * @covers \App\BaseSolutionBootstrapper
 * @group SolutionBootstrapper
 */
class BaseSolutionBootstrapperTest extends SolutionBootstrapperTestCase
{
    public const EXPECTED_CE_PLUGINS = [
        'Cipherguard/JwtAuthentication',
        'Cipherguard/Rbacs',
        'Cipherguard/AccountSettings',
        'Cipherguard/Import',
        'Cipherguard/InFormIntegration',
        'Cipherguard/Locale',
        'Cipherguard/Export',
        'Cipherguard/ResourceTypes',
        'Cipherguard/TotpResourceTypes',
        'Cipherguard/RememberMe',
        'Cipherguard/EmailNotificationSettings',
        'Cipherguard/EmailDigest',
        'Cipherguard/Reports',
        'Cipherguard/Mobile',
        'Cipherguard/SelfRegistration',
        'Cipherguard/PasswordGenerator',
        'Cipherguard/SmtpSettings',
        'Cipherguard/MultiFactorAuthentication',
        'Cipherguard/Log',
        'Cipherguard/Folders',
        'Cipherguard/PasswordPolicies',
    ];

    public function testBaseSolutionBootstrapper_Application_Bootstrap(): void
    {
        $plugins = $this->arrangeAndGetPlugins();
        $this->assertPluginList($plugins, $this->getExpectedPlugins());
    }

    public function testBaseSolutionBootstrapper_Application_Bootstrap_WebInstaller_Required(): void
    {
        Configure::write('cipherguard.webInstaller.configured', false);
        $expectedPluginList = [
            'Migrations',
            'Authentication',
            'EmailQueue',
            'BryanCrowe/ApiPagination',
            'CipherguardSeleniumApi',
            'CipherguardTestData',
            'Cipherguard/JwtAuthentication',
            'Cipherguard/WebInstaller',
            'Bake',
            'CakephpFixtureFactories',
            'Cake/TwigView',
        ];
        $plugins = $this->arrangeAndGetPlugins();
        $this->assertPluginList($plugins, $expectedPluginList);
    }

    protected function getExpectedPlugins(bool $withWebInstaller = false): array
    {
        return array_merge(
            [
            'Migrations',
            'Authentication',
            'EmailQueue',
            'BryanCrowe/ApiPagination',
            'CipherguardSeleniumApi',
            'CipherguardTestData',
            ],
            self::EXPECTED_CE_PLUGINS,
            [
            'Bake',
            'CakephpFixtureFactories',
            'Cake/TwigView',
            ]
        );
    }

    protected function arrangeAndGetPlugins(): PluginCollection
    {
        $this->enableFeaturePlugin(MobilePlugin::class);
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
        // These two plugins are enabled by default if not defined
        Configure::delete('cipherguard.plugins.multiFactorAuthentication.enabled');
        Configure::delete('cipherguard.plugins.log.enabled');

        $this->app->setSolutionBootstrapper(new BaseSolutionBootstrapper());
        $this->app->bootstrap();
        $this->app->pluginBootstrap();

        return $this->app->getPlugins();
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin()
    {
        $this->enableFeaturePlugin(MobilePlugin::class);

        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'Mobile');
        $this->assertTrue($this->app->getPlugins()->has('Cipherguard/Mobile'));

        $plugin = $this->app->getPlugins()->get('Cipherguard/Mobile');
        $this->assertSame('Cipherguard/Mobile', $this->app->getPlugins()->get('Cipherguard/Mobile')->getName());
        $this->assertTrue($plugin->isEnabled('bootstrap'));
        $this->assertTrue($plugin->isEnabled('routes'));
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_With_Config()
    {
        $this->enableFeaturePlugin(MobilePlugin::class);

        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'Mobile', ['routes' => false, 'bootstrap' => false]);
        $this->assertTrue($this->app->getPlugins()->has('Cipherguard/Mobile'));

        $plugin = $this->app->getPlugins()->get('Cipherguard/Mobile');
        $this->assertSame('Cipherguard/Mobile', $this->app->getPlugins()->get('Cipherguard/Mobile')->getName());
        $this->assertFalse($plugin->isEnabled('bootstrap'));
        $this->assertFalse($plugin->isEnabled('routes'));
    }

    public function dataFortestBaseSolutionBootstrapper_AddFeaturePlugin_On_Disabled_Plugin(): array
    {
        return [
            [],
            [false],
            [function () {

                return false;
            }],
        ];
    }

    /**
     * @dataProvider dataFortestBaseSolutionBootstrapper_AddFeaturePlugin_On_Disabled_Plugin
     * @param callable|bool $isEnabledByDefault
     */
    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Disabled_Plugin($isEnabledByDefault = null)
    {
        // If not defined, the feature flag is considered as false by default.
        Configure::delete('cipherguard.plugins.mobile.enabled');
        $this->assertFalse($this->isFeaturePluginEnabled(MobilePlugin::class));

        if ($isEnabledByDefault === null) {
            $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'mobile');
        } else {
            $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'mobile', [], $isEnabledByDefault);
        }
        $this->assertFalse($this->app->getPlugins()->has('Cipherguard/Mobile'));
    }

    public function data_for_testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_By_Default()
    {
        return [
            [true],
            [function () {

                return true;
            }],
        ];
    }

    /**
     * @dataProvider data_for_testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_By_Default
     * @param callable|bool $isEnabledByDefault
     */
    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_By_Default($isEnabledByDefault)
    {
        // If not defined, the feature flag is considered as false by default.
        Configure::delete('cipherguard.plugins.mobile.enabled');
        $this->assertFalse($this->isFeaturePluginEnabled(MobilePlugin::class));

        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'mobile', [], $isEnabledByDefault);
        $this->assertTrue($this->app->getPlugins()->has('Cipherguard/Mobile'));
        $this->assertSame('Cipherguard/Mobile', $this->app->getPlugins()->get('Cipherguard/Mobile')->getName());
        $plugin = $this->app->getPlugins()->get('Cipherguard/Mobile');
        $this->assertTrue($plugin->isEnabled('bootstrap'));
        $this->assertTrue($plugin->isEnabled('routes'));
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Non_Existing_Plugin()
    {
        $this->expectNotToPerformAssertions();
        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'foo');
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Callable_Not_Returning_A_Boolean()
    {
        // Callable not returning a boolean makes the code crash.
        $callable = function () {
            return 'Foo';
        };

        $this->expectException(\TypeError::class);
        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'Bar', [], $callable);
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Class_Not_A_Plugin()
    {
        $className = self::class;
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage("The class {$className} should implement PluginInterface::class.");
        $this->app->getSolutionBootstrapper()->isFeaturePluginEnabled($className);
    }
}
