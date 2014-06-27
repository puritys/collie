<?php
require_once "init.php";


$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);

$driver->get($host . "/");
//$driver->executeScript('alert("test")');

$element = $driver->findElement(
    WebDriverBy::cssSelector('.menu1:nth-of-type(2)')
);

$action = $driver->action();
$action->moveToElement($element);
$action->click($element);

sleep(5);
error_log("test");
$driver->close();
