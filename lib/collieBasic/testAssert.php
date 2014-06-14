<?php

class testAssert {
    public $passedNumber = 0;
    public $failedNumber = 0;
    public $totalNumber = 0;
    public $testResult = array();
    public $failedLog = array();
    public function assertEquals($expect, $actual, $failReason) {
        $this->totalNumber++;
        if ($expect == $actual) {
            $this->passedNumber++;
            $this->saveResult(true, $expect, $actual, $failReason);
            return true;
        }
        $failReason = <<<HTML
These two value is not equal.<br />
$failReason
HTML;
        $this->saveResult(false, $expect, $actual, $failReason); 

        $this->failedNumber++;
        return false;
    }

    public function assertPartialEquals($expect, $actual, $failReason) {
        $this->totalNumber++;
        if (strpos($actual, $expect) !== false) {
            $this->passedNumber++;
            $this->saveResult(true, $expect, $actual, $failReason); 
            return true;
        }
        $failReason = <<<HTML
The actual value is not in expected value.<br />
$failReason
HTML;

        $this->saveResult(false, $expect, $actual, $failReason); 
        $this->failedNumber++;
        return false;
    }

    public function assertTrue($val1) {
        return $this->testAssert->assertTure($val1);
    }

    public function getReport() {
        return array(
            "passedNumber" => $this->passedNumber,
            "failedNumber" => $this->failedNumber,
            "totalNumber" => $this->totalNumber,
            "testResult" => $this->testResult,
        );
    }

    public function saveResult($passed, $expect, $actual, $reason) {
        $this->testResult[] = array(
            "passed" => $passed,
            "expect" => $expect,
            "actual" => $actual,
            "reason" => $reason,
        );
    }


}
