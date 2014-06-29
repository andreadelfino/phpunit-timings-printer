<?php

namespace Dolphin\PHPUnit\Tests\Timings;

use Dolphin\PHPUnit\Timings\TestListener;

/**
 * @package Dolphin\PHPUnit\Tests\Timings
 * @author Andrea Delfino <andrea.delfino@gmail.com>
 */
class TestListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itShouldSetupTestInitialStatus()
    {
        $test = $this->getMock('PHPUnit_Framework_Test');

        $listener = new TestListener(1.0, true);
        $listener->startTest($test);

        $this->assertFalse($test->timeThresholdExceeded);
        $this->assertTrue($test->timeThresholdVerbose);
    }

    /** @test */
    public function itShouldPreserveThresholdStatus()
    {
        $test = $this->getMock('PHPUnit_Framework_Test');

        $listener = new TestListener(1.0);
        $listener->startTest($test);

        $this->assertFalse($test->timeThresholdExceeded);

        $listener->endTest($test, 0.5);

        $this->assertFalse($test->timeThresholdExceeded);
    }

    /** @test */
    public function itShouldDetectThresholdExcedeed()
    {
        $test = $this->getMock('PHPUnit_Framework_Test');

        $listener = new TestListener(1.0);
        $listener->startTest($test);

        $this->assertFalse($test->timeThresholdExceeded);

        $listener->endTest($test, 1.1);

        $this->assertTrue($test->timeThresholdExceeded);
    }
}
