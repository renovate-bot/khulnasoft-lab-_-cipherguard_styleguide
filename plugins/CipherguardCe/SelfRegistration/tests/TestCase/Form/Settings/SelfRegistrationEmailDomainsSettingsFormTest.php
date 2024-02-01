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

namespace Cipherguard\SelfRegistration\Test\TestCase\Form\Settings;

use App\Model\Validation\EmailValidationRule;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cipherguard\SelfRegistration\Form\Settings\SelfRegistrationBaseSettingsForm;
use Cipherguard\SelfRegistration\Form\Settings\SelfRegistrationEmailDomainsSettingsForm;

class SelfRegistrationEmailDomainsSettingsFormTest extends TestCase
{
    /**
     * @var \Cipherguard\SelfRegistration\Form\Settings\SelfRegistrationEmailDomainsSettingsForm
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new SelfRegistrationEmailDomainsSettingsForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
        Configure::write(EmailValidationRule::MX_CHECK_KEY, false);
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Valid_Provider_And_Data_Valid_Should_Succeed()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $this->assertTrue($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'cipherguard.khulnasoft.com',
                'blog.cipherguard.khulnasoft.com',
            ]],
        ]));
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_Sanitize_Data()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $this->assertTrue($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'foo' => 'bar',
            'data' => ['allowed_domains' => [
                'some key to be sanitized' => 'cipherguard.khulnasoft.com',
            ], 'foo' => 'baz'],
        ]));
        $this->assertSame([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'cipherguard.khulnasoft.com',
            ],],
        ], $this->form->getData());
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Valid_Provider_And_Empty_Domains_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => ''],
        ]));
        $this->assertSame(
            'The list of allowed domains should not be empty.',
            $this->form->getErrors()['data']['allowed_domains']['_empty']
        );
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Valid_Provider_And_Non_String_Domains_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => 'foo'],
        ]));
        $this->assertSame(
            [
                'data' => [
                    'allowed_domains' => [
                        'areEmailDomainsValid' => 'The list of allowed domains should be an array of strings.',
                    ],
                ],
            ],
            $this->form->getErrors()
        );
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Invalid_Provider_And_Data_Valid_Should_Fail()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $domain = 'cipherguard-' . rand(999, 9999) . '.com';
        $this->assertFalse($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'cipherguard.khulnasoft.com',
                $domain,
            ]],
        ]));

        $this->assertSame(
            'The domain #1 should be a valid domain.',
            $this->form->getErrors()['data']['allowed_domains']['areEmailDomainsValid']
        );
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Invalid_Provider_And_Data_Valid_And_MX_Check_Off_Should_Not_Fail()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, false);
        $domain = 'cipherguard-' . rand(999, 9999) . '.com';
        $this->assertTrue($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'cipherguard.khulnasoft.com',
                $domain,
            ]],
        ]));
    }
}
