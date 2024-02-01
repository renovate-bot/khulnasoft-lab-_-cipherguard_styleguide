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
namespace Cipherguard\Log;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cipherguard\Log\Events\ActionLogsAfterCreateListener;
use Cipherguard\Log\Events\ActionLogsBeforeRenderListener;
use Cipherguard\Log\Events\ActionLogsModelListener;

class LogPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $app->getEventManager()
            ->on(new ActionLogsAfterCreateListener())
            ->on(new ActionLogsBeforeRenderListener())
            ->on(new ActionLogsModelListener());
    }
}
