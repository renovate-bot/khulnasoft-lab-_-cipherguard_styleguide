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
namespace Cipherguard\Rbacs\Test\Lib;

use App\Test\Lib\AppIntegrationTestCase;
use Cipherguard\Rbacs\RbacsPlugin;

abstract class RbacsIntegrationTestCase extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(RbacsPlugin::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin(RbacsPlugin::class);
    }
}
