<?php

return [
    'pdo' => [
        'driver' => 'mysql',
        'host' => 'database',
        'db_name' => 'bug-report',
        'db_username' => 'appuser',
        'db_user_password' => 'apppassword',
        'default_fetch' => PDO::FETCH_OBJ
    ],
    'mysqli' => [
        'host' => 'database',
        'db_name' => 'bug-report',
        'db_username' => 'appuser',
        'db_user_password' => 'apppassword',
        'default_fetch' => MYSQLI_ASSOC
    ]
];