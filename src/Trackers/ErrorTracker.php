<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\ErrorTracker as ErrorTrackerContract;
use Arcanedev\LaravelTracker\Models\Error;
use Exception;

/**
 * Class     ErrorTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ErrorTracker extends AbstractTracker implements ErrorTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the exception error.
     *
     * @param  \Exception  $exception
     *
     * @return int
     */
    public function track(Exception $exception)
    {
        $data = [
            'code'    => $this->getCode($exception),
            'message' => $exception->getMessage(),
        ];

        return Error::firstOrCreate($data)->id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the code from the exception.
     *
     * @param  \Exception  $exception
     *
     * @return int|mixed|null
     */
    public function getCode(Exception $exception)
    {
        if (method_exists($exception, 'getCode') && $code = $exception->getCode())
            return $code;

        if (method_exists($exception, 'getStatusCode') && $code = $exception->getStatusCode())
            return $code;

        return null;
    }
}
