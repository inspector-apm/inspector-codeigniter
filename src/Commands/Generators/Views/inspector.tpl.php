<@php

namespace {namespace};

use CodeIgniter\Config\BaseConfig;

class {class} extends BaseConfig
{
  /**
     * set to true if you want all your controller methods to be 'auto-inspected'
     * set to false to set your own inspection points - provides more flexibility
     *
     * @var bool
     */
    public $AutoInspect  = true;

    /**
     * set this option to true if you want your application to send unhandled exceptions
     * to the inspector dashboard. Default is false for backward compatibility.
     * 
     * @var bool
     */
    public $LogUnhandledExceptions = false;

    /**
     * set this option to true if you want your application to send long running queries
     * and query errors to the inspector dashboard. Default is false for backward compatibility.
     * 
     * @var bool
     */
    public $LogQueries = false;
    
    /**
     * application ingestion key, you can find this on your inspector dashboard
     *
     * @var string
     */
    public $IngestionKey = '{key}';
    
    /**
     * @var bool
     */
    public $Enable = true;
    
    /**
     * Remote endpoint to send data.
     *
     * @var string
     */
    public $URL = 'https://ingest.inspector.dev';
    
    /**
     * @var string
     */
    public $Transport = 'async';
    
    /**
     * Transport options.
     *
     * @var array
     */
    public $Options = [];
    
    /**
     * Max numbers of items to collect in a single session.
     *
     * @var int
     */
    public $MaxItems = 100;
}

