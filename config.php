<?php


return [
    'database' => [
        'servername' => '127.0.0.1',
        'name' => 'currency_convert_dbase',
        'username' => 'root',
        'password' => 'root',
        'port' => '8889',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
    'services' => [
        'fixer_key' => '0e77876b45de9d664b47cb2d1783a83f', // access key from the https://fixer.io/​ 
    ]
];