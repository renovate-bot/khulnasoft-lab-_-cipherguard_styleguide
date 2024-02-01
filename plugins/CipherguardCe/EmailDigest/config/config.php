<?php
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
 * @since         3.0.0
 */
return [
    'cipherguard' => [
        'plugins' => [
            'emailDigest' => [
                'version' => '1.0.0',
                'enabled' => env('CIPHERGURD_PLUGINS_EMAIL_DIGEST_ENABLED', true),
                'batchSizeLimit' => filter_var(
                    env('CIPHERGURD_PLUGINS_EMAIL_DIGEST_BATCH_SIZE_LIMIT', '100'),
                    FILTER_VALIDATE_INT
                ),
            ],
        ],
    ],
];
