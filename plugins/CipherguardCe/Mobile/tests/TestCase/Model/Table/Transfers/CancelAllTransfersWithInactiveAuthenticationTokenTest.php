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

namespace Cipherguard\Mobile\Test\TestCase\Model\Table\Transfers;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cipherguard\Mobile\Model\Entity\Transfer;
use Cipherguard\Mobile\Model\Table\TransfersTable;
use Cipherguard\Mobile\Test\Factory\TransferFactory;

class CancelAllTransfersWithInactiveAuthenticationTokenTest extends AppTestCase
{
    /**
     * @var TransfersTable $Transfers
     */
    public $Transfers;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Cipherguard/Mobile.Transfers') ?
            [] : ['className' => TransfersTable::class];
        $this->Transfers = TableRegistry::getTableLocator()->get('Cipherguard/Mobile.Transfers', $config);
    }

    public function testTransfersTablesCancelAllTransfersWithInactiveAuthenticationToken(): void
    {
        $inactiveToken = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_MOBILE_TRANSFER)
            ->inactive();

        TransferFactory::make()
            ->status(Transfer::TRANSFER_STATUS_START)
            ->withAuthenticationToken($inactiveToken)
            ->persist();

        $activeToken = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_MOBILE_TRANSFER)
            ->active();

        TransferFactory::make()
            ->status(Transfer::TRANSFER_STATUS_START)
            ->withAuthenticationToken($activeToken)
            ->persist();

        $this->Transfers->cancelAllTransfersWithInactiveAuthenticationToken();

        $transfers = $this->Transfers->find()
            ->all()
            ->toArray();

        $this->Transfers->cancelAllTransfersWithInactiveAuthenticationToken();
        $this->assertTrue(count(Hash::extract($transfers, '{n}[status=cancel].status')) === 1);
        $this->assertTrue(count(Hash::extract($transfers, '{n}[status=start].status')) === 1);
    }
}
