<?php
//http://facebook.github.io/php-webdriver/classes/WebDriverMouse.html
require_once "init.php";

$capabilities = array(
    WebDriverCapabilityType::BROWSER_NAME => 'firefox',
);

$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);
$manage = $driver->manage();
$window = $manage->window();
$window->maximize();

//$driver->get("http://jqueryui.com/resources/demos/droppable/default.html");
//
//$sourceElm = $driver->findElement(WebDriverBy::cssSelector('.ui-draggable'));
//$targetElm = $driver->findElement(WebDriverBy::cssSelector('.ui-droppable'));
//
////$action = $driver->action();
////$action->dragAndDrop($sourceElm, $targetElm)->perform();
//
//
//$action = $driver->action();
//$action->moveToElement($sourceElm)
//->clickAndHold($sourceElm)
//->moveByOffset(150,0)->release()->perform();



$manage = $driver->manage();
$window = $manage->window();
$window->maximize();
$driver->get("http://dev.puritys.me/test.html");

$sourceElm = $driver->findElement(WebDriverBy::cssSelector('#drag1'));
$targetElm = $driver->findElement(WebDriverBy::cssSelector('#div1'));

$action = $driver->action();
$action->dragAndDrop($sourceElm, $targetElm)->perform();


$action2 = $driver->action();
$action2->dragAndDropBy($sourceElm, 10, -100);
$action2->perform();



//this is what i tried first
//$driver->getMouse()->moveto($targetElm);
//$driver->getMouse()->buttondown("");
//$driver->getMouse()->moveto($course2);
//$driver->getMouse()->buttondown("");
//

sleep(3);
// wait for at most 10 seconds until the URL is 'http://example.com/account'.
// check again 500ms after the previous attempt.
$driver->close();
