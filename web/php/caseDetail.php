<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/categoryExe.php";
require_once PATH_WEB . "/lib/controllerExe.php";

$caseDB = new caseExe($db);
$controllerExe = new controllerExe("");

$safeInputs = basicUtil::filterInputs('id', 'GET');



$args = array(
    "id" => $safeInputs['id'],
);

$case = $caseDB->getCase($args);
$data = array(
    'title' => $case[0]['title'],
    'content' => $case[0]['content'],
);


$data['descriptor'] = $case[0]['descriptor'];
$data['caseId'] = $case[0]['case_id'];

//get config
$descriptor = json_decode(html_entity_decode($data['descriptor']), true);
$n = count($descriptor['scenario']);
$controllers = array();
for ($i = 0; $i < $n; $i++) {
    $it = $descriptor['scenario'][$i];

    $conSetting = $controllerExe->getControllerSetting($it['id']);
    require_once $conSetting['filePath'];
    $classname = $conSetting['classname'];
    $con = new $classname("", "");
    $controllers[] = array(
        "id" => $it['id'],
        "name" => $con->name,
    );
}
$data['controllers'] = $controllers;

$categoryDB = new categoryExe($db);
$allCates = $categoryDB->getAllCategory();
$data['cates'] = $allCates;

$body = array(
    array("grid" => "u-1", "T" => "case/caseDetail/caseDetail.hb.html", "dataObject" => $data),
);


