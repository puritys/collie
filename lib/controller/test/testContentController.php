<?php
require_once PATH_CONTROLLER . '/basic/collieBasicController.php';
class testContentController extends collieBasicController {
    public $formParam = array (
        "type" => array(
            "label" => "Test type",
            "type" => "select",
            "options" => array(
                array("html", "HTML content"),
                array("selector", "CSS Selector"),
                array("url", "URL"),
            ),
            "hint" => "Select a type to be compared.",
        ),
        "content" => array(
            "label" => "Exist Content",
            "type"  => "input",
            "hint" => "All comparison is partial equal.",
        )
    );
    public function main ($config, $param) {
        $type = $param['type'];
        $expectContent = $param['content'];
        $contentToCompare = "";
        switch ($type) {
            case 'html':
                $body = $this->driver->findElement(
                      WebDriverBy::cssSelector('body')
                );
                $contentToCompare = $body->getText();

                $failedReason = 'The value you want is not in HTML source.';
                $this->assertPartialEquals($expectContent, $contentToCompare, $failedReason);
                break;
            case 'url':
                $currentUrl = $this->driver->getCurrentUrl();

                $failedReason = 'This url is not what you want.';

                $this->assertPartialEquals($expectContent, $currentUrl, $failedReason);

                break;


        }

    }


}
