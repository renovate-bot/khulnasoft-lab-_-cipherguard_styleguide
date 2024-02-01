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
namespace Cipherguard\SelfRegistration\Service\Healthcheck;

use App\Error\Exception\FormValidationException;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Http\Exception\InternalErrorException;
use Cipherguard\SelfRegistration\Form\Settings\SelfRegistrationBaseSettingsForm;
use Cipherguard\SelfRegistration\Service\SelfRegistrationGetSettingsService;

class SelfRegistrationHealthcheckService
{
    use FeaturePluginAwareTrait;

    /**
     * @return array
     */
    public function getHealthcheck(): array
    {
        return [
            'isSelfRegistrationPluginEnabled' => $this->isSelfRegistrationPluginEnabled(),
            'selfRegistrationProvider' => $this->selfRegistrationProvider(),
            'isRegistrationPublicRemovedFromCipherguard' => $this->isRegistrationPublicRemovedFromCipherguard(),
        ];
    }

    /**
     * Checks if the plugin is enabled
     *
     * @return bool
     */
    protected function isSelfRegistrationPluginEnabled(): bool
    {
        return $this->isFeaturePluginEnabled('SelfRegistration');
    }

    /**
     * Returns the self registration provider defined in organization settings or null
     *
     * @return ?string
     */
    protected function selfRegistrationProvider(): ?string
    {
        if (!$this->isSelfRegistrationPluginEnabled()) {
            return null;
        }

        try {
            $settings = (new SelfRegistrationGetSettingsService())->getSettings();
        } catch (FormValidationException | MissingConnectionException | InternalErrorException $e) {
            return null;
        }

        $provider = $settings['provider'] ?? '';

        return $this->mapProvider($provider);
    }

    /**
     * Checks that the deprecated config key is not defined in cipherguard.php
     *
     * @return bool
     */
    protected function isRegistrationPublicRemovedFromCipherguard(): bool
    {
        return is_null(Configure::read('cipherguard.registration.public'));
    }

    /**
     * @param string $source provider to map into human intelligible string
     * @return string|null
     */
    protected function mapProvider(string $source): ?string
    {
        $map = [
            SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS => __('Email domain safe list'),
        ];

        return $map[$source] ?? null;
    }
}
