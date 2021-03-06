<?php
$PATH = dirname(__FILE__);
require_once $PATH . "/../../lib/phpunit/php-webdriver/lib/__init__.php";
require_once $PATH . "/../../lib/phpunit/core/basic.php";
require_once PATH_PROJECT . '/lib/collieBasic/testAssert.php';
$GLOBALS['testAssert'] = new testAssert();

class runner {
    private $db;
    public $controllerBasePath;
    public $controllerList;
    public $runBook;
    public $driver;
    public $logFile;
    public function __construct($basePath, $logFile = "", $db = "") {
        $this->controllerBasePath = $basePath;
        $this->assert = $GLOBALS['testAssert'];
        $this->logFile = $logFile;

        if (!empty($logFile) && !is_file($logFile)) {
            file_put_contents($this->logFile, "");
        }

        if (!empty($db)) {
            $this->db = $db;
        }
    }

    public function startDriver($config) {/*{{{*/
        $capabilities = array(
            WebDriverCapabilityType::BROWSER_NAME => $config['browser']
        );

        if (empty($config['seleniumHost'])) {
            $config['seleniumHost'] = 'http://localhost:4444/wd/hub';
        }
        $seleniumUrl = $config['seleniumHost'];
        $timeout = 10000;
        $this->driver = RemoteWebDriver::create($seleniumUrl, $capabilities, $timeout);
        $manage = $this->driver->manage();
        $window = $manage->window();
        $window->maximize();
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
//print_r($this->runBook['process']);exit(1);
        foreach ($this->runBook['process'] as $controller) {
            if (empty($controller['id'])) {
                continue;
            }
            $controllerId = (isset($controller['controller']))? $controller['controller']: $controller['id'];
            $controllerName = (isset($controller['controller']))? $controller['controller']: $controller['name'];

            $controllerBaseInfo = $this->getControllerBaseInfo($controllerId);

            if (empty($controllerBaseInfo)) {

                error_log("Mission controller " . $controllerName);
                continue;
            }

            require_once $controllerBaseInfo['filePath'];
            $classname = $controllerId . "Controller";
            error_log("Run : " . $classname);
            $control = new $classname($this->driver, $controller['params'], $this->config, $this->logFile);
            $control->setDB($this->db);
            $classname = null; $controllerID = null;
            try {
                $control->run();
                //@ob_flush();
                //@flush();

            } catch (Exception $e) {
                $control->showLog("Program has exception, please fix it. Exception Message = " . print_r($e->getMessage(), 1), 1);
                error_log("has exception message = " . print_r($e,1));
                print_r($e);

                $control->getScreen();
                $control->assertEquals(true, false, 'Exception Happen.');
                break;
            }

        }
        return $this->assert->getReport();
        
    }


}
