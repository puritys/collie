<?php

class testAssert {
    public $passedNumber = 0;
    public $failedNumber = 0;
    public $totalNumber = 0;
    public $failedLog = array();
    public function assertEquals($val1, $val2, $reason) {
        $this->totalNumber++;
        if ($val1 == $val2) {
            $this->passedNumber++;
            return true;
        }

        $this->failedLog[] = $reason;
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
