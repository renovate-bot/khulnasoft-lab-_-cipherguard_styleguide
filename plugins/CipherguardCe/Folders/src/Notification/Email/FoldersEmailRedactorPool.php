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

namespace Cipherguard\Folders\Notification\Email;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;

class FoldersEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return a list of subscribed redactors
     *
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors(): array
    {
        $redactors = [];

        if ($this->isRedactorEnabled('send.folder.create')) {
            $redactors[] = new CreateFolderEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.folder.update')) {
            $redactors[] = new UpdateFolderEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.folder.delete')) {
            $redactors[] = new DeleteFolderEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.folder.share')) {
            $redactors[] = new ShareFolderEmailRedactor();
        }

        return $redactors;
    }
}
