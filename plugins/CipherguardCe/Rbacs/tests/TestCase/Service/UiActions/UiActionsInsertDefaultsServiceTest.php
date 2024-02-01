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

namespace Cipherguard\Rbacs\Test\TestCase\Service\UiActions;

use Cipherguard\Rbacs\Service\UiActions\UiActionsCreateService;
use Cipherguard\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Cipherguard\Rbacs\Test\Lib\RbacsTestCase;

class UiActionsInsertDefaultsServiceTest extends RbacsTestCase
{
    public function testRbacsUiActionsInsertDefaultsService_DiffSuccess()
    {
        $createService = new UiActionsCreateService();
        $createService->create('Folders.use');
        $createService->create('test.action');
        $diff = (new UiActionsInsertDefaultsService())->getDiffDefaultAndDB();
        $this->assertSame(count(UiActionsInsertDefaultsService::DEFAULT_UI_ACTIONS) - 1, count($diff));
    }
}
