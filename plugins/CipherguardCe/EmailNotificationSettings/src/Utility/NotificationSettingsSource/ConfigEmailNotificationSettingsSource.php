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
 * @since         2.13.0
 */

namespace Cipherguard\EmailNotificationSettings\Utility\NotificationSettingsSource;

use Cake\Core\Configure;

class ConfigEmailNotificationSettingsSource implements ReadableEmailNotificationSettingsSourceInterface
{
    /**
     * Return an array of notification settings with notification setting name as key and notification setting value as value.
     * Notification setting names must use the dotted key normalization.
     *
     * Get settings loaded from config/default.php and config/cipherguard.php in the CakePHP config.
     *
     * @return array
     */
    public function read()
    {
        $fileConfigs = Configure::read('cipherguard.email') ?? [];

        if (isset($fileConfigs['validate'])) {
            unset($fileConfigs['validate']);
        }

        return $fileConfigs;
    }
}
