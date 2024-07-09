<?php

namespace Inspector\CodeIgniter;

use CodeIgniter\Config\BaseConfig;
use Inspector\Configuration;
use Inspector\Exceptions\InspectorException;
use Inspector\Inspector as InspectorLibrary;
use Inspector\Models\Segment;
use Throwable;

/**
 * Allows developers to use the inspector library in CI4
 */
class Inspector extends InspectorLibrary
{
    /**
     * The latest version of the client library.
     *
     * @var string
     */
    public const VERSION = '0.3.4';

    private Segment $Segment;
    private array $ExceptionHandlers = [];
    private array $ErrorHandlers     = [];

    public static function getInstance(BaseConfig $config): Inspector
    {
        $requestURI = $_SERVER['REQUEST_URI'] ?? '';

        $configuration = (new Configuration($config->IngestionKey))
            ->setEnabled($config->Enable ?? true)
            ->setUrl($config->URL ?? 'https://ingest.inspector.dev')
            ->setVersion(self::VERSION)
            ->setTransport($config->Transport ?? 'async')
            ->setOptions($config->Options ?? [])
            ->setMaxItems($config->MaxItems ?? 100);

        $inspector = new self($configuration);

        // Only start a transation if AutoInspect is set to true
        if ($config->AutoInspect) {
            $pathInfo = explode('?', $requestURI);
            $path     = array_shift($pathInfo);

            $inspector->startTransaction($path)->markAsRequest();
        }

        return $inspector;
    }

    public function initialize(): void
    {
        $inspectorException = function (Throwable $ex) {
            $this->recordUnhandledException($ex);
        };

        if ($currentHandler = set_exception_handler($inspectorException)) {
            if (null !== $currentHandler) {
                $this->ExceptionHandlers[] = $currentHandler;
            } else {
                restore_exception_handler();
            }
        }

        $inspectorError = function ($num, $str, $file, $line) {
            $this->recordUnhandledError($num, $str, $file, $line);
        };

        if ($currentHandler = set_error_handler($inspectorError)) {
            if (null !== $currentHandler) {
                $this->ErrorHandlers[] = $currentHandler;
            } else {
                restore_error_handler();
            }
        }
    }

    public function recordUnhandledError($num, $str, $file, $line): void
    {
        try {
            // Throw a custom inspector codeigniter error so we can remort on the error
            throw new InspectorException("Uncaught Error: {$num}.\n Line: {$line}\n Error: {$str}\n In File: {$file}\n");
        } catch (Throwable $th) {
            $this->reportException($th);
        }

        foreach ($this->ErrorHandlers as $handler) {
            if (is_array($handler) && count($handler) >= 2) {
                $handler[0]->{$handler[1]}($num, $str, $file, $line);
            } else {
                $handler($num, $str, $file, $line);
            }
        }
    }

    public function recordUnhandledException(Throwable $exception): void
    {
        $this->reportException($exception);

        foreach ($this->ExceptionHandlers as $handler) {
            if (is_array($handler) && count($handler) >= 2) {
                $handler[0]->{$handler[1]}($exception);
            } else {
                $handler($exception);
            }
        }
    }

    public function hasSegment(): bool
    {
        return isset($this->Segment);
    }

    public function setSegment(Segment $segment): void
    {
        $this->Segment = $segment;
    }

    public function getSegment(): Segment
    {
        return $this->Segment;
    }
}
