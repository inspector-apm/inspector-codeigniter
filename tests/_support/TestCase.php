<?php

namespace Inspector\CodeIgniter\Tests\Support;

use Inspector\CodeIgniter\Inspector;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

abstract class TestCase extends CIUnitTestCase
{
    /**
     * @var Inspector
     */
    protected $inspector;

    /**
     * Sets up the ArrayHandler for faster & easier tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config           = config('Inspector');
        $this->inspector  = Inspector::getInstance($config);

        Services::injectMock('inspector', $this->inspector);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->resetServices();
    }
}
