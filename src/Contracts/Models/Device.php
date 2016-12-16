<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  Device
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Device
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const KIND_COMPUTER    = 'computer';
    const KIND_PHONE       = 'phone';
    const KIND_TABLET      = 'tablet';
    const KIND_UNAVAILABLE = 'unavailable';

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Is this a computer?
     *
     * @return bool
     */
    public function isComputer();

    /**
     * Is this a phone?
     *
     * @return bool
     */
    public function isPhone();

    /**
     * Is this a tablet?
     *
     * @return bool
     */
    public function isTablet();
}
