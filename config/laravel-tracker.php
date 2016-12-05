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
        'agent'               => \Arcanedev\LaravelTracker\Models\Agent::class,
        'cookie'              => \Arcanedev\LaravelTracker\Models\Cookie::class,
        'device'              => \Arcanedev\LaravelTracker\Models\Device::class,
        'domain'              => \Arcanedev\LaravelTracker\Models\Domain::class,
        'geoip'               => \Arcanedev\LaravelTracker\Models\GeoIp::class,
        'language'            => \Arcanedev\LaravelTracker\Models\Language::class,
        'referer'             => \Arcanedev\LaravelTracker\Models\Referer::class,
        'referer-search-term' => \Arcanedev\LaravelTracker\Models\RefererSearchTerm::class,
        'session'             => \Arcanedev\LaravelTracker\Models\Session::class,
        'user'                => \Illuminate\Foundation\Auth\User::class,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Tracking
     | ------------------------------------------------------------------------------------------------
     */
    'tracking' => [
        'cookies'     => true,
        'devices'     => true,
        'geoip'       => true,
        'languages'   => true,
        'referers'    => true,
        'users'       => true,
        'user-agents' => true,
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
        'name' => 'session_name_here'
    ],
];
