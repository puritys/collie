<?php
require_once PATH_PROJECT . '/lib/collieBasic/testAssert.php';
//$GLOBALS['testAssert'] = new testAssert();

class collieBasicController {
    protected $param;
    protected $driver;
    protected $config;
    protected $logFile;
    protected $db;
    public $type;
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

    public function setDB($db) {
        $this->db = $db;
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
            $html = UILogUtil::startController($this->name, $this->type);
        } else {
            $html = UILogUtil::startController("Start a new controller.", $this->type);
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

    /**
    * Save some data into db, then we can get it again later.
    * Example:  we need to create a item and buy it at next controller, but we need to keep the product id.
    * So we could save the product id in database, and get it later.
    */
    public function saveData($key, $val) {
        $createTime = date("Y/m/d H:i:s", time());
        $sql = "replace into dataValue(`key_name`, `value`, `create_time`) values(:key, :value, :create_time)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':key', $key, PDO::PARAM_STR);
        $st->bindValue(':value', $val, PDO::PARAM_STR);
        $st->bindValue(':create_time', $createTime, PDO::PARAM_STR);
        $this->db->beginTransaction();
        $st->execute();
        $id = $this->db->lastInsertId();
        $this->db->commit();
        return true;
    }

    public function getData($key, $expired = 0) {
        $sql = "select * from dataValue where key_name=:key";
        $st = $this->db->prepare($sql);
        $st->bindValue(':key', $key, PDO::PARAM_STR);
        //$st->bindValue(':create_time', $createTime, PDO::PARAM_STR);
        $st->execute();
        $ret = $st->fetchAll(PDO::FETCH_ASSOC);
        if (isset($ret[0]['value'])) {
            return $ret[0]['value'];
        } 

        return false;

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
