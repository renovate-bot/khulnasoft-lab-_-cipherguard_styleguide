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
 * @since         4.1.0
 */
namespace App\Test\Lib\Utility;

use App\Application;
use Cake\Event\EventManager;
use Cake\Http\ControllerFactoryInterface;
use Cake\Http\Response;

trait MiddlewareTestTrait
{
    private function mockHandler(?Response $response = null): Application
    {
        $response = $response ?? new Response();

        $controllerFactoryStub = $this->getMockBuilder(ControllerFactoryInterface::class)->getMock();
        $controllerFactoryStub->method('invoke')->willReturn($response);

        return new Application(CONFIG, new EventManager(), $controllerFactoryStub);
    }
}
