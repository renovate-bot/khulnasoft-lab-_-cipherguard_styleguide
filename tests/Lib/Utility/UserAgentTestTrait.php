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
 * @since         3.8.0
 */
namespace App\Test\Lib\Utility;

/**
 * @depends on IntegrationTestTrait
 */
trait UserAgentTestTrait
{
    /**
     * @param string $agent User agent
     * @return void
     */
    public function mockUserAgent(string $agent = 'foo')
    {
        $this->_request['headers']['USER_AGENT'] = $agent;
    }
}
