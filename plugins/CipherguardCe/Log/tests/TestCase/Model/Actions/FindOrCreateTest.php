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
 */

namespace Cipherguard\Log\Test\TestCase\Model\Actions;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class FindOrCreateTest
 */
class FindOrCreateTest extends AppTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Cipherguard\Log\Model\Table\ActionsTable
     */
    protected $Actions;

    public function setUp(): void
    {
        parent::setUp();
        $this->Actions = $this->fetchTable('Cipherguard/Log.Actions');
    }

    /**
     * Test FindOrCreateAction function.
     */
    public function testLogFindOrCreateDoNotCreateDuplicates()
    {
        // Delete cache
        $this->Actions->clearCache();

        $this->Actions->findOrCreateAction(UuidFactory::uuid('Test.test'), 'Test.test');
        $allActions = $this->Actions->find()->all();
        $this->assertEquals(count($allActions), 1);

        $this->Actions->findOrCreateAction(UuidFactory::uuid('Test.test'), 'Test.test');
        $allActions = $this->Actions->find()->all();
        $this->assertEquals(count($allActions), 1);
    }
}
