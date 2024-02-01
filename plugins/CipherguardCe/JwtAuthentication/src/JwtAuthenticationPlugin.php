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
namespace Cipherguard\JwtAuthentication;

use App\Middleware\CsrfProtectionMiddleware;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cipherguard\JwtAuthentication\Authenticator\JwtArmoredChallengeInterface;
use Cipherguard\JwtAuthentication\Authenticator\JwtArmoredChallengeService;
use Cipherguard\JwtAuthentication\Event\LogAuthenticationWithNonValidJwtAccessToken;
use Cipherguard\JwtAuthentication\Event\RemoveCsrfCookieOnJwt;
use Cipherguard\JwtAuthentication\Event\RemoveSessionCookiesOnJwt;
use Cipherguard\JwtAuthentication\Event\SetSessionIdentifierOnLogin;
use Cipherguard\JwtAuthentication\Middleware\JwtAuthDetectionMiddleware;
use Cipherguard\JwtAuthentication\Middleware\JwtCsrfDetectionMiddleware;
use Cipherguard\JwtAuthentication\Middleware\JwtDestroySessionMiddleware;
use Cipherguard\JwtAuthentication\Middleware\JwtRouteFilterMiddleware;
use Cipherguard\JwtAuthentication\Notification\Email\Redactor\JwtAuthenticationEmailRedactorPool;
use Cipherguard\JwtAuthentication\Service\AccessToken\JwksGetService;

class JwtAuthenticationPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->registerListeners($app);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->insertAfter(RoutingMiddleware::class, JwtAuthDetectionMiddleware::class)
            ->insertAfter(JwtAuthDetectionMiddleware::class, JwtRouteFilterMiddleware::class)
            ->insertBefore(AuthenticationMiddleware::class, JwtDestroySessionMiddleware::class)
            ->insertBefore(CsrfProtectionMiddleware::class, JwtCsrfDetectionMiddleware::class);

        return $middlewareQueue;
    }

    /**
     * Register JWT related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new JwtAuthenticationEmailRedactorPool())
            ->on(new LogAuthenticationWithNonValidJwtAccessToken())
            ->on(new RemoveSessionCookiesOnJwt())
            ->on(new RemoveCsrfCookieOnJwt())
            ->on(new SetSessionIdentifierOnLogin());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(JwtArmoredChallengeInterface::class, JwtArmoredChallengeService::class);
        $container->add(JwksGetService::class);
    }
}
