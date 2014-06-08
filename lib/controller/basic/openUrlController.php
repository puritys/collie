<?php
require_once PATH_CONTROLLER . '/basic/collieBasicController.php';
class openUrlController extends collieBasicController {
    public $isSameUrl = true;
    public $formParam = array (
        "url" => array(
            "label" => "Url",
            "type"  => "input",
//            "type": "select",
//            "options": [
//                ["NONE", "Please select a option"],
//                ["xx1", "select 1"],
//            ],
            "hint" => "Type a url like example: http://www.purity.me/",
        )
    );
    public function main ($config, $param) {
        $this->url = $this->param["url"];
        $this->driver->get($this->url);
        $this->originalUrl = $this->driver->getCurrentURL();

        if ($this->url !== $this->originalUrl) {
            $this->isSameUrl = false;
        }
        // wait for at most 10 seconds until the URL is 'http://example.com/account'.
        // check again 500ms after the previous attempt.
        $this->driver->wait(10, 500)->until(array($this, 'checkIsComplete'));
        return true;
    }

    public function checkIsComplete () {
        $script = 'return document.readyState';
        $status = $this->driver->executeScript($script);
        $this->getScreen();
        if ($status == "complete") {
            return true;
        }
        return false;



/*
        if (!$this->isSameUrl) { 
error_log("url = " . $this->driver->getCurrentURL() . "  original = " . $this->originalUrl);
            if ($this->driver->getCurrentURL() != $this->originalUrl) {
                error_log("Open Url is complete");
                return true;
            } else {
                error_log("Open Url is failed.");
                return false;
            }
        } else {
            if ($this->driver->getCurrentURL() == $this->originalUrl) {
                error_log("Open Url is complete");
                return true;
            } else {
                error_log("Open Url is failed.");
                return false;
            }


        }
*/
    }


}
