<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker as CookieTrackerContract;
use Arcanedev\LaravelTracker\Models\Cookie;
use Ramsey\Uuid\Uuid;

/**
 * Class     CookieTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CookieTracker extends AbstractTracker implements CookieTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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

        return Cookie::firstOrCreate(['uuid' => $cookie])->id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the cookie instance.
     *
     * @return \Illuminate\Cookie\CookieJar
     */
    private function cookie()
    {
        return $this->make(\Illuminate\Contracts\Cookie\QueueingFactory::class);
    }
}
