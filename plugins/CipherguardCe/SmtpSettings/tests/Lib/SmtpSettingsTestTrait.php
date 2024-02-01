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

namespace Cipherguard\SmtpSettings\Test\Lib;

use App\Model\Entity\OrganizationSetting;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use App\Utility\Filesystem\DirectoryUtility;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Mailer\TransportFactory;
use Cipherguard\SmtpSettings\Service\SmtpSettingsSetService;
use Cipherguard\SmtpSettings\Test\Factory\SmtpSettingFactory;

/**
 * @covers \Cipherguard\SmtpSettings\Service\SmtpSettingsSetService
 */
trait SmtpSettingsTestTrait
{
    use GpgAdaSetupTrait;

    /**
     * @var string
     */
    protected $dummyCipherguardFile = TMP . 'tests' . DS . 'cipherguard.php';

    /**
     * @return \Cake\Mailer\Message[]
     */
    protected function getSentMessages(): array
    {
        /** @var \App\Mailer\Transport\DebugTransport $transport */
        $transport = TransportFactory::get('default');

        return $transport->getMessages();
    }

    private function getSmtpSettingsData(?string $field = null, $value = null): array
    {
        $validData = [
            'sender_name' => 'John Doe',
            'sender_email' => 'johndoe@cipherguard.test',
            'host' => 'some host',
            'tls' => true,
            'port' => (string)rand(1, 999),
            'client' => 'cipherguard.khulnasoft.com',
            'username' => 'test-user',
            'password' => 'test-secret',
        ];

        if (isset($field)) {
            $validData[$field] = $value;
        }

        return $validData;
    }

    private function encryptAndPersistSmtpSettings($data): OrganizationSetting
    {
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($data));

        /** @var \App\Model\Entity\OrganizationSetting $setting */
        $setting = SmtpSettingFactory::make()->value($encryptedSettings)->persist();

        return $setting;
    }

    protected function assertTransportConfigMatches(array $expectedConfig): void
    {
        foreach ($expectedConfig as $k => $v) {
            $this->assertSame($v, TransportFactory::get('default')->getConfig($k));
        }
    }

    private function setTransportConfig(?string $field = null, $value = null): void
    {
        $validConfig = [
            'host' => 'some test host',
            'tls' => true,
            'port' => '25',
            'username' => 'user',
            'password' => 'secret',
        ];

        if (isset($field)) {
            $validConfig[$field] = $value;
        }

        TransportFactory::get('default')->setConfig($validConfig);
    }

    private function makeDummyCipherguardFile(array $data)
    {
        $phpConfig = new PhpConfig(TMP . 'tests' . DS);
        if (!$phpConfig->dump('cipherguard', $data)) {
            $this->markTestSkipped(TMP . 'tests' . DS . 'cipherguard not writable, skipping test');
        }
    }

    private function deleteCipherguardDummyFile(): void
    {
        DirectoryUtility::removeRecursively($this->dummyCipherguardFile);
    }

    private function assertFileSettingsHaveTheRightKeys(array $settings)
    {
        $keys = array_keys($settings);
        $expectedKeys = SmtpSettingsSetService::SMTP_SETTINGS_ALLOWED_FIELDS;
        asort($keys);
        asort($expectedKeys);

        $this->assertEquals(array_values($expectedKeys), array_values($keys));
    }

    private function assertDBSettingsHaveTheRightKeys(array $settings)
    {
        $keys = array_keys($settings);
        $expectedKeys = array_merge(
            SmtpSettingsSetService::SMTP_SETTINGS_ALLOWED_FIELDS,
            ['id', 'created', 'modified', 'created_by', 'modified_by',]
        );
        asort($keys);
        asort($expectedKeys);

        $this->assertEquals(array_values($expectedKeys), array_values($keys));
    }

    /**
     * Sets the SMTP settings security flag to false
     *
     * @return void
     */
    public function enableSmtpSettingsEndpoints()
    {
        Configure::write('cipherguard.security.smtpSettings.endpointsDisabled', false);
    }

    /**
     * Sets the SMTP settings security flag to true
     *
     * @return void
     */
    public function disableSmtpSettingsEndpoints()
    {
        Configure::write('cipherguard.security.smtpSettings.endpointsDisabled', true);
    }
}
