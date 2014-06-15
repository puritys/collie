<?php
require_once PATH_PROJECT . '/lib/collieBasic/testAssert.php';
//$GLOBALS['testAssert'] = new testAssert();

class collieBasicController {
    protected $param;
    protected $driver;
    protected $config;
    protected $logFile;
    public function __construct($driver, $param, $config = array(), $logFile = "") {
        $this->param = $param;
        $this->config = $config;
        $this->driver = $driver;
        if (isset($GLOBALS['testAssert'])) {
            $this->testAssert = $GLOBALS['testAssert'];
        }

        if (!empty($logFile)) {
            $this->logFile = $logFile;

        }
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
            $html = UILogUtil::startController($this->name);
        } else {
            $html = UILogUtil::startController("Start a new controller.");
        }
        echo $html;
        $this->saveLog($html);
    }
    public function endRun () {
        if (empty($this->type) || $this->type != "test") {
            $this->getScreen();
        }
        $html = UILogUtil::endController();
        echo $html;
        $this->saveLog($html);
    }

    public function getScreen() {
        //fetch image
        $name = time() . '.jpg';
        $imageFile = $this->config["PATH_CASE_RESULT"] . '/' . $name;
        $html = UILogUtil::screenshot($this->config["URL_CASE_RESULT"] .'/'. $name);
        echo $html;
        $this->saveLog($html);
        $this->driver->takeScreenshot($imageFile);

    }

    public function showLog($message, $level = 1) {
        $html = UILogUtil::showLog($message, $level);
        echo $html;
        $this->saveLog($html);
    }

    public function saveLog($html) {
        if ($this->logFile) {
            file_put_contents($this->logFile, $html, FILE_APPEND);
        }
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
