<?php
require_once "../lib/phpunit/php-webdriver/lib/__init__.php";
require_once "../lib/phpunit/core/basic.php";
$capabilities = array(
    WebDriverCapabilityType::BROWSER_NAME => 'firefox'
);
$seleniumUrl = 'http://localhost:4444/wd/hub'; 
$host = "http://www.puritys.me";
