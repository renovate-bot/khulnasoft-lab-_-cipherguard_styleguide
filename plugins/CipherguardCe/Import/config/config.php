<?php
return [
    'cipherguard' => [
        'plugins' => [
            'import' => [
                'settingsVisibility' => [
                    'whiteList' => [
                        'config.format',
                    ],
                ],
                'version' => '2.0.1',
                'config' => [
                    'format' => [
                        'kdbx',
                        'csv',
                    ],
                ],
            ],
        ],
    ],
];
