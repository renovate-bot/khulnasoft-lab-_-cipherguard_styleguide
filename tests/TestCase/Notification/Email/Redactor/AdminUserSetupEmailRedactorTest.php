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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Notification\Email\Redactor;

use App\Controller\Setup\SetupCompleteController;
use App\Notification\Email\Redactor\AdminUserSetupCompleteEmailRedactor;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

class AdminUserSetupEmailRedactorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Configure::write('cipherguard.plugins.log.enabled', true);
    }

    public function testThatRedactorIsSubscribedToEvents()
    {
        $this->assertSame(
            [
                SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME,
            ],
            (new AdminUserSetupCompleteEmailRedactor())->getSubscribedEvents()
        );
    }
}
