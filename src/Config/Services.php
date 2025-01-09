<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Config\BaseService;
use Inspector\Configuration;
use Inspector\Inspector;

class Services extends BaseService
{
    /**
     * Current version of the package.
     */
    const VERSION = '0.5.4';

    /**
     * Returns the Inspector manager class.
     */
    public static function inspector(bool $getShared = true): Inspector
    {
        if ($getShared) {
            return static::getSharedInstance('inspector');
        }

        $config = config('Inspector');

        $configuration = (new Configuration($config->ingestionKey))
            ->setEnabled($config->enabled)
            ->setUrl($config->url)
            ->setVersion(self::VERSION)
            ->setTransport($config->transport)
            ->setOptions($config->options)
            ->setMaxItems($config->maxItems);

        return new Inspector($configuration);
    }
}
