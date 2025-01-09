<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\BaseService;
use Inspector\Configuration;
use Inspector\Inspector;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
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
            ->setVersion($config->version)
            ->setTransport($config->transport)
            ->setOptions($config->options)
            ->setMaxItems($config->maxItems);

        return new Inspector($configuration);
    }
}
