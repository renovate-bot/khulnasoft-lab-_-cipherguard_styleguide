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

use Cake\TestSuite\TestCase;
use Cipherguard\SelfRegistration\Form\Settings\SelfRegistrationBaseSettingsForm;

class SelfRegistrationBaseSettingsFormTest extends TestCase
{
    /**
     * @var \Cipherguard\SelfRegistration\Form\Settings\SelfRegistrationBaseSettingsForm
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = $this->getMockForAbstractClass(SelfRegistrationBaseSettingsForm::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function testSelfRegistrationBaseSettingsForm_With_Provider_Null_And_Data_Null_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => '',
            'data' => '',
        ]));

        $this->assertSame([
            'provider' => [
                '_empty' => 'The provider should not be empty.',
            ],
            'data' => [
                '_empty' => 'The data should not be empty.',
            ],
        ], $this->form->getErrors());
    }

    public function testSelfRegistrationBaseSettingsForm_With_Provider_In_List_And_Data_Not_Null_Should_Succeed()
    {
        $this->assertTrue($this->form->execute([
            'provider' => $this->form::USER_SELF_REGISTRATION_PROVIDERS[0],
            'data' => 'bar',
        ]));
    }

    public function testSelfRegistrationBaseSettingsForm_With_Provider_Not_In_List_And_Data_Not_Null_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => 'foo',
            'data' => 'bar',
        ]));
        $this->assertSame([
            'provider' => [
                'inList' => 'The provider should be part of the supported list: email_domains.',
            ],
        ], $this->form->getErrors());
    }

    public function testSelfRegistrationBaseSettingsForm_With_Provider_Not_Null_And_Data_Null_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => $this->form::USER_SELF_REGISTRATION_PROVIDERS[0],
            'data' => '',
        ]));
        $this->assertSame([
            'data' => [
                '_empty' => 'The data should not be empty.',
            ],
        ], $this->form->getErrors());
    }

    public function testSelfRegistrationBaseSettingsForm_Sanitized_Data()
    {
        $this->form->execute([
            'provider' => 'foo',
            'data' => 'bar',
            'some-useless-data',
            'some' => 'more-useless-data',
        ]);

        $this->assertSame([
            'provider' => 'foo',
            'data' => 'bar',
        ], $this->form->getData());
    }
}
