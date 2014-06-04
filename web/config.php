<?php

define("PROJECT", "collie");  //project name

define("URL_HOME", "/" . PROJECT ); 

define("PATH_PROJECT", dirname(__FILE__) . "/..");
define("PATH_WEB", dirname(__FILE__) . "/../web");
define("PATH_CONTROLLER", dirname(__FILE__) . "/../lib/controller");
define("PATH_TEMP", PATH_PROJECT . "/web/templates"); //template path
define("PATH_DIR_RUN", PATH_WEB . "/runTmp"); // descriptor temp dir , It will generate the descriptor file into this dir

define("PATH_CONTROLLER_DEAFULT_SETTING", PATH_PROJECT . '/conf/controllerDefaultSetting.json');
define("PATH_CONTROLLER_LIST", PATH_PROJECT . "/conf/controllerList.json");

define("URL_DIR_RUN", URL_HOME . "/runTmp"); 


// mysql
define("MYSQL_HOST", "localhost");
define("MYSQL_DB", "collie");
define("MYSQL_USER", "collie");
define("MYSQL_PSWD", "collie");

