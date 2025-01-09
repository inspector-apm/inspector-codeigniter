<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Config\BaseConfig;

class Inspector extends BaseConfig
{
    /**
     * application ingestion key, you can find this on your inspector dashboard
     *
     * @var string
     */
    public $ingestionKey = '';

    /**
     * @var bool
     */
    public $enable = true;

    /**
     * Remote endpoint to send data.
     *
     * @var string
     */
    public $url = 'https://ingest.inspector.dev';

    /**
     * set this option to true if you want your application to send long running queries
     * and query errors to the inspector dashboard. Default is false for backward compatibility.
     *
     * @var bool
     */
    public $DBQuery = false;

    /**
     * @var string
     */
    public $transport = 'async';

    /**
     * Transport options.
     *
     * @var array
     */
    public $options = [];

    /**
     * Max numbers of items to collect in a single session.
     *
     * @var int
     */
    public $maxItems = 100;
}
