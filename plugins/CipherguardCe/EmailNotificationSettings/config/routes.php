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
 * @since         2.10.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Cipherguard/EmailNotificationSettings', ['path' => '/settings'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    /**
     * Get notification settings page
     */
    $routes->connect('/emails/notifications', [
            'prefix' => 'NotificationOrgSettings', 'controller' => 'NotificationOrgSettingsGet', 'action' => 'get',
        ])
        ->setMethods(['GET']);

    /**
     * Update notification settings page
     */
    $routes->connect('/emails/notifications', [
            'prefix' => 'NotificationOrgSettings', 'controller' => 'NotificationOrgSettingsPost', 'action' => 'post',
        ])
        ->setMethods(['POST']);
});
