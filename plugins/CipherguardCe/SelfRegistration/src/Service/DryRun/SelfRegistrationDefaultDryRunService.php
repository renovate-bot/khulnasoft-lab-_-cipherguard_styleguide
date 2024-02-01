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
 * @since         3.10.0
 */
namespace Cipherguard\SelfRegistration\Service\DryRun;

use Cake\Http\Exception\NotFoundException;

class SelfRegistrationDefaultDryRunService implements SelfRegistrationDryRunServiceInterface
{
    /**
     * @inheritDoc
     */
    public function isSelfRegistrationOpen(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function canGuestSelfRegister(array $data): bool
    {
        throw new NotFoundException(__('The self registration plugin is not enabled.'));
    }
}
