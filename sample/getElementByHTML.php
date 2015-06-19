<?php
require_once "init.php";

//$profile = new FirefoxProfile();

//$caps = DesiredCapabilities::firefox();
//$caps->setCapability(FirefoxDriver::PROFILE, $profile);
$capabilities = array(
    WebDriverCapabilityType::BROWSER_NAME => 'firefox',
//    FirefoxDriver::PROFILE => $profile
);

$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);



$host = "http://dev.puritys.me/mysite/";
$driver->get($host . "test.php");

$value = "test";

$driver->executeScript('var elms = document.querySelectorAll(".alert-success"); for(var i = 0; i < elms.length; i++) {elms[i].style.opacity = 100; elms[i].style.display="block"; }');

//$driver->executeScript('$(".alert-success").css({opacity: 100}).show()');

//change url and wait
$elm = $driver->findElement(
  WebDriverBy::xpath('//div[normalize-space( . ) = "' . $value . '"]')
);

echo $elm->getText() . "\n";
//$elm = $driver->findElement(
//  WebDriverBy::cssSelector('div[nodeValue = "' . $value . '"]')
//);
print_r($elm);


// wait for at most 10 seconds until the URL is 'http://example.com/account'.
// check again 500ms after the previous attempt.
$driver->close();
