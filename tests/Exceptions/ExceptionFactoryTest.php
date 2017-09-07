<?php namespace Arcanedev\LaravelTracker\Tests\Exceptions;

use Arcanedev\LaravelTracker\Exceptions\ExceptionFactory;
use Arcanedev\LaravelTracker\Exceptions\Errors;
use Arcanedev\LaravelTracker\Tests\TestCase;

/**
 * Class     ExceptionFactoryTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExceptionFactoryTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_make()
    {
        $exceptions = $this->getSupportedErrors();

        foreach ($exceptions as $code => $class) {
            $exception = ExceptionFactory::make($code, 'This is a message');

            $this->assertInstanceOf($class, $exception);
        }
    }

    /** @test */
    public function it_can_make_default_if_not_supported()
    {
        $this->assertInstanceOf(
            Errors\Error::class,
            ExceptionFactory::make(600, 'Kabooom!!')
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the supported error exceptions.
     *
     * @return array
     */
    private function getSupportedErrors()
    {
        return [
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
    }
}
