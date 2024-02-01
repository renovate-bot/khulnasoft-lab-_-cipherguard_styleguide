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

namespace App\Model\Validation\Fingerprint;

use App\Model\Validation\CipherguardValidationRule;
use App\Service\OpenPGP\PublicKeyValidationService;

class IsValidFingerprintValidationRule extends CipherguardValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The fingerprint should be a string of 40 hexadecimal characters.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return PublicKeyValidationService::isValidFingerprint($value);
    }
}
