<?php

namespace Inspector\CodeIgniter\Config;

use Inspector\CodeIgniter\Filters\WebRequestMonitoringFilter;

class Registrar
{
    /**
     * Registers the Inspector filters.
     */
    public static function Filters(): array
    {
        return [
            'aliases' => [
                'inspector' => WebRequestMonitoringFilter::class,
            ],
            
            'globals' => [
                'before' => ['inspector'],
                'after' => ['inspector'],
            ]
        ];
    }
}