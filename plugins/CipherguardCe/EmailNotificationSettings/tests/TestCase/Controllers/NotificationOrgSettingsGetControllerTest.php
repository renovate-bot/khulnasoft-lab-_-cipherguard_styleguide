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
 * @since         2.10.0
 */
namespace Cipherguard\EmailNotificationSettings\Test\TestCase\Controllers;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cipherguard\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettings;

class NotificationOrgSettingsGetControllerTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/Users',
        'app.Base/Roles',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->loadNotificationSettings();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->unloadNotificationSettings();
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerNotLoggedIn()
    {
        $this->getJson('/settings/emails/notifications.json?api-version=v2');
        $this->assertAuthenticationError();
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerNotJson()
    {
        $this->logInAsAdmin();
        $this->get('/settings/emails/notifications');
        $this->assertResponseError('This is not a valid Ajax/Json request.');
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerNonAdminNotAllowed()
    {
        $this->logInAsUser();
        $this->getJson('/settings/emails/notifications.json?api-version=v2');
        $this->assertResponseError('You are not allowed to access this location.');
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerDefaultSuccess()
    {
        $this->logInAsAdmin();
        $this->getJson('/settings/emails/notifications.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertFalse($this->_responseJsonBody->sources_database);
        $this->assertFalse($this->_responseJsonBody->sources_file);
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerSuccessDBOverride()
    {
        $cases = [
            'send_comment_add' => false,
            'send_password_create' => true,
            'send_password_share' => false,
        ];

        // Mock DB settings
        $this->setEmailNotificationSettings($cases);

        $this->logInAsAdmin();
        $this->getJson('/settings/emails/notifications.json?api-version=v2');
        $this->assertTrue($this->_responseJsonBody->sources_database);
        $this->assertFalse($this->_responseJsonBody->sources_file);

        foreach ($cases as $case => $expected) {
            $this->assertEquals($expected, $this->_responseJsonBody->{$case});
        }
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerSuccessFileOverride()
    {
        $cases = [
            'send_comment_add' => false,
            'send_password_create' => true,
            'send_password_share' => false,
        ];

        // Mock File settings
        foreach ($cases as $case => $value) {
            $configKey = EmailNotificationSettings::underscoreToDottedFormat($case);
            Configure::write('cipherguard.email.' . $configKey, $value);
        }

        $this->logInAsAdmin();
        $this->getJson('/settings/emails/notifications.json?api-version=v2');
        $this->assertFalse($this->_responseJsonBody->sources_database);
        $this->assertTrue($this->_responseJsonBody->sources_file);

        foreach ($cases as $case => $expected) {
            $this->assertEquals($expected, $this->_responseJsonBody->{$case});
        }
    }

    /**
     * @group notificationSetting
     * @group notificationOrgSettings
     * @group notificationOrgSettingsGet
     */
    public function testNotificationOrgSettingsGetControllerSuccessBothOverride()
    {
        $cases = [
            'send_comment_add' => false,
            'send_password_create' => true,
            'send_password_share' => false,
        ];

        // Mock DB settings
        foreach ($cases as $case => $value) {
            $configKey = EmailNotificationSettings::underscoreToDottedFormat($case);
            Configure::write('cipherguard.email.' . $configKey, $value);
        }

        // Override with DB settings
        $this->setEmailNotificationSettings($cases);

        $this->logInAsAdmin();
        $this->getJson('/settings/emails/notifications.json?api-version=v2');
        $this->assertTrue($this->_responseJsonBody->sources_database);
        $this->assertTrue($this->_responseJsonBody->sources_file);

        foreach ($cases as $case => $expected) {
            $this->assertEquals($expected, $this->_responseJsonBody->{$case});
        }
    }
}
