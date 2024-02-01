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
 * @since         3.2.0
 */

namespace Cipherguard\Locale\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cipherguard\Locale\Service\GetOrgLocaleService;
use Cipherguard\Locale\Service\LocaleService;

/**
 * Class AccountLocalesSelectControllerTest
 */
class AccountLocalesSelectControllerTest extends AppIntegrationTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Cipherguard\AccountSettings\Model\Table\AccountSettingsTable
     */
    protected $AccountSettings;

    public function setUp(): void
    {
        parent::setUp();
        $this->AccountSettings = $this->fetchTable('Cipherguard/AccountSettings.AccountSettings');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        GetOrgLocaleService::clearOrganisationLocale();
    }

    public function testAccountLocalesSelectAsGuestFails()
    {
        $this->postJson('/account/settings/locales.json');
        $this->assertAuthenticationError();
    }

    public function testAccountLocalesSelectSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $value = 'en-UK';
        $this->postJson('/account/settings/locales.json', compact('value'));
        $this->assertResponseSuccess();
        $this->assertSame(
            $value,
            $this->AccountSettings->getByProperty($user->id, LocaleService::SETTING_PROPERTY)->get('value')
        );
    }

    public function testAccountLocalesSelectOnNonSupportedLocale()
    {
        $this->logInAsUser();

        $value = 'foo-BAR';
        $this->postJson('/account/settings/locales.json', compact('value'));
        $this->assertBadRequestError('This is not a valid locale.');
    }
}
