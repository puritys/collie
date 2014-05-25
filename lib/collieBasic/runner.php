<?php
require_once "../lib/phpunit/php-webdriver/lib/__init__.php";
require_once "../lib/phpunit/core/basic.php";

class runner {
    public $controllerBasePath;
    public $controllerList;
    public $runBook;
    public $driver;
    public function __construct($basePath) {
        $this->controllerBasePath = $basePath;
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
        $content = file_get_contents($file);
        $this->runBook = json_decode($content, true);
    }

    public function loadConfig($file) {
        $content = file_get_contents($file);
        $this->config = json_decode($content, true);
    }

    public function getControllerBaseInfo($name) {/*{{{*/
        if (isset($this->controllerList[$name])) {
            return $this->controllerList[$name];
        }

        if (isset($this->controllerList[$name . "Controller"])) {
            return $this->controllerList[$name . "Controller"];
        }
        return null;
    }/*}}}*/

    /**
    * Start to run selenium controller to send command.
    */
    public function run() {
        foreach ($this->runBook['process'] as $controller) {
            $controllerBaseInfo = $this->getControllerBaseInfo($controller['controller']);
            require_once $this->controllerBasePath . $controllerBaseInfo['filePath'];
            $classname = $controller['controller']. "Controller";
            error_log("Run : " . $classname);
            $control = new $classname($this->driver, $controller['param'], $this->config);
            $control->run();
        }
    }


}
