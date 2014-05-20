<?php

class collieBasicController {
    protected $param;
    protected $driver;

    public function __construct($driver, $param) {
        $this->param = $param;
        $this->driver = $driver;
    }

    public function run () {
        $this->beforeRun();
        $this->main();
    }

    public function main() {

    }

    public function beforeRun () {

    }


}
