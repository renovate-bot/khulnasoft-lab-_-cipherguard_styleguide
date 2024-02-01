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
 * @since         2.12.0
 */

namespace Cipherguard\MultiFactorAuthentication\Service;

use App\Model\Entity\User;
use Cipherguard\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService;
use Throwable;

class IsMfaEnabledService
{
    /**
     * @var \Cipherguard\MultiFactorAuthentication\Service\GetMfaAccountSettingsService
     */
    private $getMfaAccountSettingsService;

    /**
     * @var \Cipherguard\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService
     */
    private $getMfaOrgSettingsService;

    /**
     * @param \Cipherguard\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService|null $getMfaOrgSettingsService Service to retrieve MfaOrgSettings
     * @param \Cipherguard\MultiFactorAuthentication\Service\GetMfaAccountSettingsService|null $getMfaAccountSettingsService Service to retrieve MfaAccountSettings
     */
    public function __construct(
        ?MfaOrgSettingsGetService $getMfaOrgSettingsService = null,
        ?GetMfaAccountSettingsService $getMfaAccountSettingsService = null
    ) {
        $this->getMfaAccountSettingsService = $getMfaAccountSettingsService ?? new GetMfaAccountSettingsService();
        $this->getMfaOrgSettingsService = $getMfaOrgSettingsService ?? new MfaOrgSettingsGetService();
    }

    /**
     * @param \App\Model\Entity\User $user User to check if mfa is enabled
     * @return bool
     * @throws \Exception
     */
    public function isEnabledForUser(User $user)
    {
        $mfaOrgSettings = $this->getMfaOrgSettingsService->get();

        if (!$mfaOrgSettings->isEnabled()) {
            return false;
        }

        try {
            $providersEnabledForOrgAndUser = array_intersect(
                $mfaOrgSettings->getEnabledProviders(),
                $this->getMfaAccountSettings($user)->getEnabledProviders()
            );
        } catch (Throwable $t) {
            $providersEnabledForOrgAndUser = [];
        }

        return count($providersEnabledForOrgAndUser) > 0;
    }

    /**
     * @param \App\Model\Entity\User $user User to get settings for
     * @return \Cipherguard\MultiFactorAuthentication\Utility\MfaAccountSettings
     * @throws \Exception
     */
    private function getMfaAccountSettings(User $user)
    {
        return $this->getMfaAccountSettingsService->getSettingsForUser($user);
    }
}
