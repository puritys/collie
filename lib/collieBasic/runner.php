<?php
require_once "../lib/phpunit/php-webdriver/lib/__init__.php";
require_once "../lib/phpunit/core/basic.php";
require_once PATH_PROJECT . '/lib/collieBasic/testAssert.php';
$GLOBALS['testAssert'] = new testAssert();

class runner {
    public $controllerBasePath;
    public $controllerList;
    public $runBook;
    public $driver;
    public function __construct($basePath) {
        $this->controllerBasePath = $basePath;
        $this->assert = $GLOBALS['testAssert'];
    }

    public function startDriver() {/*{{{*/
        $capabilities = array(
            WebDriverCapabilityType::BROWSER_NAME => 'firefox'
        );

        $seleniumUrl = 'http://localhost:4444/wd/hub';
        $this->driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);
    }/*}}}*/

    public function closeDriver() {
        sleep(1);
        $this->driver->close();
    }

    public function loadControllerList ($file) {/*{{{*/
        $content = file_get_contents($file);
        $list = json_decode($content, true);
        $this->controllerList = $list;
    }/*}}}*/

    public function loadRunBook($file) {
        if (is_array($file)) {
            $this->runBook = $file;
        } else {
            $content = file_get_contents($file);
            $this->runBook = json_decode($content, true);
        }
    }

    public function loadConfig($file) {
        if (is_array($file)) {
            $this->config = $file;
        } else {
            $content = file_get_contents($file);
            $this->config = json_decode($content, true);
        }
    }

    public function getControllerBaseInfo($name) {/*{{{*/
        if (isset($this->controllerList[$name])) {
            return $this->controllerList[$name];
        }

        if (isset($this->controllerList[$name . "Controller"])) {
            return $this->controllerList[$name . "Controller"];
        }
        return "";
    }/*}}}*/

    /**
    * Start to run selenium controller to send command.
    */
    public function run() {
        $totalCase = 0;
        $passCase = 0;
        $failCase = 0;
        foreach ($this->runBook['process'] as $controller) {
            $controllerName = (isset($controller['controller']))? $controller['controller']: $controller['name'];
            $controllerBaseInfo = $this->getControllerBaseInfo($controllerName);
            if (empty($controllerBaseInfo)) {
                error_log("Mission controller " . $controllerName);
                continue;
            }
            require_once $this->controllerBasePath . $controllerBaseInfo['filePath'];
            $classname = $controllerName . "Controller";
            error_log("Run : " . $classname);
            $control = new $classname($this->driver, $controller['params'], $this->config);
            $control->run();
        }
        return $this->assert->getReport();
        
//        return array($res['totalNumber'], $res['passedNumber'], $res['failedNumber']);
    }


}
