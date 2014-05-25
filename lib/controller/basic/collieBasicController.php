<?php

class collieBasicController {
    protected $param;
    protected $driver;
    protected $config;

    public function __construct($driver, $param, $config = array()) {
        $this->param = $param;
        $this->config = $config;
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
