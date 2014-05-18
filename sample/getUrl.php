<?php
require_once "init.php";


$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);

$driver->get($host . "/docs-blog/article-49");


$url = $driver->getCurrentUrl();
error_log( "url = " . $url);
//change url and wait
$elm = $driver->findElement(
  WebDriverBy::cssSelector('.nav-box a:nth-of-type(1)')
);

$elm->click();
// wait for at most 10 seconds until the URL is 'http://example.com/account'.
// check again 500ms after the previous attempt.
$driver->wait(10, 500)->until(function ($driver) {
    if ($driver->getCurrentURL() === 'http://www.puritys.me/news') {
        error_log("Document is complete");
        return true;
    } else {
        return false;
    }
});

error_log("test");
$driver->close();
