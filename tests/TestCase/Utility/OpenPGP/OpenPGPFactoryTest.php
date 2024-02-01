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
 * @since         2.10.0
 */
namespace App\Test\TestCase\Utility\OpenPGP;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;

class OpenPGPFactoryTest extends TestCase
{
    public function testOpenPGPFactoryGetError()
    {
        OpenPGPBackendFactory::reset();
        $this->expectException(InternalErrorException::class);
        Configure::write('cipherguard.gpg.backend', 'nope');
        OpenPGPBackendFactory::get();
    }

    public function testOpenPGPFactoryGetSuccess()
    {
        OpenPGPBackendFactory::reset();
        Configure::write('cipherguard.gpg.backend', OpenPGPBackendFactory::GNUPG);
        $gpg = OpenPGPBackendFactory::get();
        $this->assertNotEmpty($gpg);
    }

    public function testOpenPGPFactoryCreateError()
    {
        $this->expectException(InternalErrorException::class);
        OpenPGPBackendFactory::create('error');
    }
}
