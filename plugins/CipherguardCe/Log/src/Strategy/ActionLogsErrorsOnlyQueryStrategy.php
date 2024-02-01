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
namespace Cipherguard\Log\Strategy;

use Cipherguard\Log\Model\Entity\ActionLog;

/**
 * Extends the default strategy but logs errors only
 */
class ActionLogsErrorsOnlyQueryStrategy extends ActionLogsDefaultQueryStrategy
{
    /**
     * @inheritDoc
     */
    public function query(ActionLog $actionLog)
    {
        if ($actionLog->isStatusSuccess()) {
            return false;
        }

        return parent::query($actionLog);
    }
}
