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
 * @since         4.2.0
 */

return [
    'cipherguard' => [
        'plugins' => [
            'passwordPolicies' => [
                'version' => '1.0.0',
                'enabled' => true,
                /*
                 * 'defaultPasswordGenerator' => 'password'
                 *
                 * The default password generator type, by default 'password', however it is customizable.
                 *
                 * EE administrators can redefine it via the Password Policy administration page in the application.
                 *
                 * While CE & EE administrators can redefine it:
                 * - By adding an entry under the key (cipherguard.plugins.passwordPolicies.defaultPasswordGenerator) in
                 *   the cipherguard.php config file;
                 * - By setting the environment variable: CIPHERGURD_PLUGINS_PASSWORD_POLICIES_DEFAULT_PASSWORD_GENERATOR_TYPE
                 */
            ],
        ],
    ],
];
