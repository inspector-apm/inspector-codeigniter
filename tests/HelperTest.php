<?php

namespace Inspector\CodeIgniter\Tests;

use Inspector\Inspector;
use Inspector\CodeIgniter\Tests\Support\TestCase;

/**
 * @internal
 */
final class HelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        helper(['inspector']);
    }

    public function testReturnsServiceByDefault()
    {
        $this->assertInstanceOf(Inspector::class, inspector());
    }
}
