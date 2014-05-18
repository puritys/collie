<?php
require_once "init.php";


$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);

$driver->get($host . "/");

$manage = $driver->manage(); //WebDriverOptions
$window = $manage->window();  //WebDriverWindow
$cookie = $manage->getCookies();  //addCookie, deleteCookie ... 
$log = $manage->getLog('browser'); //client, driver, browser, server


$dimension = $window->getSize();
$position = $window->getPosition();  //getx ,x , move to 
$height = $dimension->getHeight();
$width = $dimension->getWidth();

error_log("window height = $height");
error_log("window width = $width");
error_log("window positionX = " . $position->getX()  . ' positionY = ' . $position->getY());

$position->move(200, 0);
$window->setPosition($position);
//error_log("cookie = " . print_r($cookie, 1));
//error_log("log = " . print_r($log, 1));

//$newSize = new WebDriverDimension(100,200);
//$window->setSize($newSize);
//$window->maximize();

sleep(3);
error_log("test");
$driver->close();
