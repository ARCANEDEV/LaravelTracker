<?php

return [
    /* ------------------------------------------------------------------------------------------------
     |  Enabled
     | ------------------------------------------------------------------------------------------------
     */
    'enabled' => false,

    /* ------------------------------------------------------------------------------------------------
     |  Database
     | ------------------------------------------------------------------------------------------------
     */
    'database' => [
        'connection' => null,

        'prefix'     => 'tracker_'
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Models
     | ------------------------------------------------------------------------------------------------
     */
    'models' => [
        'agent'    => \Arcanedev\LaravelTracker\Models\Agent::class,
        'cookie'   => \Arcanedev\LaravelTracker\Models\Cookie::class,
        'device'   => \Arcanedev\LaravelTracker\Models\Device::class,
        'domain'   => \Arcanedev\LaravelTracker\Models\Domain::class,
        'geoip'    => \Arcanedev\LaravelTracker\Models\GeoIp::class,
        'language' => \Arcanedev\LaravelTracker\Models\Language::class,
        'referrer' => \Arcanedev\LaravelTracker\Models\Referrer::class,
        'session'  => \Arcanedev\LaravelTracker\Models\Session::class,
        'user'     => \Illuminate\Foundation\Auth\User::class,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Tracking
     | ------------------------------------------------------------------------------------------------
     */
    'tracking' => [
        'devices' => true,
        'users'   => true,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Auth
     | ------------------------------------------------------------------------------------------------
     */
    'auth' => [
        'bindings' => ['auth'],

        'methods'  => [
            'check' => 'check', // The auth `check` method
            'user'  => 'user',  // The auth `user` method
        ],

        'columns'  => [
            'id'     => 'id',
            'custom' => 'email',
        ],
    ],
];
