<?php
require_once "init.php";

$profile = new FirefoxProfile();
$profile->setPreference(
  'browser.startup.homepage',
  'https://github.com/facebook/php-webdriver/'
);
$profile->setPreference(
  'browser.helperApps.neverAsk.saveToDisk',
  'application/vnd.ms-excel'
);

$profile->addExtension('firebug-2.0.1.xpi');
$caps = DesiredCapabilities::firefox();
$caps->setCapability(FirefoxDriver::PROFILE, $profile);

$driver = RemoteWebDriver::create($seleniumUrl, $caps, 5000);
//$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);
$driver->get("http://www.puritys.me/download.html");


$url = $driver->getCurrentUrl();
error_log( "url = " . $url);
//change url and wait
$elm = $driver->findElement(
  WebDriverBy::cssSelector('a')
);
$elm->click();
sleep(10);
//$driver->get($host . "/docs-blog/article-49");

error_log("test");
$driver->close();
