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
namespace Cipherguard\Locale\Test\Lib;

use Cake\Core\Configure;

trait DummySystemLocaleTestTrait
{
    /**
     * Add a dummy system locale.
     */
    public function addFooSystemLocale(): void
    {
        $newOptions = array_merge(
            Configure::readOrFail('cipherguard.plugins.locale.options'),
            [['locale' => 'foo','label' => 'foo-FOO',]]
        );
        Configure::write(
            'cipherguard.plugins.locale.options',
            $newOptions
        );
    }

    /**
     * Remove a dummy system locale.
     */
    public function removeFooSystemLocale(): void
    {
        $options = Configure::readOrFail('cipherguard.plugins.locale.options');
        array_pop($options);
        Configure::write('cipherguard.plugins.locale.options', $options);
    }
}
