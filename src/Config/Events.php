<?php

namespace Inspector\CodeIgniter\Config;

use CodeIgniter\Events\Events;
use Inspector\Exceptions\InspectorException;
use Throwable;

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

$inspectorConfig = config('Inspector');

if(!isset($inspectorConfig)) {
    return;
}

if ($inspectorConfig->AutoInspect) {
    Events::on('post_controller_constructor', static function () {
        $router     = service('router');
        $controller = $router->controllerName();
        $method     = $router->methodName();
        $segment    = Services::inspector()->startSegment($controller, $method);
        Services::inspector()->setSegment($segment);
    });

    Events::on('post_system', static function () {
        $inspector = Services::inspector();
        if ($inspector->hasSegment()) {
            $inspector->getSegment()->end();
        }
    });
}

if ($inspectorConfig->LogQueries) {
    Events::on('DBQuery', static function ($query) {
        $inspector  = Services::inspector();
        $segment    = $inspector->startSegment('query', 'Running Queries');
        $upperBound = $query->getDuration() * 1000; // upper bound for the query in milliseconds
        // report all queries that take longer than a second
        if ($upperBound >= 1000) {
            try {
                throw new InspectorException("Query Took: {$upperBound} Input: " . $query->getOriginalQuery() . ' Output: ' . $query->getQuery());
            } catch (Throwable $th) {
                $inspector->reportException($th);
            }
        }
        // report queries with errors
        if ($query->hasError()) {
            try {
                throw new InspectorException('Query Error: ' . $query->getErrorCode() . ' Message: ' . $query->getErrorMessage());
            } catch (Throwable $th) {
                $inspector->reportException($th);
            }
        }
        $segment->end();
    });
}

if ($inspectorConfig->LogUnhandledExceptions) {
    Events::on('pre_system', static function () {
        Services::inspector()->initialize();
    });
}
