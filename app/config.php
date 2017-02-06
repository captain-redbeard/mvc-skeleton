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
        'password_cost' =>      12,
        'max_login_attempts' => 5,
        'secure_cookies' =>     true,
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
