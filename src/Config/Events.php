<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Database\Query;
use CodeIgniter\Events\Events;
use Inspector\CodeIgniter\Utils;
use Throwable;

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

/*Events::on('pre_system', static function () {
    $exceptions = service('exceptions');

    // Store the original exception handler
    $originalHandler = set_exception_handler(static function (Throwable $e) use ($exceptions) {
        // Report to Inspector
        inspector()->reportException($e);
        // todo: Find a way to report the status code
        inspector()->transaction()->setResult('error');

        // Call the original handler
        if (is_callable($exceptions->exceptionHandler)) {
            ($exceptions->exceptionHandler)($e);
        }
    });

    // Store the original handler
    if ($originalHandler) {
        $exceptions->exceptionHandler = $originalHandler;
    }
});

Events::on('post_system', static function () {
});

Events::on('post_controller_constructor', static function () {
});*/

/**
 * Console Command
 */
Events::on('pre_command', static function () {
    /*
     * Without a payload with information about the command that is being executed
     * we can only intercept the fist command launched by CLI.
     *
     * We cannot identify commands executed from other commands or from a controller.
     * They should be traced as segments inside the current transaction, but without a payload
     * we can't do that.
     */
    if (\PHP_SAPI === 'cli') {
        $args = \array_slice($_SERVER['argv'], 1);
        $name = \array_shift($args);

        if (\is_string($name)) {
            // Check if it is in the ignore list
            foreach (config('Inspector')->ignoreCommands??[] as $command) {
                if (Utils::matchWithWildcard($name, $command)) {
                    return;
                }
            }
            // Start the transaction otherwise
            inspector()
                ->startTransaction($name)
                ->setType('command');
        }
    }
});

Events::on('post_command', static function () {
    if (\PHP_SAPI === 'cli' && inspector()->hasTransaction()) {
        inspector()->transaction()->setResult('success');
    }
});

/**
 * Database Query
 */
if (config('Inspector')->DBQuery ?? false) {
    Events::on('DBQuery', static function (Query $query) {
        if (inspector()->canAddSegments()) {
            inspector()->startSegment('query', $query->getOriginalQuery())
                ->addContext('Db', ['sql' => $query->getQuery()])
                ->end($query->getDuration() * 1000);
        }
    });
}
