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
namespace Cipherguard\SmtpSettings\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cipherguard\SmtpSettings\Middleware\SmtpSettingsSecurityMiddleware;

class SmtpSettingsHealthcheckService
{
    use FeaturePluginAwareTrait;

    /**
     * @var string
     */
    private $cipherguardFileName;

    /**
     * @param string $cipherguardFileName The cipherguard config file, modifiable for unit test purpose.
     */
    public function __construct(string $cipherguardFileName = CONFIG . DS . 'cipherguard.php')
    {
        $this->cipherguardFileName = $cipherguardFileName;
    }

    /**
     * Read the SMTP Settings, detect:
     * - source (DB, file or env)
     * - settings validation issues
     * - decryption issues
     *
     * @param array|null $checks previous checks
     * @return array
     */
    public function check(?array $checks = []): array
    {
        $check = [];
        $check['isEnabled'] = $this->isFeaturePluginEnabled('SmtpSettings');
        $check['areEndpointsDisabled'] = Configure::read(
            SmtpSettingsSecurityMiddleware::CIPHERGURD_SECURITY_SMTP_SETTINGS_ENDPOINTS_DISABLED
        );
        $getService = new SmtpSettingsGetService($this->cipherguardFileName);
        $check['errorMessage'] = false;

        try {
            $smtpSettings = $getService->getSettings();
            $source = $smtpSettings['source'];
        } catch (FormValidationException $e) {
            $source = $e->getForm()->getData('source');
            $check['errorMessage'] = json_encode($e->getErrors());
        } catch (InternalErrorException $e) {
            $check['errorMessage'] = $e->getMessage();
            $source = SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB;
        } catch (\Throwable $e) {
            $check['errorMessage'] = $e->getMessage();
            $source = SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_UNDEFINED;
        }

        $check['source'] = $this->mapSource($source) ?? __('not found');
        $check['isInDb'] = $source === SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB;
        $checks['smtpSettings'] = $check;

        return $checks;
    }

    /**
     * The case where the source is not defined should code-based never occur, but just in case
     * and in order preserve the healthcheck integrity we cover the un defined case.
     *
     * @param string $source source to map into human intelligible string
     * @return string|null
     */
    protected function mapSource(string $source): ?string
    {
        $map = [
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB => __('database'),
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE => CONFIG . 'cipherguard.php',
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_ENV => __('env variables'),
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_UNDEFINED => __('undefined'),
        ];

        return $map[$source] ?? null;
    }
}
