<?php

namespace Inspector\CodeIgniter;

use Inspector\Inspector as InspectorLibrary;
use Inspector\Configuration;
use Inspector\Models\Segment;

use CodeIgniter\Config\BaseConfig;

use InvalidArgumentException;
use RuntimeException;

/**
 * Allows developers to use the inspector library in CI4
 */
class Inspector extends InspectorLibrary
{
    private Segment $Segment;

    public static function getInstance(BaseConfig $config)
    {
        $requestURI     = $_SERVER["REQUEST_URI"] ?? '';

        $configuration  = new Configuration($config->IngestionKey);
        $inspector      = new self($configuration);

        /* Only start a transation if AutoInspect is set to true */
        if($config->AutoInspect) {
            $pathInfo = explode('?', $requestURI);
            $path     = array_shift($pathInfo);

            $inspector->startTransaction($path);
        }

        return $inspector;
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
