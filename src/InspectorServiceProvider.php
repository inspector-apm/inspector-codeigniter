<?php

namespace Inspector\CodeIgniter;

use CodeIgniter\Config\BaseService;
use Inspector\CodeIgniter\Filters\WebRequestMonitoringFilter;

class InspectorServiceProvider extends BaseService
{
    public static function register()
    {
        $filters = config('Filters');

        // Register your filter alias
        $filters->aliases['inspector'] = WebRequestMonitoringFilter::class;
        $filters->globals['before'][] = 'inspector';
        $filters->globals['after'][] = 'inspector';

        dd($filters);
    }
}
