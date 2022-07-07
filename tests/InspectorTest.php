<?php

namespace Inspector\CodeIgniter\Tests;

use Inspector\CodeIgniter\Inspector;
use Inspector\Models\Segment;
use Config\Services;
use Inspector\CodeIgniter\Tests\Support\TestCase;

/**
 * @internal
 */
final class InspectorTest extends TestCase
{

    protected function simulateEventStart(): void
    {
        \CodeIgniter\Events\Events::trigger('post_controller_constructor');
    }

    protected function simulateEventEnd(): void
    {
        \CodeIgniter\Events\Events::trigger('post_system');
    }

    public function testAutoInspectStartsTransaction()
    {
        $config    = config('Inspector');
        $inspector = Inspector::getInstance($config);
        $result    = $inspector->hasTransaction();

        $this->assertSame(true, $result);
    }

    public function testSetSegmentIsCorrect()
    {
        Services::resetSingle('inspector');

        $this->simulateEventStart();

        $config    = config('Inspector');
        $inspector = service('inspector');

        $this->assertInstanceOf(Segment::class, $inspector->getSegment());

        $this->simulateEventEnd();
    }

    public function testGetSegmentReturnsValidSegmentInstance()
    {
        Services::resetSingle('inspector');

        $this->simulateEventStart();

        $config    = config('Inspector');
        $inspector = service('inspector');
        $result    = $inspector->getSegment();

        $this->assertInstanceOf(Segment::class, $result);

        $this->simulateEventEnd();
    }
}
