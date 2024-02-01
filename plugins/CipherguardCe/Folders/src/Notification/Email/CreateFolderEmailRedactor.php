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

use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Cipherguard\Folders\Model\Entity\Folder;
use Cipherguard\Folders\Service\Folders\FoldersCreateService;
use Cipherguard\Locale\Service\LocaleService;

class CreateFolderEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     * @see templates/email/html/LU/folder_delete.php
     */
    public const TEMPLATE = 'Cipherguard/Folders.LU/folder_create';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * Email redactor constructor.
     */
    public function __construct()
    {
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            FoldersCreateService::FOLDERS_CREATE_FOLDER_EVENT,
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $folder = $event->getData('folder');
        if (!$folder) {
            throw new InvalidArgumentException('`folder` is missing from event data.');
        }

        $uac = $event->getData('uac');
        if (!$uac) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        $email = $this->createEmail($folder, $uac);

        return $emailCollection->addEmail($email);
    }

    /**
     * @param \Cipherguard\Folders\Model\Entity\Folder $folder Folder entity
     * @param \App\Utility\UserAccessControl $uac UserAccessControl
     * @return \App\Notification\Email\Email
     */
    private function createEmail(Folder $folder, UserAccessControl $uac)
    {
        $recipient = $this->usersTable->findFirstForEmail($uac->getId());
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($folder) {
                return __('You added the folder {0}', $folder->name);
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'user' => $recipient,
                    'folder' => $folder,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }
}
