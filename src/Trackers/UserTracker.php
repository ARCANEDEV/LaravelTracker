<?php namespace Arcanedev\LaravelTracker\Trackers;

/**
 * Class     UserTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserTracker
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
     * UserTracker constructor.
     */
    public function __construct()
    {
        $this->app = app();

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
     * Track the current authenticated user id.
     *
     * @return int|null
     */
    public function track()
    {
        return $this->check()
            ? $this->user()->{$this->getConfig('auth.columns.id', 'id')}
            : null;
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    protected function check()
    {
        return $this->executeAuthMethod($this->getConfig('auth.methods.check'));
    }

    /**
     * Get the authenticated user.
     *
     * @return false|mixed
     */
    protected function user()
    {
        return $this->executeAuthMethod($this->getConfig('auth.methods.user'));
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
