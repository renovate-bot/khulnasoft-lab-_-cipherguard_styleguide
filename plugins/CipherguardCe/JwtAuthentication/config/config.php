<?php
return [
    'cipherguard' => [
        'plugins' => [
            'jwtAuthentication' => [
                'version' => '3.3.0',
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'enabled', // see. config/default.php
                    ],
                ],
            ],
        ],
    ],
];
