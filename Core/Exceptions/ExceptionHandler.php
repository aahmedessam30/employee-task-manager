<?php

namespace Core\Exceptions;

use Core\Session\Session;
use Error;
use Exception;
use Core\Validation\ValidationException;
use Throwable;

class ExceptionHandler
{
    public static function handle(Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return self::handleValidationException($exception);
        } elseif ($exception instanceof Exception) {
            self::handleException($exception);
        } elseif ($exception instanceof Error) {
            self::handleError($exception);
        } else {
            self::handleThrowable($exception);
        }

        return null;
    }

    protected static function handleError(Error $error): void
    {
        echo "<br>";
        echo "<b>An error occurred:</b> " . $error->getMessage() . "<br>";
        echo "In file: " . $error->getFile() . " on line " . $error->getLine() . "<br>";
        exit;
    }

    protected static function handleThrowable(Throwable $exception): void
    {
        echo "<br>";
        echo "<b>An error occurred:</b> " . $exception->getMessage() . "<br>";
        echo "In file: " . $exception->getFile() . " on line " . $exception->getLine() . "<br>";
        exit;
    }

    protected static function handleException(Exception $exception): void
    {
        echo "<br>";
        echo "<b>An error occurred:</b> " . $exception->getMessage() . "<br>";
        echo "In file: " . $exception->getFile() . " on line " . $exception->getLine() . "<br>";
        exit;
    }

    protected static function handleValidationException(ValidationException $exception)
    {
        if (!(request()->isAjax() || request()->expectsJson())) {
            Session::flash('errors', $exception->errors());
            Session::flash('old', request()->all());
            back();
        }

        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => $exception->errors()
        ]);
    }
}
