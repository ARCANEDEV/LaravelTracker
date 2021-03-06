<?php namespace Arcanedev\LaravelTracker\Trackers;

use Illuminate\Contracts\Foundation\Application;

/**
 * Class     AbstractTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractTracker
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Illuminate\Contracts\Foundation\Application */
    private $app;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * AbstractTracker constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /* -----------------------------------------------------------------
     |  Common Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the config instance.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    protected function config()
    {
        return $this->make('config');
    }

    /**
     * Get the session store instance.
     *
     * @return \Illuminate\Session\Store
     */
    protected function session()
    {
        return $this->make('session.store');
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

    /**
     * Make the model.
     *
     * @param  string  $name
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    protected function makeModel($name)
    {
        return $this->make(
            $this->getConfig("models.$name")
        );
    }

    /**
     * Make/Get the instance from the app.
     *
     * @param  string  $abstract
     *
     * @return mixed
     */
    protected function make($abstract)
    {
        return $this->app->make($abstract);
    }
}
