<?php

namespace Inspector\CodeIgniter\Tests;

use Exception;
use Inspector\CodeIgniter\Inspector;
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

    public function testAddSegmentFromHelper()
    {
        $result = inspector(static function () {
            $data = [1, 2, 3];
            $res  = array_map(
                static fn ($el) => $el * 2,
                $data
            );

            return $res;
        }, 'test-helper');

        $this->assertSame([2, 4, 6], $result);
    }

    public function testThrownExceptions()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('A test exception');

        throw inspector(static function () {
            $msg = 'A test exception';

            return new Exception($msg);
        }, 'test-helper-exception', 'Test Helper Exception');
    }
}
