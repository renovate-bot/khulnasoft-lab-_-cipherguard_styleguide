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

namespace Cipherguard\Rbacs\Test\TestCase\Table;

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Cipherguard\Rbacs\Test\Factory\UiActionFactory;
use Cipherguard\Rbacs\Test\Lib\RbacsTestCase;

class UiActionsTableTest extends RbacsTestCase
{
    use FormatValidationTrait;

    /**
     * @var \Cipherguard\Rbacs\Model\Table\UiActionsTable
     */
    public $UiActions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->UiActions = TableRegistry::getTableLocator()->get('Cipherguard/Rbacs.UiActions');
    }

    /**
     * Get default entity options.
     */
    public function getDummyEntityDefaultOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    public function testUiActionsTable_ValidationName(): void
    {
        $testCases = [
            'ascii' => self::getAsciiTestCases(255),
        ];
        $data = UiActionFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->UiActions, 'name', $data, self::getDummyEntityDefaultOptions(), $testCases);
    }
}
