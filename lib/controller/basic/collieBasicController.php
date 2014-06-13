<?php
require_once PATH_PROJECT . '/lib/collieBasic/testAssert.php';
//$GLOBALS['testAssert'] = new testAssert();

class collieBasicController {
    protected $param;
    protected $driver;
    protected $config;

    public function __construct($driver, $param, $config = array()) {
        $this->param = $param;
        $this->config = $config;
        $this->driver = $driver;
        $this->testAssert = $GLOBALS['testAssert'];

    }

    public function run () {
        $this->beforeRun();
        $this->main($this->config, $this->param);
        $this->endRun();
    }

    public function main($config, $param) {

    }

    public function beforeRun () {
        if (!empty($this->name)) {
            echo UILogUtil::startController($this->name);
        } else {
            echo UILogUtil::startController("Start a new controller.");
        }
    }
    public function endRun () {
        if (empty($this->type) || $this->type != "test") {
            $this->getScreen();
        }
        echo UILogUtil::endController();
    }

    public function getScreen() {
        //fetch image
        $name = time() . '.jpg';
        $imageFile = $this->config["PATH_CASE_RESULT"] . '/' . $name;
        echo UILogUtil::screenshot($this->config["URL_CASE_RESULT"] .'/'. $name);
        $this->driver->takeScreenshot($imageFile);

    }

    public function showLog($message, $level = 1) {
        echo UILogUtil::showLog($message, $level);
    }

    public function assertEquals($val1, $val2, $reason) {
        return $this->testAssert->assertEquals($val1, $val2, $reason);
    }

    public function assertPartialEquals($expect, $actual, $reason) {
        return $this->testAssert->assertPartialEquals($expect, $actual, $reason);
    }

    public function assertTrue($val1, $reason) {
        return $this->testAssert->assertTure($val1);
    }


}
