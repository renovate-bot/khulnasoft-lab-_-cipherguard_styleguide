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

namespace Cipherguard\MultiFactorAuthentication\Service;

use Cake\Controller\Controller;
use Cipherguard\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class ClearMfaCookieInResponseService
{
    /**
     * @var \Cake\Controller\Controller
     */
    private $controller;

    /**
     * @param \Cake\Controller\Controller $controller the controller in action
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Overwrite the MFA cookie with an expired one.
     *
     * @return void
     */
    public function clearMfaCookie(): void
    {
        $expiredMfaCookie = MfaVerifiedCookie::clearCookie($this->controller->getRequest());
        $this->controller->setResponse($this->controller->getResponse()->withCookie($expiredMfaCookie));
    }
}
