<?php

use App\User;

return [
    'user_model' => User::class,

    'name' => 'TwoFactorAuth',
   
    'migrate' => [
        // indicate table name
        'table' => 'users',

        // indicate field for login as username
        'username' => 'phone',

        // use to store personal access token for user
        'user_token_field' => 'access_token',

        /*******************************************************
         *
         * leave `user_token_expire_field` to null
         * it will be use `user_token_field` to suffi x `_expire`
         *
         *******************************************************/
        'user_token_expire_field' => null
    ],
    'two-factor-auth-core' => [
        // set window = 0 mean code invalid after 30s to match google authenticate app
        'window' => 0,
        // redirect to url after verify success
        'verify-login-redirect-to' => 'admin',
        // route to verify page
        'verify-page' => 'admin/verify',
        'verify-page-session' => 'pass_login_but_require_2fa',
        // this option `enable-logout-other-device` to true required to enable `AuthenticateSession` middleware
        'enable-logout-other-device' => true,
        // enable or disable auto generate route
        'route' => [
            // to disable web route set it to empty []
            // 'web' => route group []
            'web' => [
                'middleware' => ['web'],
            ],
            'api' => true
        ],

    ]
];
