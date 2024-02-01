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
 * @since         2.5.0
 */
namespace Cipherguard\MultiFactorAuthentication\Form\Duo;

use App\Utility\UserAccessControl;
use Cipherguard\MultiFactorAuthentication\Form\MfaForm;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyForm extends MfaForm
{
    /**
     * @var \Cipherguard\MultiFactorAuthentication\Utility\MfaSettings
     */
    protected $settings;

    /**
     * VerifyForm constructor.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Cipherguard\MultiFactorAuthentication\Utility\MfaSettings $settings settings
     */
    public function __construct(UserAccessControl $uac, MfaSettings $settings)
    {
        parent::__construct($uac);
        $this->settings = $settings;
    }
}
