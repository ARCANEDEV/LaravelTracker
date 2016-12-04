<?php namespace Arcanedev\LaravelTracker\Detectors;

use Arcanedev\LaravelTracker\Contracts\Detectors\UserDetector as UserDetectorContract;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class     UserDetector
 *
 * @package  Arcanedev\LaravelTracker\Detectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserDetector implements UserDetectorContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Illuminate\Contracts\Foundation\Application */
    private $app;

    /** @var array */
    private $auths = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * UserDetector constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->instantiateAuthentication();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the config instance.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    private function config()
    {
        return $this->app['config'];
    }

    /**
     * Get the tracker config.
     *
     * @param  string      $key
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return $this->config()->get("laravel-tracker.$key", $default);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return $this->executeAuthMethod($this->getConfig('auth.methods.check'));
    }

    /**
     * Get the authenticated user.
     *
     * @return false|mixed
     */
    public function user()
    {
        return $this->executeAuthMethod($this->getConfig('auth.methods.user'));
    }

    /**
     * Get the current authenticated user id.
     *
     * @return int|null
     */
    public function getCurrentUserId()
    {
        return $this->check()
            ? $this->user()->{$this->getConfig('auth.columns.id', 'id')}
            : null;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Grab all the authentication bindings.
     */
    private function instantiateAuthentication()
    {
        foreach ((array) $this->getConfig('auth.bindings', []) as $binding) {
            $this->auths[] = $this->app->make($binding);
        }
    }

    /**
     * Execute the auth method.
     *
     * @param  string  $method
     *
     * @return mixed|false
     */
    private function executeAuthMethod($method)
    {
        foreach ($this->auths as $auth) {
            if (is_callable([$auth, $method], true)) {
                if ($data = $auth->{$method}()) return $data;
            }
        }

        return false;
    }
}
