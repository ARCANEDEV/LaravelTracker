<?php namespace Arcanedev\LaravelTracker\Exceptions;

/**
 * Class     ExceptionFactory
 *
 * @package  Arcanedev\LaravelTracker\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExceptionFactory
{
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
        switch ($errorCode) {
            case E_ERROR:             return new Errors\Error($errorMessage, $errorCode);
            case E_WARNING:           return new Errors\Warning($errorMessage, $errorCode);
            case E_PARSE:             return new Errors\Parse($errorMessage, $errorCode);
            case E_NOTICE:            return new Errors\Notice($errorMessage, $errorCode);
            case E_CORE_ERROR:        return new Errors\CoreError($errorMessage, $errorCode);
            case E_CORE_WARNING:      return new Errors\CoreWarning($errorMessage, $errorCode);
            case E_COMPILE_ERROR:     return new Errors\CompileError($errorMessage, $errorCode);
            case E_COMPILE_WARNING:   return new Errors\CompileWarning($errorMessage, $errorCode);
            case E_USER_ERROR:        return new Errors\UserError($errorMessage, $errorCode);
            case E_USER_WARNING:      return new Errors\UserWarning($errorMessage, $errorCode);
            case E_USER_NOTICE:       return new Errors\UserNotice($errorMessage, $errorCode);
            case E_STRICT:            return new Errors\Strict($errorMessage, $errorCode);
            case E_RECOVERABLE_ERROR: return new Errors\RecoverableError($errorMessage, $errorCode);
            case E_DEPRECATED:        return new Errors\Deprecated($errorMessage, $errorCode);
            case E_USER_DEPRECATED:   return new Errors\UserDeprecated($errorMessage, $errorCode);
        }

        return new Errors\Error($errorMessage, $errorCode);
    }
}
