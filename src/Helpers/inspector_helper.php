<?php

use Inspector\CodeIgniter\Inspector;

if (! function_exists('inspector')) {
    /**
     * Provides a convenience interface to the Inspector service.
     *
     * @param mixed|null $value
     *
     * @return array|bool|float|int|object|Settings|string|void|null
     */
    function inspector(?callable $func = null, string $type = '', string $label = '')
    {
        /** @var Inspector $inspector */
        $inspector = service('inspector');

        if (empty($func) || count(func_get_args()) < 2) {
            return $inspector;
        }

        if (count(func_get_args()) >= 2) {
            return $inspector->addSegment($func, $type, $label);
        }
    }
}
