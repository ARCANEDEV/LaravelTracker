<?php namespace Arcanedev\LaravelTracker\Exceptions;

use Arcanedev\LaravelTracker\Contracts\Tracker;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

/**
 * Class     Handler
 *
 * @package  Arcanedev\LaravelTracker\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Handler implements ExceptionHandlerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Tracker */
    private $manager;

    /** @var  \Illuminate\Contracts\Debug\ExceptionHandler */
    private $illuminateHandler;

    private $originalExceptionHandler;

    private $originalErrorHandler;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handler constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Tracker   $tracker
     * @param  \Illuminate\Contracts\Debug\ExceptionHandler  $illuminateHandler
     */
    public function __construct(Tracker $tracker, ExceptionHandlerContract $illuminateHandler)
    {
        $this->manager                  = $tracker;
        $this->illuminateHandler        = $illuminateHandler;
        $this->originalExceptionHandler = set_exception_handler([$this, 'trackException']);
        $this->originalErrorHandler     = set_error_handler([$this, 'handleError']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     */
    public function report(Exception $e)
    {
        try {
            $this->manager->trackException($e);
        }
        catch (Exception $exception) {
            // ignore
        }

        $this->illuminateHandler->report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception                $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        return $this->illuminateHandler->render($request, $e);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception                                         $e
     */
    public function renderForConsole($output, Exception $e)
    {
        $this->illuminateHandler->renderForConsole($output, $e);
    }

    /**
     * Track the exception.
     *
     * @param  Exception  $exception
     *
     * @return mixed
     */
    public function trackException(Exception $exception)
    {
        try {
            $this->manager->trackException($exception);
        }
        catch (Exception $e) {
            // Ignore Tracker exceptions
        }

        // Call Laravel Exception Handler
        return call_user_func($this->originalExceptionHandler, $exception);
    }

    /**
     * Handle the error.
     *
     * @param  int     $err_severity
     * @param  string  $err_msg
     * @param  mixed   $err_file
     * @param  mixed   $err_line
     * @param  array   $err_context
     *
     * @return mixed
     */
    public function handleError($err_severity, $err_msg, $err_file, $err_line, array $err_context)
    {
        try {
            $this->manager->trackException(
                ExceptionFactory::make($err_severity, $err_msg)
            );
        }
        catch (Exception $e) {
            // Ignore Tracker exceptions
        }

        // Call Laravel Exception Handler
        return call_user_func($this->originalErrorHandler, $err_severity, $err_msg, $err_file, $err_line);
    }
}
