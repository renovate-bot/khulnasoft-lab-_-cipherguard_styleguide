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

use App\Error\Exception\NoAdminInDbException;
use App\Model\Entity\OrganizationSetting;
use App\Model\Entity\Role;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UserAccessControl;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class SmtpSettingsMigrationService
{
    use FeaturePluginAwareTrait;

    /**
     * @var array
     */
    private $smtpSettings;

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
     * Save SMTP Settings in the DB if defined in config/cipherguard.php file
     *
     * @return \App\Model\Entity\OrganizationSetting|null
     */
    public function migrateSmtpSettingsToDb(): ?OrganizationSetting
    {
        if (!$this->isFeaturePluginEnabled('SmtpSettings')) {
            return null;
        }

        $orgSetting = null;
        try {
            $this->fetchSettings();
            if ($this->isSourceFile()) {
                $orgSetting = $this->saveSettingsInDb();
                $orgSetting->set('source', SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE);
            }
        } catch (NoAdminInDbException $e) {
          // Silently do nothing, this is probably due running a fresh installation
            Log::info($e->getMessage() . ' Ignoring the import of the SMTP Settings.');
        } catch (\Throwable $e) {
            $this->logWarning($e->getMessage());
        }

        return $orgSetting;
    }

    /**
     * Read the present settings
     *
     * @return void
     */
    private function fetchSettings(): void
    {
        $this->smtpSettings = (new SmtpSettingsGetService($this->cipherguardFileName))->getSettings();
        Log::info('SMTP Settings were detected in ' . $this->getSource() . '.');
    }

    /**
     * Import settings in the DB if found in file
     *
     * @return \App\Model\Entity\OrganizationSetting
     * @throws \App\Error\Exception\NoAdminInDbException if no admin is found
     */
    private function saveSettingsInDb(): OrganizationSetting
    {
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstAdminOrThrowNoAdminInDbException();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $orgSetting = (new SmtpSettingsSetService($uac))->saveSettings($this->smtpSettings);
        Log::info('SMTP Settings were imported from ' . CONFIG . DS . 'cipherguard.php to the database.');

        return $orgSetting;
    }

    /**
     * @return string|null
     */
    private function getSource(): ?string
    {
        return $this->smtpSettings['source'] ?? null;
    }

    /**
     * @return bool
     */
    private function isSourceFile(): bool
    {
        return $this->getSource() === SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE;
    }

    /**
     * @param string $msg Message to log
     * @return void
     */
    private function logWarning(string $msg): void
    {
        Log::warning('There was an error in V380SaveSmtpSettingsInDb');
        Log::warning($msg);
    }
}
