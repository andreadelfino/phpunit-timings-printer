<?php

namespace Dolphin\PHPUnit\Timings;

use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestResult;
use PHPUnit_TextUI_ResultPrinter;

/**
 * @package Dolphin\PHPUnit\Timings
 * @author Andrea Delfino <andrea.delfino@gmail.com>
 */
class ResultPrinter extends PHPUnit_TextUI_ResultPrinter
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $change;

    /**
     * @var bool
     */
    private $verboseBackup;

    /**
     * @param null $out
     * @param bool $verbose
     * @param bool $colors
     * @param bool $debug
     */
    public function __construct($out = null, $verbose = false, $colors = false, $debug = false)
    {
        parent::__construct($out, $verbose, true, $debug);

        $this->data = array();

        $this->change = false;
        $this->verboseBackup = $verbose;
    }

    /**
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->verbose = $this->checkTimeThresholdVerbose($test);

        parent::startTest($test);
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->change = $this->checkTimeThresholdExceeded($test);

        parent::endTest($test, $time);

        $this->analyzeTimeThreshold($test, $time);

        $this->change = false;
        $this->verbose = $this->verboseBackup;
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     */
    protected function printFooter(PHPUnit_Framework_TestResult $result)
    {
        parent::printFooter($result);

        $this->printSlowestTen();
    }

    /**
     * @param $progress
     */
    protected function writeProgress($progress)
    {
        if ($this->verbose) {
            return $this->writeProgressWithSummary($progress);
        }

        parent::writeProgress($this->overrideTimeThresholdProgress($progress));
    }

    /**
     * @param $progress
     */
    protected function writeProgressWithSummary($progress)
    {
        $this->column++;
        $this->numTestsRun++;

        $numberMask = sprintf('%%%sd', $this->numTestsWidth);
        $percentMask = '%3s';

        if ($this->change) {
            $numberMask = $this->highlight($numberMask);
            $percentMask = $this->highlight($percentMask);
        }

        $this->write(sprintf(
            sprintf('%s / %s (%s%%%%) %%s', $numberMask, $numberMask, $percentMask),
            $this->numTestsRun,
            $this->numTests,
            floor(($this->numTestsRun / $this->numTests) * 100),
            $this->overrideTimeThresholdProgress($progress)
        ));
    }

    /**
     * @param  PHPUnit_Framework_Test $test
     * @param $time
     * @return bool
     */
    private function analyzeTimeThreshold(PHPUnit_Framework_Test $test, $time)
    {
        $this->writeTimeThresholdInfo($test, $time);

        if (!$this->checkTimeThresholdExceeded($test)) {
            return false;
        }

        $this->collectTimeThresholdInfo($test, $time);

        return true;
    }

    /**
     * @param  PHPUnit_Framework_Test $test
     * @return bool
     */
    private function checkTimeThresholdExceeded(PHPUnit_Framework_Test $test)
    {
        return property_exists($test, 'timeThresholdExceeded') && $test->timeThresholdExceeded;
    }

    /**
     * @param  PHPUnit_Framework_Test $test
     * @return bool
     */
    private function checkTimeThresholdVerbose(PHPUnit_Framework_Test $test)
    {
        return $this->verbose || $this->debug || (property_exists($test, 'timeThresholdVerbose') && $test->timeThresholdVerbose);
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param $time
     */
    private function collectTimeThresholdInfo(PHPUnit_Framework_Test $test, $time)
    {
        $this->data[$test->toString()] = $time;
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param $time
     */
    private function writeTimeThresholdInfo(PHPUnit_Framework_Test $test, $time)
    {
        if ($this->checkTimeThresholdVerbose($test)) {
            $color = $this->checkTimeThresholdExceeded($test) ? '33' : '00';
            $this->write(sprintf("\x1b[0;%sm%s took ~%ds {%f}\x1b[0m\n", $color, $test->toString(), $time, $time));
        }
    }

    /**
     * @param  string $progress
     * @return string
     */
    private function overrideTimeThresholdProgress($progress)
    {
        if ($progress === '') {
            return $progress;
        }

        if ($this->change) {
            $progress = $this->highlight($progress, true);
        }

        if ($this->verbose || $this->debug) {
            $progress = sprintf("[%s] ", $progress);
        }

        return $progress;
    }

    /**
     * @param  string $string
     * @param  bool   $bold
     * @return string
     */
    private function highlight($string, $bold = false)
    {
        return sprintf("\x1b[%d;33m%s\x1b[0m", (int) $bold, $string);
    }

    /**
     * Print final report
     */
    private function printSlowestTen()
    {
        if (!$this->data) {
            $this->write(sprintf("\n\x1b[0;50m# Good job man! No slow test found.\x1b[0m\n\n"));

            return;
        }

        arsort($this->data);
        $slowestTest = array_slice($this->data, 0, 10, true);
        $slowestCount = count($slowestTest);

        $this->write(sprintf("\n\x1b[0;50m# The Slowest Top%d\x1b[0m\n\n", $slowestCount));

        $i = 0;
        $timeSpent = 0.0;

        foreach ($slowestTest as $name => $time) {
            $this->write(sprintf("% 2d) \x1b[0;%sm% 7.2f > %s\x1b[0m\n", ++$i, ($i <= 8 ? 30 + $i : 38), $time, $name));
            $timeSpent += $time;
        }

        $this->write(sprintf("\n\x1b[0;50m# Wasted \x1b[0;31m%fs\x1b[0;50m running %d tests\x1b[0m\n\n", $timeSpent, $slowestCount));
    }
}
