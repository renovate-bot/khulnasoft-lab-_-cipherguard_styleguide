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
 * @since         2.13.0
 */

namespace Cipherguard\EmailDigest\Command;

use App\Command\CipherguardCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cipherguard\EmailDigest\Service\SendEmailBatchService;

class SenderCommand extends CipherguardCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription(__('Sends a batch of queued emails as emails digests.'))
            ->addOption('limit', [
                'short' => 'l',
                'help' => __('How many emails should be sent in this batch?'),
                'default' => Configure::read('cipherguard.plugins.emailDigest.batchSizeLimit'),
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $limit = (int)$args->getOption('limit');
        /** @var \EmailQueue\Model\Table\EmailQueueTable $EmailQueueTable */
        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $emails = $EmailQueueTable->getBatch($limit);
        (new SendEmailBatchService())->sendNextEmailsBatch($emails);

        return $this->successCode();
    }
}
