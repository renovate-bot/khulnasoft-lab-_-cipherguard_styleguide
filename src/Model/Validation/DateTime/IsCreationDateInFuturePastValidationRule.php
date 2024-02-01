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
 * @since         3.6.0
 */

namespace App\Model\Validation\DateTime;

use App\Model\Validation\CipherguardValidationRule;
use Cake\Chronos\ChronosInterface;
use Cake\I18n\FrozenTime;

/**
 * Check if a key date is set in the past... tomorrow!
 *
 * In a ideal world we should check if a key date is set in the past from 'now'
 * where now is the time of reference of the server. But in practice we
 * allow a next day margin because users had the issue of having keys generated
 * by systems that were ahead of server time. Refs. CIPHERGURD-1505.
 */
class IsCreationDateInFuturePastValidationRule extends CipherguardValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The creation date should be set in the past.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!($value instanceof ChronosInterface)) {
            return false;
        }

        /** @var \Cake\Chronos\ChronosInterface $nowWithMargin */
        $nowWithMargin = FrozenTime::now()->modify('+12 hours');

        return $value->lessThan($nowWithMargin);
    }
}
