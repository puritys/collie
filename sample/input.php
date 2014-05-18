<?php
require_once "init.php";


$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);

$driver->get($host . "/docs-blog/article-49");


$url = $driver->getCurrentUrl();
error_log( "url = " . $url);
//change url and wait
$elm = $driver->findElement(
  WebDriverBy::cssSelector('input[name=reply_name]')
);

$elm->sendKeys("我是誰");


//input form
$input = array(
    "reply_name" => "test",
    "reply_email" => "test@gmail.com",
    "reply_content" => "content test ",
    "xxf" => "f",
);

$driverBasic = new webDriverBasic($driver);
$driverBasic->form($input);

sleep(3);
error_log("test");
$driver->close();
