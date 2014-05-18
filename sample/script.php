<?php
require_once "init.php";


$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);

$driver->get($host . "/");
$driver->executeScript('alert("test")');

sleep(3);
error_log("test");
$driver->close();
