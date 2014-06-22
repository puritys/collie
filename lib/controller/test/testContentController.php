<?php
require_once PATH_CONTROLLER . '/basic/collieBasicController.php';
class testContentController extends collieBasicController {
    public $name = "Test assert";
    public $type = "test";
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
        "selector" => array(
            "label" => "Selector",
            "type"  => "input",
            "hint" => "If you choose the type \"CSS Selector\", then you must enter the class name of element.",
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
        $this->showLog("Test type is " . $type, 1);

        switch ($type) {
            case 'html':
                $this->showLog("To get the html source code and check the expected value is in the html or not.", 1);

                $contentToCompare = $this->driver->executeScript("return document.body.innerHTML;");

                $failedReason = 'The value you want is not in HTML source.';
                $this->assertPartialEquals($expectContent, $contentToCompare, $failedReason);
                break;
            case 'url':
                $contentToCompare = $currentUrl = $this->driver->getCurrentUrl();
                $failedReason = 'This url is not what you want.';
                $this->assertPartialEquals($expectContent, $currentUrl, $failedReason);

                break;
            case 'selector':
                $this->showLog("To find the element in html and get the text of element.", 1);

                $elm = $this->driver->findElement(
                      WebDriverBy::cssSelector($param['selector'])
                );
                $contentToCompare = $elm->getText();

                $failedReason = 'Can not find value.';
                $this->assertPartialEquals($expectContent, $contentToCompare, $failedReason);

                break;


        }
        $this->showLog("Expected value is  \"<span class=\"test-expected-log-value\">" . $expectContent ."</span>\"", 1);
        $actual = htmlspecialchars($contentToCompare);
        $this->showLog("<div class=\"test-actual-log-title\">Actual value is  </div><div class='test-actual-log-value'>" . $actual . "</div>", 1);

    }


}
