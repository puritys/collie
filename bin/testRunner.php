<?php

//$capabilities = array(
//    WebDriverCapabilityType::BROWSER_NAME => 'firefox'
//);
//
//$seleniumUrl = 'http://localhost:4444/wd/hub';
//
//
//$driver = RemoteWebDriver::create($seleniumUrl, $capabilities, 5000);
//
require_once "../lib/controller/basic/collieBasicController.php";
require_once "../lib/collieBasic/runner.php";



//require_once "../lib/controller/basic/openUrlController.php";
//require_once "../lib/controller/basic/fillOutFormController.php";

$runner = new runner("../lib/controller/");
$runner->loadControllerList("../sample/controllerList.json");
$runner->loadRunBook("../sample/sample.json");

$runner->startDriver();
$runner->run();
$runner->closeDriver();
//$param = array(
//    "url" => "http://www.puritys.me/docs-blog/article-49",
//);
//
//$c = new openUrlController($driver, $param);
//
//$c->run();
//
//$c2 = new fillOutFormController($driver, $param);
//$c2->run();
