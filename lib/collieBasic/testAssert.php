<?php

class testAssert {
    public $passedNumber = 0;
    public $failedNumber = 0;
    public $totalNumber = 0;
    public $failedLog = array();
    public function assertEquals($expect, $actual, $failReason) {
        $this->totalNumber++;
        if ($expect == $actual) {
            $this->passedNumber++;
            return true;
        }
        $failReason = <<<HTML
$failReason
These two value is not equal.
Expect Value: $expect
Actual Value: $actual
HTML;
        $this->failedLog[] = $failReason;
        $this->failedNumber++;
        return false;
    }

    public function assertPartialEquals($expect, $actual, $failReason) {
        $this->totalNumber++;
        if (strpos($actual, $expect) !== false) {
            $this->passedNumber++;
            return true;
        }

        $failReason = <<<HTML
$failReason
Actual value is not include expect value.
Expect Value: $expect
Actual Value: $actual
HTML;

        $this->failedLog[] = $failReason;
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
            "failedLog" => $this->failedLog,
        );
    }

}
