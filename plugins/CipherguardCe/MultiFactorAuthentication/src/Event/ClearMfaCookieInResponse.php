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
 * @since         3.3.0
 */
namespace Cipherguard\MultiFactorAuthentication\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cipherguard\MultiFactorAuthentication\Service\ClearMfaCookieInResponseService;

class ClearMfaCookieInResponse implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.shutdown' => 'clearInvalidMfaCookieInResponse',
        ];
    }

    /**
     * If a user is authenticating with a non valid MFA cookie,
     * the latter shall be removed from the request.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function clearInvalidMfaCookieInResponse(EventInterface $event): void
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();
        (new ClearMfaCookieInResponseService($controller))->clearMfaCookie();
    }
}
