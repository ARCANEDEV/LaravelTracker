<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker as CookieTrackerContract;
use Arcanedev\LaravelTracker\Support\BindingManager;
use Illuminate\Contracts\Cookie\QueueingFactory;
use Ramsey\Uuid\Uuid;

/**
 * Class     CookieTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CookieTracker extends AbstractTracker implements CookieTrackerContract
{
    /* -----------------------------------------------------------------
     |  Getters and Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Cookie
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_COOKIE);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the cookie.
     *
     * @param  mixed  $cookie
     *
     * @return int
     */
    public function track($cookie)
    {
        if ( ! $cookie) {
            $this->cookie()->queue(
                $this->cookie()->make($this->getConfig('cookie.name'), $cookie = (string) Uuid::uuid4())
            );
        }

        return $this->getModel()
                    ->newQuery()
                    ->firstOrCreate(['uuid' => $cookie])
                    ->getKey();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the cookie instance.
     *
     * @return \Illuminate\Cookie\CookieJar
     */
    private function cookie()
    {
        return $this->make(QueueingFactory::class);
    }
}
