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
namespace Cipherguard\JwtAuthentication\Event;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Middleware\ContainerAwareMiddlewareTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cipherguard\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Cipherguard\JwtAuthentication\Authenticator\JwtSessionIdentificationService;

class SetSessionIdentifierOnLogin implements EventListenerInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            GpgJwtAuthenticator::JWT_AUTHENTICATION_AFTER_IDENTIFY => 'setSessionIdentifierOnLogin',
        ];
    }

    /**
     * When a user logs in, set the session ID as the access token generated.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function setSessionIdentifierOnLogin(EventInterface $event): void
    {
        /** @var \Cipherguard\JwtAuthentication\Authenticator\GpgJwtAuthenticator $authenticator */
        $authenticator = $event->getSubject();
        $accessToken = $event->getData('access_token');
        $this->getContainer($authenticator->getRequest())
            ->extend(SessionIdentificationServiceInterface::class)
            ->setConcrete(JwtSessionIdentificationService::class)
            ->addArgument($accessToken)
            ->isShared();
    }
}
