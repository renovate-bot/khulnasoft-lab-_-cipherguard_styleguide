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
namespace App\Test\TestCase;

use App\Application;
use App\Middleware\ApiVersionMiddleware;
use App\Middleware\ContainerInjectorMiddleware;
use App\Middleware\ContentSecurityPolicyMiddleware;
use App\Middleware\CsrfProtectionMiddleware;
use App\Middleware\GpgAuthHeadersMiddleware;
use App\Middleware\HttpProxyMiddleware;
use App\Middleware\SessionAuthPreventDeletedOrDisabledUsersMiddleware;
use App\Middleware\SessionPreventExtensionMiddleware;
use App\Middleware\SslForceMiddleware;
use App\Middleware\UuidParserMiddleware;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\TestCase;

/**
 * ApplicationTest class
 */
class ApplicationTest extends TestCase
{
    /**
     * @return void
     */
    public function testApplication_Middleware()
    {
        $app = new Application('');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $middlewareClassesInOrder = [
            ContainerInjectorMiddleware::class,
            ContentSecurityPolicyMiddleware::class,
            ErrorHandlerMiddleware::class,
            SslForceMiddleware::class,
            AssetMiddleware::class,
            RoutingMiddleware::class,
            UuidParserMiddleware::class,
            ApiVersionMiddleware::class,
            SessionPreventExtensionMiddleware::class,
            BodyParserMiddleware::class,
            SessionAuthPreventDeletedOrDisabledUsersMiddleware::class,
            AuthenticationMiddleware::class,
            GpgAuthHeadersMiddleware::class,
            CsrfProtectionMiddleware::class,
            HttpProxyMiddleware::class,
        ];

        foreach ($middlewareClassesInOrder as $midClass) {
            $this->assertInstanceOf($midClass, $middleware->current());
            $middleware->next();
        }
    }
}
