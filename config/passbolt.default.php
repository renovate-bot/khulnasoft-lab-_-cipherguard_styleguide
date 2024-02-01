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
 * @since         2.0.0
 */
/**
 * CIPHERGURD CONFIGURATION FILE TEMPLATE
 *
 * By default cipherguard tries to use the environment variables or falls back to the default values as
 * defined in default.php. You can use cipherguard.default.php as a basis to set your own configuration
 * without using environment variables.
 *
 * 1. copy/paste cipherguard.default.php to cipherguard.php
 * 2. set the variables in the App section
 * 3. set the variables in the cipherguard section
 *
 * To see all available options, you can refer to the default.php file, and modify passsbolt.php accordingly.
 * Do not modify default.php or you may break your upgrade process.
 *
 * Read more about how to install cipherguard: https://www.cipherguard.khulnasoft.com/help/tech/install
 * Any issue, check out our FAQ: https://www.cipherguard.khulnasoft.com/faq
 * An installation issue? Ask for help to the community: https://community.cipherguard.khulnasoft.com/
 */
return [

    /**
     * DEFAULT APP CONFIGURATION
     *
     * All the information in this section must be provided in order for cipherguard to work
     * This configuration overrides the CakePHP defaults located in app.php
     * Do not edit app.php as it may break your upgrade process
     */
    'App' => [
        // A base URL to use for absolute links.
        // The fully qualified domain name (including protocol) to your application’s root
        // e.g. where the cipherguard instance will be reachable to your end users.
        // This information is need to render images in emails for example.
        'fullBaseUrl' => 'https://www.cipherguard.test',
        // OPTIONAL
        // You can specify the base directory the app resides in.
        // Useful if you are running cipherguard in a subdirectory like example.com/cipherguard
        // Ensure your string starts with a / and does NOT end with a /
        // 'base' => '/subdir'
    ],

    // Database configuration.
    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'user',
            'password' => 'secret',
            'database' => 'cipherguard',
        ],
    ],

    // Email configuration.
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => 'user',
            'password' => 'secret',
            // Is this a secure connection? true if yes, null if no.
            'tls' => null,
            //'timeout' => 30,
            //'client' => null,
            //'url' => null,
        ],
    ],
    'Email' => [
        'default' => [
            // Defines the default name and email of the sender of the emails.
            'from' => ['cipherguard@your_organization.com' => 'Cipherguard'],
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
    ],

    /**
     * DEFAULT CIPHERGURD CONFIGURATION
     *
     * This is the default configuration.
     * It enforces the use of ssl, and does not provide a default OpenPGP key.
     * If your objective is to try cipherguard quickly for evaluation purpose, and security is not important
     * you can use the demo config example provided in the next section below.
     */
    'cipherguard' => [
        // GPG Configuration.
        // The keyring must to be owned and accessible by the webserver user.
        // Example: www-data user on Debian
        'gpg' => [
            // Tell GPG where to find the keyring.
            // If putenv is set to false, gnupg will use the default path ~/.gnupg.
            // For example :
            // - Apache on Centos it would be in '/usr/share/httpd/.gnupg'
            // - Apache on Debian it would be in '/var/www/.gnupg'
            // - Nginx on Centos it would be in '/var/lib/nginx/.gnupg'
            // - etc.
            //'keyring' => getenv("HOME") . DS . '.gnupg',
            //
            // Replace GNUPGHOME with above value even if it is set.
            //'putenv' => false,

            // Main server key.
            'serverKey' => [
                // Server private key fingerprint.
                'fingerprint' => '',
                //'public' => CONFIG . 'gpg' . DS . 'serverkey.asc',
                //'private' => CONFIG . 'gpg' . DS . 'serverkey_private.asc',
            ],
        ],
    ],

/**
 * DEMO CONFIGURATION EXAMPLE
 *
 * Uncomment the lines below if you want to try cipherguard quickly.
 * and if you are not concerned about the security of your installation.
 * (Don't forget to comment the default config above).
 */
//    'debug' => true,
//    'cipherguard' => [
//        'registration' => [
//            'public' => true
//        ],
//        'ssl' => [
//            'force' => false,
//        ],
//        'gpg' => [
//            'serverKey' => [
//                'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E',
//                'public' => CONFIG . DS . 'gpg' . DS . 'unsecure.key',
//                'private' => CONFIG . DS . 'gpg' . DS . 'unsecure_private.key',
//            ],
//        ],
//    ]

];
