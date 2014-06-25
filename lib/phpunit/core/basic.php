<?php

class webDriverBasic {
    public $driver;

    public function __construct($driver) {
        $this->driver = $driver;
    }

    public function getElm($selector) {
        return $this->driver->findElement(
            WebDriverBy::cssSelector($selector)
        );
    }

    public function form($data, $parent = "") {
        foreach ($data as $key => $value) {
            try {
                $elm = $this->getElm($parent . ' input[name=' . $key . ']');
            } catch (Exception $e) {
                try {
                    $elm = $this->getElm($parent . ' textarea[name=' . $key . ']');
                } catch (Exception $e) {
                    error_log("Not Found!");
                    continue;
                }
            }

            $elm->clear();
            $elm->sendKeys($value);
        }

    }
}
