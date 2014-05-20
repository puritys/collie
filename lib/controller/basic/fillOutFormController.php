<?php

class fillOutFormController extends collieBasicController {

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
