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
 * @since         3.3.0
 */

return [
    'cipherguard' => [
        'plugins' => [
            'passwordGenerator' => [
                'version' => '4.2.0',
            /**
             * 'defaultPasswordGenerator' => 'password'
             *
             * @deprecated with v5.0. The setting is now handled by the PasswordPolicies plugin.
             */
            ],
        ],
    ],
];
