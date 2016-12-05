<?php namespace Arcanedev\LaravelTracker\Support;

use Illuminate\Support\Arr;

/**
 * Class     PhpSession
 *
 * @package  Arcanedev\LaravelTracker\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PhpSession
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const DEFAULT_NAMESPACE = 'arcanedev/phpsession';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $namespace;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * PhpSession constructor.
     * @param null $namespace
     */
    public function __construct($namespace = null)
    {
        $this->startSession();
        $this->setNamespace($namespace);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the session namespace.
     *
     * @param  string  $namespace
     */
    public function setNamespace($namespace)
    {
        if ($namespace) {
            $this->namespace = $namespace;
        }
    }

    /**
     * Get the session status.
     *
     * @return int
     */
    public function status()
    {
        return session_status();
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Start the session.
     */
    private function startSession()
    {
        if ( ! $this->isStarted())
            session_start();
    }

    /**
     * Get the session data.
     *
     * @param  string       $key
     * @param  string|null  $namespace
     *
     * @return mixed
     */
    public function get($key, $namespace = null)
    {
        return Arr::get($this->getNamespaceData($namespace), $key);
    }

    /**
     * Check if session has data.
     *
     * @param  string       $key
     * @param  string|null  $namespace
     *
     * @return bool
     */
    public function has($key, $namespace = null)
    {
        return Arr::has($this->getNamespaceData($namespace), $key);
    }

    /**
     * Add data to the session.
     *
     * @param  string       $key
     * @param  mixed        $value
     * @param  string|null  $namespace
     */
    public function put($key, $value, $namespace = null)
    {
        $this->setNamespaceData(
            $namespace,
            Arr::add($this->getNamespaceData($namespace), $key, $value)
        );
    }

    /**
     * Make the namespace for the session.
     *
     * @param  string|null  $namespace
     *
     * @return string
     */
    private function makeNamespace($namespace = null)
    {
        $namespace = $namespace ?: $this->namespace;

        return $namespace ?: self::DEFAULT_NAMESPACE;
    }

    /**
     * Get the session data by a namespace.
     *
     * @param  string  $namespace
     *
     * @return array
     */
    private function getNamespaceData($namespace)
    {
        return Arr::get($_SESSION, $this->makeNamespace($namespace), []);
    }

    /**
     * Set the session data by a namespace.
     *
     * @param  string  $namespace
     * @param  mixed   $value
     */
    private function setNamespaceData($namespace, $value)
    {
        $_SESSION[$this->makeNamespace($namespace)] = $value;
    }

    /**
     * Regenerate the session.
     *
     * @param  bool  $destroy
     *
     * @return string
     */
    public function regenerate($destroy = true)
    {
        session_regenerate_id($destroy);

        return $this->getId();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the session is started.
     *
     * @return bool
     */
    private function isStarted()
    {
        return $this->status() === PHP_SESSION_ACTIVE;
    }
}
