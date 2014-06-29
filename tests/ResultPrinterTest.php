<?php

namespace Dolphin\PHPUnit\Tests\Timings;

use Dolphin\PHPUnit\Timings\ResultPrinter;

/**
 * @package Dolphin\PHPUnit\Tests\Timings
 * @author Andrea Delfino <andrea.delfino@gmail.com>
 */
class ResultPrinterTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itShouldPrintDots()
    {
        $test = clone $this;
        $test->timeThresholdExceeded = false;
        $test->timeThresholdVerbose = false;

        $printer = new ResultPrinter();

        ob_start();
        $printer->startTest($test);
        $printer->endTest($test, 0.123);
        $out = ob_get_clean();

        $this->assertEquals('..', $out);
    }

    /** @test */
    public function itShouldPrintVerboseLine()
    {
        $test = clone $this;
        $test->timeThresholdExceeded = false;
        $test->timeThresholdVerbose = true;

        $printer = new ResultPrinter();

        ob_start();
        $printer->startTest($test);
        $printer->endTest($test, 0.54321);
        $out = ob_get_clean();

        $this->assertContains("[.]", $out);
        $this->assertContains(__METHOD__, $out);
        $this->assertContains("{0.543210}", $out);

        // Output needs to be cleaned from colors escapes
        // $this->assertContains(sprintf('[.] %s took ~0s {0.543210}', __METHOD__), $out);
    }

    /** @test */
    public function itShouldPrintTestResult()
    {
        $test = clone $this;
        $test->timeThresholdExceeded = true;
        $test->timeThresholdVerbose = false;

        $printer = new ResultPrinter();

        ob_start();
        $printer->startTest($test);
        $printer->endTest($test, 6.123);
        $printer->printResult($this->getMock('PHPUnit_Framework_TestResult'));
        $out = ob_get_clean();

        $this->assertContains(sprintf('6.12 > %s', __METHOD__), $out);
        $this->assertContains('6.123000s', $out);

        // Output needs to be cleaned from colors escapes
        // $this->assertContains(sprintf('1)    6.12 > %s', __METHOD__), $out);
        // $this->assertContains('# Wasted 6.123000s running 1 tests', $out);
    }
}
