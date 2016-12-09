<?php namespace Arcanedev\LaravelTracker\Exceptions;

use Illuminate\Support\Arr;

/**
 * Class     ExceptionFactory
 *
 * @package  Arcanedev\LaravelTracker\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExceptionFactory
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Supported exceptions.
     *
     * @var array
     */
    protected static $supported = [
        E_ERROR             => Errors\Error::class,
        E_WARNING           => Errors\Warning::class,
        E_PARSE             => Errors\Parse::class,
        E_NOTICE            => Errors\Notice::class,
        E_CORE_ERROR        => Errors\CoreError::class,
        E_CORE_WARNING      => Errors\CoreWarning::class,
        E_COMPILE_ERROR     => Errors\CompileError::class,
        E_COMPILE_WARNING   => Errors\CompileWarning::class,
        E_USER_ERROR        => Errors\UserError::class,
        E_USER_WARNING      => Errors\UserWarning::class,
        E_USER_NOTICE       => Errors\UserNotice::class,
        E_STRICT            => Errors\Strict::class,
        E_RECOVERABLE_ERROR => Errors\RecoverableError::class,
        E_DEPRECATED        => Errors\Deprecated::class,
        E_USER_DEPRECATED   => Errors\UserDeprecated::class,
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make exception.
     *
     * @param  int     $errorCode
     * @param  string  $errorMessage
     *
     * @return mixed
     */
    public static function make($errorCode, $errorMessage)
    {
        $exception = Arr::get(self::$supported, $errorCode, Errors\Error::class);

        return new $exception($errorMessage, $errorCode);
    }
}
