<?php

namespace Inspector\CodeIgniter;

use CodeIgniter\Config\BaseConfig;
use Inspector\Configuration;
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
    public const VERSION = '0.2.2';

    private Segment $Segment;
    private array $PreviousHandlers = [];

    public static function getInstance(BaseConfig $config)
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

            $inspector->startTransaction($path);
        }

        return $inspector;
    }

    public function initialize()
    {
        $inspectorException = function (Throwable $ex) {
            $this->recordUnhandledException($ex);
        };

        if ($currentHandler = set_exception_handler($inspectorException)) {
            if (is_array($currentHandler) && $currentHandler[0] instanceof \CodeIgniter\Debug\Exceptions) {
                $this->PreviousHandlers[] = $currentHandler;
            } else {
                restore_exception_handler();
            }
        }
    }

    public function recordUnhandledException(Throwable $exception)
    {
        $this->reportException($exception);

        foreach ($this->PreviousHandlers as $handler) {
            if (count($handler) >= 2) {
                $handler[0]->{$handler[1]}($exception);
            } else {
                $handler($exception);
            }
        }
    }

    public function setSegment(Segment $segment)
    {
        $this->Segment = $segment;
    }

    public function getSegment()
    {
        return $this->Segment;
    }
}
