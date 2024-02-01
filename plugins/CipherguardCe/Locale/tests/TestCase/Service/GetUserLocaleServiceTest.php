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

namespace Cipherguard\Locale\Test\TestCase\Service;

use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\Locale\Service\GetOrgLocaleService;
use Cipherguard\Locale\Service\GetUserLocaleService;
use Cipherguard\Locale\Test\Factory\LocaleSettingFactory;
use Cipherguard\Locale\Test\Lib\DummySystemLocaleTestTrait;

class GetUserLocaleServiceTest extends TestCase
{
    use DummySystemLocaleTestTrait;
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Cipherguard/Locale' => []]);
        $this->addFooSystemLocale();
    }

    public function tearDown(): void
    {
        GetOrgLocaleService::clearOrganisationLocale();
        $this->removeFooSystemLocale();
        parent::tearDown();
    }

    public function dataForTestGetUserLocaleServiceGetLocale(): array
    {
        return [
            ['hasLocaleSetting@test.test', 'fr-FR'],
            ['hasNoSetting@test.test', 'foo'],
            ['i_am_not_an_email', 'foo'],
            ['', 'foo'],
        ];
    }

    /**
     * @param string $recipient The email's recipient
     * @param string $expected
     * @throws \Exception
     * @dataProvider dataForTestGetUserLocaleServiceGetLocale
     */
    public function testGetUserLocaleServiceGetLocaleInEmail(string $recipient, string $expected): void
    {
        UserFactory::make(['username' => $recipient])
            ->withLocale('fr-FR')
            ->persist();

        LocaleSettingFactory::make()->locale('foo')->persist();

        $service = new GetUserLocaleService();

        $this->assertSame(
            $expected,
            $service->getLocale('hasLocaleSetting@test.test')
        );
    }
}
