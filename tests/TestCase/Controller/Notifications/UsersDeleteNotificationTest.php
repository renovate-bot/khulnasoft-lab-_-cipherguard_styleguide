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

use App\Notification\Email\Redactor\User\AdminDeleteEmailRedactor;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;

class UsersDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Favorites', 'app.Base/Secrets',
        'app.Base/GroupsUsers', 'app.Base/Permissions',
    ];

    public function testUsersDeleteNotificationSuccess(): void
    {
        $francesId = UuidFactory::uuid('user.id.ursula');
        Configure::write(AdminDeleteEmailRedactor::CONFIG_KEY_EMAIL_ENABLED, false);

        $this->authenticateAs('admin');
        $this->deleteJson('/users/' . $francesId . '.json');

        $this->assertSuccess();
        $this->assertEmailInBatchContains('deleted the user Ursula', 'ping@cipherguard.khulnasoft.com');
        $this->assertEmailInBatchContains('Human resource', 'ping@cipherguard.khulnasoft.com');
        $this->assertEmailInBatchContains('IT support', 'ping@cipherguard.khulnasoft.com');

        $this->assertEmailInBatchContains('deleted the user Ursula', 'thelma@cipherguard.khulnasoft.com');
        $this->assertEmailInBatchContains('Human resource', 'thelma@cipherguard.khulnasoft.com');
        $this->assertEmailInBatchNotContains('IT support', 'thelma@cipherguard.khulnasoft.com');

        $this->assertEmailWithRecipientIsInNotQueue('wang@cipherguard.khulnasoft.com');

        // Two mails should be in the queue
        $this->assertEmailQueueCount(2);
    }
}
