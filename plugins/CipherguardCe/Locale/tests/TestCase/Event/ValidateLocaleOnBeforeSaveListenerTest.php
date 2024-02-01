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

namespace Cipherguard\Locale\Test\TestCase\Event;

use App\Error\Exception\ValidationException;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\Locale\Service\GetOrgLocaleService;
use Cipherguard\Locale\Service\LocaleService;

class ValidateLocaleOnBeforeSaveListenerTest extends TestCase
{
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Cipherguard/Locale' => []]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        GetOrgLocaleService::clearOrganisationLocale();
    }

    public function dataForTestEmailLocaleServiceGetLocale(): array
    {
        return [
            ['fr-FR'],
            ['fr_FR'],
            ['fr+FR', ValidationException::class],
            ['fr*FR', ValidationException::class],
            ['fr FR', ValidationException::class],
            ['xx-YY', ValidationException::class],
            ['', ValidationException::class],
            [null, \TypeError::class],
        ];
    }

    /**
     * @param string|null $recipient The email's recipient
     * @param string $expectException
     * @throws \Exception
     * @dataProvider dataForTestEmailLocaleServiceGetLocale
     */
    public function testLocaleBeforeSaveValidation(?string $locale, ?string $expectException = ''): void
    {
        if ($expectException) {
            $this->expectException($expectException);
        }

        $setting = TableRegistry::getTableLocator()->get('Cipherguard/AccountSettings.AccountSettings')
            ->createOrUpdateSetting(
                UserFactory::make()->persist()->id,
                LocaleService::SETTING_PROPERTY,
                $locale
            );

        $this->assertSame('fr-FR', $setting->get('value'));
    }
}
