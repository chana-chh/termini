<?php

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'renderer' => [
            'template_path' => DIR . 'app' . DS . 'views',
            'cache_path' => false,
        ],
        'chasha_app_settings' => [
            'db' => [
                'dsn' => 'mysql:host=127.0.0.1;dbname=termini;charset=utf8mb4',
                'username' => 'root',
                'password' => '',
                'options' => [
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                ],
            ],
            'cyrillic' => false,
            'pagination' => [
                'per_page' => 10,
                'page_span' => 4,
            ],
            'mail' => [
                'host' => 'mail.eeckg.rs',
                'username' => 'kure@eeckg.rs',
                'password' => 'vir5373plus!',
                'port' => 465, // 465 = ssl, 587 = tls
                'from' => 'kure@eeckg.rs',
                'from_name' => 'EEC zakazivanje',
            ],
        ]
    ],
];
