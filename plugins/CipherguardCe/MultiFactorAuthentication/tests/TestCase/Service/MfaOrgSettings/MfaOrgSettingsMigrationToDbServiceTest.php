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
 * @since         3.7.3
 */

namespace Cipherguard\MultiFactorAuthentication\Test\TestCase\Service\MfaOrgSettings;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsMigrationToDbService;
use Cipherguard\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Cipherguard\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Cipherguard\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsMigrationToDbServiceTest extends TestCase
{
    use MfaOrgSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var \Cipherguard\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsMigrationToDbService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        MfaSettings::clear();
        /** @psalm-suppress InternalMethod */
        $this->service = new MfaOrgSettingsMigrationToDbService();
        $this->loadPlugins(['Cipherguard/MultiFactorAuthentication' => []]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    /**
     * If no admins are in the DB, e.g. at installation
     * Then do not activate the TOTP (TOTP de-activated by default)
     */
    public function testMfaOrgSettingsMigrationToDbService_NoAdmin_Should_Not_Save_Settings()
    {
        $this->service->migrate();
        $this->assertSame(0, MfaOrganizationSettingFactory::count());
    }

    /**
     * If no settings are in the DB and Totp was NOT DISABLED in env(CIPHERGURD_PLUGINS_MFA_PROVIDERS_TOTP)
     * Then store TOTP as enabled in the DB (legacy behavior with TOTP activated by default)
     */
    public function testMfaOrgSettingsMigrationToDbService_NoEnvVariable_NoDBSettings_Should_EnableTotp_In_Db()
    {
        UserFactory::make()->admin()->persist();

        $this->service->migrate();

        $this->assertSame(
            UserFactory::find()->firstOrFail()->get('id'),
            MfaOrganizationSettingFactory::find()->firstOrFail()->get('created_by')
        );
        $expectedConfig = ['providers' => ['totp']];
        $this->assertSame($expectedConfig, $this->getMfaOrganizationSettingValue());
    }

    /**
     * If no settings are in the DB and Totp was DISABLED in env(CIPHERGURD_PLUGINS_MFA_PROVIDERS_TOTP)
     * Then store TOTP as enabled in the DB (legacy behavior with TOTP activated by default)
     */
    public function testMfaOrgSettingsMigrationToDbService_WithEnvVariableSetToFalse_NoDBSettings_Should_DisableTotp_In_Db()
    {
        UserFactory::make()->admin()->persist();

        putenv('CIPHERGURD_PLUGINS_MFA_PROVIDERS_TOTP=false');

        $this->service->migrate();

        $expectedConfig = ['providers' => []];
        $this->assertSame($expectedConfig, $this->getMfaOrganizationSettingValue());

        putenv('CIPHERGURD_PLUGINS_MFA_PROVIDERS_TOTP');
    }

    public function testMfaOrgSettingsMigrationToDbService_WithDBSettings_Should_Not_Overwrite_DB_Settings()
    {
        UserFactory::make()->admin()->persist();

        $settings = ['foo' => 'bar'];
        MfaOrganizationSettingFactory::make()->value($settings)->persist();

        $this->service->migrate();

        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $this->assertSame($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationToDbService_With_Unknown_Provider_Should_Not_Be_Stored()
    {
        UserFactory::make()->admin()->persist();

        $settings['providers'] = ['foo' => true];
        $expectedSettings['providers'] = ['totp'];
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $this->assertSame($expectedSettings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationToDbService_With_Complete_Settings()
    {
        UserFactory::make()->admin()->persist();
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $settings['providers'] = ['totp', 'duo', 'yubikey'];
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationToDbService_WithOtherProvidersAndNoTotpInDB_And_WithTotpEnv_Must_Enable_Totp()
    {
        putenv('CIPHERGURD_PLUGINS_MFA_PROVIDERS_TOTP=true');

        UserFactory::make()->admin()->persist();
        $settings = $this->getDefaultMfaOrgSettings();
        unset($settings[MfaSettings::PROVIDERS][MfaSettings::PROVIDER_TOTP]);
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $settings['providers'] = ['duo', 'yubikey', 'totp'];
        $this->assertEquals($settings, $settingsInDB);

        putenv('CIPHERGURD_PLUGINS_MFA_PROVIDERS_TOTP');
    }

    public function testMfaOrgSettingsMigrationToDbService_With_Invalid_Settings()
    {
        UserFactory::make()->admin()->persist();
        $settings = $this->getDefaultMfaOrgSettings();
        $settings['yubikey']['clientId'] = 'invalid ';
        $this->mockMfaOrgSettings($settings);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Could not validate multi-factor authentication provider configuration.');
        $this->service->migrate();
    }
}
