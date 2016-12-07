<?php

return [
    /* ------------------------------------------------------------------------------------------------
     |  Enabled
     | ------------------------------------------------------------------------------------------------
     */
    'enabled' => true,

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
        'agent'                => \Arcanedev\LaravelTracker\Models\Agent::class,
        'cookie'               => \Arcanedev\LaravelTracker\Models\Cookie::class,
        'device'               => \Arcanedev\LaravelTracker\Models\Device::class,
        'domain'               => \Arcanedev\LaravelTracker\Models\Domain::class,
        'error'                => \Arcanedev\LaravelTracker\Models\Error::class,
        'geoip'                => \Arcanedev\LaravelTracker\Models\GeoIp::class,
        'language'             => \Arcanedev\LaravelTracker\Models\Language::class,
        'query'                => \Arcanedev\LaravelTracker\Models\Query::class,
        'referer'              => \Arcanedev\LaravelTracker\Models\Referer::class,
        'referer-search-term'  => \Arcanedev\LaravelTracker\Models\RefererSearchTerm::class,
        'route'                => \Arcanedev\LaravelTracker\Models\Route::class,
        'route-path'           => \Arcanedev\LaravelTracker\Models\RoutePath::class,
        'route-path-parameter' => \Arcanedev\LaravelTracker\Models\RoutePathParameter::class,
        'session'              => \Arcanedev\LaravelTracker\Models\Session::class,
        'session-activity'     => \Arcanedev\LaravelTracker\Models\SessionActivity::class,
        'user'                 => \Illuminate\Foundation\Auth\User::class,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Tracking
     | ------------------------------------------------------------------------------------------------
     */
    'tracking' => [
        'cookies'      => true,
        'devices'      => true,
        'geoip'        => true,
        'languages'    => true,
        'paths'        => true,
        'path-queries' => true,
        'referers'     => true,
        'users'        => true,
        'user-agents'  => true,
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

    /* ------------------------------------------------------------------------------------------------
     |  Cookie
     | ------------------------------------------------------------------------------------------------
     */
    'cookie' => [
        'name' => 'cookie_name_here'
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Session
     | ------------------------------------------------------------------------------------------------
     */
    'session' => [
        'name' => 'tracker_session'
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Routes
     | ------------------------------------------------------------------------------------------------
     */
    'routes' => [
        'ignore' => [
            'names' => [
                // route names
            ],
        ],

        'model-columns' => ['id'],
    ],
];
