<?php

namespace Inspector\CodeIgniter;

use CodeIgniter\Debug\BaseExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class InspectorExceptionHandler extends BaseExceptionHandler implements ExceptionHandlerInterface
{
    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode
    ): void {
        try {
            service('inspector')->reportException($exception, false);
            service('inspector')->transaction()->setResult($statusCode);
        } catch (Throwable $e) {
        }
    }
}
