<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Database\Query;
use CodeIgniter\Events\Events;

helper('inspector');

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function () {
    /*$exceptions = service('exceptions');

    // Store the original exception handler
    $originalHandler = set_exception_handler(function (\Throwable $e) use ($exceptions) {
        // Your custom handling here
        inspector()->reportException($e);

        // Call the original handler
        if (is_callable($exceptions->exceptionHandler)) {
            call_user_func($exceptions->exceptionHandler, $e);
        }
    });

    // Store the original handler
    if ($originalHandler) {
        $exceptions->exceptionHandler = $originalHandler;
    }*/
});

Events::on('post_system', static function () {
});

Events::on('post_controller_constructor', static function () {
});

/**
 * Console Command
 */
Events::on('pre_command', static function () {
});

Events::on('post_command', static function () {
});

/**
 * Database Query
 */
if (config('Inspector')->DBQuery) {
    Events::on('DBQuery', static function (Query $query) {
        if (inspector()->canAddSegments()) {
            inspector()->startSegment('query', $query->getOriginalQuery())
                ->addContext('Db', ['sql' => $query->getQuery()])
                ->end($query->getDuration() * 1000);
        }
    });
}

