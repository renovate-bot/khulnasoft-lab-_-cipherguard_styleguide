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
 * Check if a key date is set in the future
 * Used to check key expiry date
 */
class IsDateInFutureValidationRule extends CipherguardValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The key should not already be expired.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!($value instanceof ChronosInterface)) {
            return false;
        }

        return $value->greaterThan(FrozenTime::now());
    }
}
