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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cipherguard\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Cipherguard\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class UsersRegisterNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',];

    public function testUserRegisterNotificationDisabled(): void
    {
        $this->setSelfRegistrationSettingsData();
        $this->setEmailNotificationSetting('send.user.create', false);

        $this->postJson('/users/register.json', [
            'username' => 'aurore@cipherguard.khulnasoft.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailWithRecipientIsInNotQueue('aurore@cipherguard.khulnasoft.com');
    }

    public function testUserRegisterNotificationSuccess(): void
    {
        $this->setSelfRegistrationSettingsData();
        $this->setEmailNotificationSetting('send.user.create', true);

        $this->postJson('/users/register.json', [
            'username' => 'aurore@cipherguard.khulnasoft.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailInBatchContains('You just opened an account', 'aurore@cipherguard.khulnasoft.com');
    }
}
