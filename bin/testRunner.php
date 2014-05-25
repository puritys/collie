<?php
require_once "../lib/controller/basic/collieBasicController.php";
require_once "../lib/collieBasic/runner.php";


$runner = new runner("../lib/controller/");
$runner->loadConfig("../sample/config.json");
$runner->loadControllerList("../sample/controllerList.json");
$runner->loadRunBook("../sample/sample.json");

$runner->startDriver();
$runner->run();
$runner->closeDriver();

