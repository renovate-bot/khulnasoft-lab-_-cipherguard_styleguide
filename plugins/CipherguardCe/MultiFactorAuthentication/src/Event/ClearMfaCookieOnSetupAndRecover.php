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
 * @since         3.6.0
 */
namespace Cipherguard\MultiFactorAuthentication\Event;

use App\Controller\Component\UserComponent;
use App\Controller\Setup\RecoverAbortController;
use App\Controller\Setup\RecoverCompleteController;
use App\Controller\Setup\SetupCompleteController;
use App\Controller\Users\UsersRecoverController;
use App\Controller\Users\UsersRegisterController;
use App\Model\Entity\Role;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cipherguard\MultiFactorAuthentication\Service\ClearMfaCookieInResponseService;

class ClearMfaCookieOnSetupAndRecover implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.shutdown' => 'clearMfaCookieInResponse',
        ];
    }

    /**
     * The controllers concerned
     *
     * @return string[]
     */
    public function getListOfControllers(): array
    {
        return [
            UsersRegisterController::class,
            UsersRecoverController::class,
            SetupCompleteController::class,
            RecoverCompleteController::class,
            RecoverAbortController::class,
        ];
    }

    /**
     * If a user is accessing one of the end points above and is guest,
     * clear any MFA Cookie found in the request.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function clearMfaCookieInResponse(EventInterface $event): void
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();

        $isControllerInList = in_array(get_class($controller), $this->getListOfControllers());
        $isPost = $controller->getRequest()->is(['POST', 'PUT']);

        if (!$isControllerInList) {
            return;
        } elseif (!$isPost) {
            return;
        } elseif (!isset($controller->User) || !($controller->User instanceof UserComponent)) {
            Log::error('The User component is not set for ' . get_class($controller));

            return;
        }

        $isUserGuest = $controller->User->getAccessControl()->roleName() === Role::GUEST;
        if ($isUserGuest) {
            (new ClearMfaCookieInResponseService($controller))->clearMfaCookie();
        }
    }
}
