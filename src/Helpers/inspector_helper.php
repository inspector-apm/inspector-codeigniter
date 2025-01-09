<?php

if (! function_exists('inspector')) {
    /**
     * Provides a convenience interface to the Inspector service.
     *
     * @return \Inspector\Inspector
     */
    function inspector(): \Inspector\Inspector
    {
        return service('inspector');
    }
}
