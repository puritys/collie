<?php

require_once PATH_CONTROLLER . '/basic/collieBasicController.php';
class fillOutFormController extends collieBasicController {

    public $formParam = array (
        "form" => array(
            "label" => "Fill up form",
            "type"  => "input", //key-value
//            "type": "select",
//            "options": [
//                ["NONE", "Please select a option"],
//                ["xx1", "select 1"],
//            ],
            "hint" => "Type a url like example: http://www.purity.me/",
        )
    );

    public function main () {

        //input form
        $input = array(
            "reply_name" => "test",
            "reply_email" => "test@gmail.com",
            "reply_content" => "content test ",
            "xxf" => "f",
        );

        $driverBasic = new webDriverBasic($this->driver);
        $driverBasic->form($input);

        return true;

    }


}
