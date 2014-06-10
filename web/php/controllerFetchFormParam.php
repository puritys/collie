<?php
/** Return a json response**/

$safeInputs = basicUtil::filterInputs('name');

$controllerFile = PATH_CONTROLLER_LIST;
$controller = file_get_contents($controllerFile);
$controller = json_decode($controller, true);


$allowController = false;
foreach ($controller as $key => $val) {
    if ($key == $safeInputs['name']) {
        $allowController = true;
        $file = $val['filePath'];
        $controllerName = $key;
        break;
    }
}


if ($allowController !== true) {
    exit(1);
}


require_once PATH_CONTROLLER . '/' . $file;
$controllerName .= 'Controller';
$controller = new $controllerName();

$data = json_encode($controller->formParam);

include PATH_WEB . '/views/ajax.phtml';