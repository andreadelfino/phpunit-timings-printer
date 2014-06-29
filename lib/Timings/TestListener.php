<?php

namespace Dolphin\PHPUnit\Timings;

use Exception;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_TestSuite;

/**
 * @package Dolphin\PHPUnit\Timings
 * @author Andrea Delfino <andrea.delfino@gmail.com>
 */
class TestListener implements PHPUnit_Framework_TestListener
{
    /**
     * @var float
     */
    protected $threshold;

    /**
     * @var bool
     */
    protected $verbose;

    /**
     * @param float $threshold
     * @param bool  $verbose
     */
    public function __construct($threshold = 1.0, $verbose = false)
    {
        $this->threshold = (double) $threshold ?: 1.0;
        $this->verbose = (bool) $verbose;
    }

    /**
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $test->timeThresholdExceeded = false;
        $test->timeThresholdVerbose = $this->verbose;
    }

    /**
     * @param PHPUnit_Framework_Test $test
     *                                     @param $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        if ($time > $this->threshold) {
            $test->timeThresholdExceeded = true;
        }
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     *                                     @param $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @param PHPUnit_Framework_Test                 $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     *                                                     @param $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     *                                     @param $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     *                                     @param $time
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * Risky test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     *                                     @since Method available since Release 4.0.0
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }
}
