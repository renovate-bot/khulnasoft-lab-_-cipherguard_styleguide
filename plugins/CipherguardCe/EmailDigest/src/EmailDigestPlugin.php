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
 * @since         3.4.0
 */
namespace Cipherguard\EmailDigest;

use App\Notification\DigestTemplate\GroupMembershipDigestTemplate;
use App\Notification\DigestTemplate\GroupUserDeleteDigestTemplate;
use App\Notification\DigestTemplate\ResourceChangesDigestTemplate;
use App\Notification\DigestTemplate\ResourceShareDigestTemplate;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cipherguard\EmailDigest\Command\PreviewCommand;
use Cipherguard\EmailDigest\Command\SenderCommand;
use Cipherguard\EmailDigest\Utility\Digest\DigestTemplateRegistry;

class EmailDigestPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // Core email digests
        DigestTemplateRegistry::getInstance()
            ->addTemplate(new ResourceChangesDigestTemplate())
            ->addTemplate(new ResourceShareDigestTemplate())
            ->addTemplate(new GroupUserDeleteDigestTemplate())
            ->addTemplate(new GroupMembershipDigestTemplate());
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        // Alias commands
        $commands->add('cipherguard email_digest preview', PreviewCommand::class);
        $commands->add('cipherguard email_digest send', SenderCommand::class);

        return $commands;
    }
}
