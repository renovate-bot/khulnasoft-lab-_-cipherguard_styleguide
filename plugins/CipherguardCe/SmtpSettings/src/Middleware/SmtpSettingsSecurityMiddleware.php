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
 * @since         3.9.0
 */
namespace Cipherguard\SmtpSettings\Middleware;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SmtpSettingsSecurityMiddleware implements MiddlewareInterface
{
    public const CIPHERGURD_SECURITY_SMTP_SETTINGS_ENDPOINTS_DISABLED =
        'cipherguard.security.smtpSettings.endpointsDisabled';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (Configure::read(self::CIPHERGURD_SECURITY_SMTP_SETTINGS_ENDPOINTS_DISABLED)) {
            throw new ForbiddenException(__('SMTP settings endpoints disabled.'));
        }

        return $handler->handle($request);
    }
}
