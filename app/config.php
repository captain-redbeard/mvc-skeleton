<?php
/**
 * @author captain-redbeard
 * @since 05/02/17
 */

return [
    'app' => [
        'base_dir' =>           __DIR__,
        'timezone' =>           'UTC',
        'user_session' =>       'redbeard_user',
        'user_role' =>          10,
        'password_cost' =>      12,
        'max_login_attempts' => 5,
        'secure_cookies' =>     false,
        'token_expire_time' =>  900,
        'path' =>               '\\Redbeard\\',
        'default_controller' => 'Home',
        'default_method' =>     'index'
    ],
    
    'database' => [
        'rdbms' =>              'mysql',
        'hostname' =>           'localhost',
        'database' =>           'mvc-skeleton',
        'username' =>           '',
        'password' =>           '',
        'charset'  =>           'utf8mb4',
    ],
    
    'site' => [
        'name' =>               'Redbeards MVC Skeleton',
        'theme_color' =>        '4aa3df'
    ]
];
