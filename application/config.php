<?php
/**
 * @author captain-redbeard
 * @since 05/02/17
 */

return [
    'app' => [
        'base_dir' =>               __DIR__,
        'config_directory' =>       __DIR__ . '/Config/',
        'models_directory' =>       __DIR__ . '/Models/',
        'views_directory' =>        __DIR__ . '/Views/',
        'controllers_directory' =>  __DIR__ . '/Controllers/',
        'models_path' =>            'Models\\',
        'views_path' =>             'Views\\',
        'controllers_path' =>       'Controllers\\',
        'system_path' =>            '\\Redbeard\\Crew\\',
        'path' =>                   '\\Demo\\',
        'default_controller' =>     'Home',
        'default_method' =>         'index'
        'timezone' =>               'UTC',
        'user_session' =>           'redbeard_user',
        'user_role' =>              10,
        'password_cost' =>          12,
        'max_login_attempts' =>     5,
        'secure_cookies' =>         false,
        'token_expire_time' =>      900
    ]
];
