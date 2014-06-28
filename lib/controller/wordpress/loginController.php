<?php
require_once PATH_CONTROLLER . '/basic/collieBasicController.php';

class loginController extends collieBasicController {
    public $name = "Wordpress Login";
    public $formParam = array (
        "autoOpenLoginPage" => array(
            "label" => "OpenLoginPage",
            "type" => "checkbox",
            "hint" => "Checked: Testing will auto open login page and start to login. The login page url is setted in controller source code."
        ),
        "user" => array(
            "label" => "Login User",
            "type" => "select",
            "options" => array(
                array("default", "Default User"),
                array("user1", "User 1"),
            ),
            "hint" => "Select the username which you want to login.",
        ), 
    );
    public function main ($config, $param) {
        if ($param['autoOpenLoginPage'] == true) {
            $url = $config['host'] . '/wp-admin';
            $this->showLog("Login url is \"" .$url."\"");    
            $this->driver->get($url);
        }

        $user = $param['user'];
        if (empty($user) || $user == "default") {
            $user = "user1";
        }

        $username = $config[$user];
        $password = $config[$user . '_password'];
        $this->showLog("Login Username = $username");
        $inputs = array(
            "log" => $username,
            "pwd" => $password,
        );

        $driverBasic = new webDriverBasic($this->driver);
        $driverBasic->form($inputs);
        $this->getScreen("Fillout the form.");

        $elm = $this->driver->findElement(
          WebDriverBy::cssSelector('#wp-submit')
        );
 
        $elm->click();
        $this->showLog("Click submit button.");

        $this->driver->wait(10, 500)->until(function ($driver) {
            $url = $driver->getCurrentURL();
            if (strpos($url, 'wp-admin') > 0) {
                error_log("Document is complete");
                return true;
            } else {
                return false;
            }
        });

        return true;
    }


}
