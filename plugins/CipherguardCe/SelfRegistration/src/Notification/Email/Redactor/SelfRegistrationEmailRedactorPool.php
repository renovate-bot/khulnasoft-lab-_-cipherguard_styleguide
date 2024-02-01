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
 * @since         3.10.0
 */

namespace Cipherguard\SelfRegistration\Notification\Email\Redactor;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use Cipherguard\SelfRegistration\Notification\Email\Redactor\Settings\SelfRegistrationSettingsAdminEmailRedactor;
use Cipherguard\SelfRegistration\Notification\Email\Redactor\User\SelfRegistrationAdminEmailRedactor;

class SelfRegistrationEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * @inheritDoc
     */
    public function getSubscribedRedactors(): array
    {
        $redactors = [];

        // This setting cannot be deactivated
        $redactors[] = new SelfRegistrationSettingsAdminEmailRedactor();

        if ($this->isRedactorEnabled('send.admin.user.register.complete')) {
            $redactors[] = new SelfRegistrationAdminEmailRedactor();
        }

        return $redactors;
    }
}
