<?php

namespace Inspector\CodeIgniter\Tests;

use Inspector\CodeIgniter\Tests\Support\TestCase;
use Inspector\Inspector;

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
