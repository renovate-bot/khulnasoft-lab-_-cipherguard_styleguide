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
 * @since         4.1.0
 */

namespace Cipherguard\EmailNotificationSettings\Test\TestCase\Utility;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cipherguard\EmailNotificationSettings\Test\Factory\EmailNotificationSettingFactory;
use Cipherguard\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Cipherguard\Folders\FoldersPlugin;
use Cipherguard\Folders\Notification\NotificationSettings\FolderNotificationSettingsDefinition;

/**
 * @covers \Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettings
 */
class EmailNotificationSettingsTest extends AppTestCase
{
    use EmailNotificationSettingsTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadNotificationSettings();
        // Set default config
        Configure::write('cipherguard.email', self::getDefaultEmailNotificationConfig());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->unloadNotificationSettings();

        parent::tearDown();
    }

    public function testEmailNotificationSettings_Get_DefaultConfigWithFolders(): void
    {
        // Enable folders email notifications
        $this->enableFeaturePlugin(FoldersPlugin::class);
        EventManager::instance()->on(new FolderNotificationSettingsDefinition());

        $result = EmailNotificationSettings::get();

        $defaultEmailNotifSettings = self::getDefaultEmailNotificationConfig();
        $this->assertSame($defaultEmailNotifSettings['purify'], $result['purify']);
        $this->assertSame($defaultEmailNotifSettings['show'], $result['show']);
        $this->assertSame($defaultEmailNotifSettings['send']['comment'], $result['send']['comment']);
        $this->assertSame($defaultEmailNotifSettings['send']['password'], $result['send']['password']);
        $this->assertSame($defaultEmailNotifSettings['send']['user'], $result['send']['user']);
        $this->assertSame($defaultEmailNotifSettings['send']['group'], $result['send']['group']);
        $this->assertSame($defaultEmailNotifSettings['send']['folder'], $result['send']['folder']);
        $this->assertSame([
            'database' => false,
            'file' => false,
        ], $result['sources']);
    }

    public function testEmailNotificationSettings_Get_OverriddenConfig(): void
    {
        // Change config
        Configure::write('cipherguard.email.send.password.create', true);

        $result = EmailNotificationSettings::get();

        $defaultEmailNotifSettings = self::getDefaultEmailNotificationConfig();
        $this->assertSame($defaultEmailNotifSettings['purify'], $result['purify']);
        $this->assertSame($defaultEmailNotifSettings['show'], $result['show']);
        $this->assertSame($defaultEmailNotifSettings['send']['comment'], $result['send']['comment']);
        $this->assertSame($defaultEmailNotifSettings['send']['user'], $result['send']['user']);
        $this->assertSame($defaultEmailNotifSettings['send']['group'], $result['send']['group']);
        // Make sure if folder plugin is not loaded then key is not returned
        $this->assertArrayNotHasKey('folder', $result['send']);
        // Assert override config values
        $this->assertTrue($result['send']['password']['create']);
        $this->assertSame([
            'database' => false,
            'file' => true, // `true` indicates there is config file values is overridden
        ], $result['sources']);
    }

    public function testEmailNotificationSettings_Get_DefaultConfigWithDatabase(): void
    {
        $dbEmailNotifSettings = EmailNotificationSettingFactory::make()->persist();
        $dbEmailNotifSettings = json_decode($dbEmailNotifSettings->value, true);

        $result = EmailNotificationSettings::get();

        $this->assertSame([
            'database' => true,
            'file' => false,
        ], $result['sources']);
        // assert db config values
        $this->assertSame($dbEmailNotifSettings['purify'], $result['purify']);
        $this->assertSame($dbEmailNotifSettings['show'], $result['show']);
        $this->assertSame($dbEmailNotifSettings['send']['comment'], $result['send']['comment']);
        $this->assertSame($dbEmailNotifSettings['send']['user'], $result['send']['user']);
        $this->assertSame($dbEmailNotifSettings['send']['group'], $result['send']['group']);
        $this->assertSame($dbEmailNotifSettings['send']['password'], $result['send']['password']);
        // Make sure if folder plugin is not loaded then key is not returned
        $this->assertArrayNotHasKey('folder', $result['send']);
    }

    public function testEmailNotificationSettings_Get_OverriddenConfigWithDatabase(): void
    {
        $dbEmailNotifSettings = EmailNotificationSettingFactory::make()->persist();
        $dbEmailNotifSettings = json_decode($dbEmailNotifSettings->value, true);
        // Change config
        Configure::write('cipherguard.email.send.password.create', true);

        $result = EmailNotificationSettings::get();

        $this->assertSame([
            'database' => true,
            'file' => true,
        ], $result['sources']);
        // assert db config values
        $this->assertSame($dbEmailNotifSettings['purify'], $result['purify']);
        $this->assertSame($dbEmailNotifSettings['show'], $result['show']);
        $this->assertSame($dbEmailNotifSettings['send']['comment'], $result['send']['comment']);
        $this->assertSame($dbEmailNotifSettings['send']['user'], $result['send']['user']);
        $this->assertSame($dbEmailNotifSettings['send']['group'], $result['send']['group']);
        $this->assertSame($dbEmailNotifSettings['send']['password'], $result['send']['password']);
        // Make sure if folder plugin is not loaded then key is not returned
        $this->assertArrayNotHasKey('folder', $result['send']);
    }
}
