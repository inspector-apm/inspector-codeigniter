<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Config\BaseConfig;

class Inspector extends BaseConfig
{
    /**
     * Application ingestion key, you can find this on your inspector dashboard
     *
     * @var string
     */
    public $ingestionKey = '';

    /**
     * Master switch.
     *
     * @var bool
     */
    public $enable = true;

    /**
     * Current version of the package.
     *
     * @var string
     */
    public $version = '0.5.2';

    /**
     * Remote endpoint to send data.
     *
     * @var string
     */
    public $url = 'https://ingest.inspector.dev';

    /**
     * Determine if Inspector should monitor database interactions.
     *
     * @var bool
     */
    public $DBQuery = false;

    /**
     * The strategy to send data.
     *
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
