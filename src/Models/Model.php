<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\Support\Bases\Model as BaseModel;

/**
 * Class     Model
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Model extends BaseModel
{
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
