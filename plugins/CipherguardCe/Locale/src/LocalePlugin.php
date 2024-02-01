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
 * @since         3.2.0
 */
namespace Cipherguard\Locale;

use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cipherguard\Locale\Event\LocaleEmailQueueListener;
use Cipherguard\Locale\Event\LocaleRenderListener;
use Cipherguard\Locale\Event\SaveUserLocaleListener;
use Cipherguard\Locale\Event\ValidateLocaleOnBeforeSaveListener;
use Cipherguard\Locale\Middleware\LocaleMiddleware;

class LocalePlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $this->attachListeners(EventManager::instance());
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue->insertAfter(
            AuthenticationMiddleware::class,
            LocaleMiddleware::class
        );
    }

    /**
     * Attach the Locale related event listeners.
     *
     * @param \Cake\Event\EventManager $eventManager EventManager
     * @return void
     */
    public function attachListeners(EventManager $eventManager): void
    {
        $eventManager
            ->on(new LocaleEmailQueueListener())
            ->on(new SaveUserLocaleListener())
            ->on(new ValidateLocaleOnBeforeSaveListener());

        if (PHP_SAPI === 'cli') {
            $eventManager->on(new LocaleRenderListener());
        }
    }
}
