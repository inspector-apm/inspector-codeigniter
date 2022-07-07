<?php

namespace Inspector\CodeIgniter\Tests\Support\Config;

use CodeIgniter\Config\BaseConfig;

/**
 * @internal
 */
final class Inspector extends BaseConfig
{
    public $AutoInspect  = true;
    public $IngestionKey = 'test_key_invalid_on_purpose';
}
