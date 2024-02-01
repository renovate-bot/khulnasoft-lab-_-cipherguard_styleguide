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
 * @since         3.7.4
 */
namespace App\Controller\Healthcheck;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;

class HealthcheckErrorController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        if (Configure::read('cipherguard.healthcheck.error') === true) {
            $this->Authentication->allowUnauthenticated(['internal']);
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * A lightweight methods that throws an internal error
     * Used to test the error handling.
     *
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function internal(): void
    {
        throw new InternalErrorException();
    }
}
