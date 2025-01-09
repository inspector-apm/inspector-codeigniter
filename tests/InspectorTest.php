<?php

namespace Inspector\CodeIgniter\Tests;

use Inspector\Inspector;
use Inspector\CodeIgniter\Tests\Support\TestCase;

/**
 * @internal
 */
final class InspectorTest extends TestCase
{
    public function testConfigLoad()
    {
        $this->assertInstanceOf(\Inspector\CodeIgniter\Config\Inspector::class, config('inspector'));
    }

    public function testServiceLoad()
    {
        $this->assertInstanceOf(Inspector::class, service('inspector'));
    }
}
