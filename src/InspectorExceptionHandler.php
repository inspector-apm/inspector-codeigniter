<?php

namespace Inspector\CodeIgniter;

use Throwable;

class InspectorExceptionHandler
{
    public static function handle(int $statusCode, Throwable $exception)
    {
        try {
            service('inspector')->reportException($exception, false);
            service('inspector')->transaction()->setResult($statusCode);
        } catch (Throwable $e) {
        }
    }
}
