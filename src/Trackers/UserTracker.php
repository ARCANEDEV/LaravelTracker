<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\UserTracker as UserTrackerContract;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class     UserTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserTracker extends AbstractTracker implements UserTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    private $auths = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * UserTracker constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->instantiateAuthentication();
    }

    /**
     * Grab all the authentication bindings.
     */
    private function instantiateAuthentication()
    {
        foreach ((array) $this->getConfig('auth.bindings', []) as $binding) {
            $this->auths[] = $this->make($binding);
        }
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
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
