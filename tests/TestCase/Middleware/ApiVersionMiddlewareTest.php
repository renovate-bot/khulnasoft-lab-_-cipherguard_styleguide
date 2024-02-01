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
 * @since         3.12.0
 */

namespace App\Test\TestCase\Middleware;

use App\Test\Lib\AppIntegrationTestCase;

/**
 * Test for ApiVersionMiddleware
 */
class ApiVersionMiddlewareTest extends AppIntegrationTestCase
{
    public function testApiVersionMiddleware()
    {
        $this->get('/settings.json?api-version=v1');
        $this->assertResponseCode(400, 'API v1 support is deprecated in this version.');
    }
}
