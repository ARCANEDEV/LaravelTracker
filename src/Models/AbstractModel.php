<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\Support\Bases\Model;

/**
 * Class     AbstractModel
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int  id
 *
 * @method  static  \Arcanedev\LaravelTracker\Models\AbstractModel  firstOrCreate(array $attributes, array $values = [])
 */
abstract class AbstractModel extends Model
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const MODEL_AGENT                = 'agent';
    const MODEL_COOKIE               = 'cookie';
    const MODEL_DEVICE               = 'device';
    const MODEL_DOMAIN               = 'domain';
    const MODEL_ERROR                = 'error';
    const MODEL_GEOIP                = 'geoip';
    const MODEL_LANGUAGE             = 'language';
    const MODEL_PATH                 = 'path';
    const MODEL_QUERY                = 'query';
    const MODEL_REFERER              = 'referer';
    const MODEL_REFERER_SEARCH_TERM  = 'referer-search-term';
    const MODEL_ROUTE                = 'route';
    const MODEL_ROUTE_PATH           = 'route-path';
    const MODEL_ROUTE_PATH_PARAMETER = 'route-path-parameter';
    const MODEL_SESSION              = 'session';
    const MODEL_SESSION_ACTIVITY     = 'session-activity';
    const MODEL_USER                 = 'user';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setConnection($this->getConfig('database.connection', null))
             ->setPrefix($this->getConfig('database.prefix', 'tracker_'));

        parent::__construct($attributes);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
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
        return config("laravel-tracker.$key", $default);
    }

    /**
     * Get the model class.
     *
     * @param  string       $name
     * @param  string|null  $default
     *
     * @return string
     */
    protected function getModelClass($name, $default = null)
    {
        return $this->getConfig("models.$name", $default);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Laravel 5.2 & 5.1 Support
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (version_compare(app()->version(), '5.3.0', '<') && in_array($method, ['firstOrCreate']))
            return call_user_func_array([$this, 'custom'.ucfirst($method)], $parameters);

        return parent::__call($method, $parameters);
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     *
     * @return self
     */
    public function customFirstOrCreate(array $attributes, array $values = [])
    {
        $instance = $this->newInstance();

        foreach ($attributes as $key => $value) {
            $instance = $instance->where($key, $value);
        }

        if ( ! is_null($first = $instance->first())) return $first;

        $instance = $this->newInstance($attributes + $values);
        $instance->save();

        return $instance;
    }
}
