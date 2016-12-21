<?php

use Arcanedev\LaravelTracker\Models;
use Arcanedev\LaravelTracker\Support\BindingManager;

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

        'prefix'     => 'tracker_',
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Models
     | ------------------------------------------------------------------------------------------------
     */
    'models' => [
        BindingManager::MODEL_AGENT                => Models\Agent::class,
        BindingManager::MODEL_COOKIE               => Models\Cookie::class,
        BindingManager::MODEL_DEVICE               => Models\Device::class,
        BindingManager::MODEL_DOMAIN               => Models\Domain::class,
        BindingManager::MODEL_ERROR                => Models\Error::class,
        BindingManager::MODEL_GEOIP                => Models\GeoIp::class,
        BindingManager::MODEL_LANGUAGE             => Models\Language::class,
        BindingManager::MODEL_PATH                 => Models\Path::class,
        BindingManager::MODEL_QUERY                => Models\Query::class,
        BindingManager::MODEL_REFERER              => Models\Referer::class,
        BindingManager::MODEL_REFERER_SEARCH_TERM  => Models\RefererSearchTerm::class,
        BindingManager::MODEL_ROUTE                => Models\Route::class,
        BindingManager::MODEL_ROUTE_PATH           => Models\RoutePath::class,
        BindingManager::MODEL_ROUTE_PATH_PARAMETER => Models\RoutePathParameter::class,
        BindingManager::MODEL_USER                 => Models\User::class,
        BindingManager::MODEL_VISITOR              => Models\Visitor::class,
        BindingManager::MODEL_VISITOR_ACTIVITY     => Models\VisitorActivity::class,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Tracking
     | ------------------------------------------------------------------------------------------------
     */
    'tracking' => [
        'cookies'      => true,
        'devices'      => true,
        'errors'       => true,
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
        /**
         * A cookie may be created on your visitor device, so you can have
         * information on everything made using that device on your site.
         */
        'name' => null
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
                // route names like 'blog.*'
            ],
            'uris'  => [
                // URIs like 'admin', 'admin/*' (both to ignore uri starting with `admin`)
            ],
        ],

        'model-columns' => ['id'],
    ],
];
