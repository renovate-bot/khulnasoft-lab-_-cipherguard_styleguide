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
namespace Cipherguard\SmtpSettings\Event;

use App\Mailer\Transport\SmtpTransport;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;

class SmtpTransportSendTestEmailEventListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT => [
                'callable' => 'stopPropagation',
                'priority' => 1,
            ],
            SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT => [
                'callable' => 'stopPropagation',
                'priority' => 1,
            ],
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function stopPropagation(EventInterface $event): void
    {
        $event->stopPropagation();
    }
}
