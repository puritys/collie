<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/categoryExe.php";
require_once PATH_WEB . "/lib/caseCategoryExe.php";
require_once PATH_WEB . "/lib/controllerExe.php";

$controllerFile = PATH_CONTROLLER_LIST;
$controller = file_get_contents($controllerFile);
$controller = json_decode($controller, true);

$controllerExe = new controllerExe("");


$data = array(
    "action" => "new",
    "referer" => $_SERVER['HTTP_REFERER'],
    "controller" => dataUtil::transformController_to_HandlebarForamt($controller),
);


if (isset($_GET['id'])) {
    $caseId = basicUtil::filterInput($_GET['id']);
}

if (!empty($caseId)) {
    $caseDB = new caseExe($db);
    $case = $caseDB->getCase(array("id" => $caseId));
    if (!isset($case[0])) {
        echo "This case is not exist.";
        exit(1);
    }
    $data['action'] = "edit";
    $data['title'] = $case[0]['title'];
    $data['content'] = $case[0]['content'];
    $data['descriptor'] = $case[0]['descriptor'];
    $data['caseId'] = $case[0]['case_id'];

    //get config
    $descriptor = json_decode(html_entity_decode($data['descriptor']), true);

    $n = count($descriptor['scenario']);
    $config = array();
    for ($i = 0; $i < $n; $i++) {
        $it = $descriptor['scenario'][$i];
        $conSetting = $controllerExe->getControllerSetting($it['name']);
        require_once $conSetting['filePath'];
        $classname = $conSetting['classname'];
        $con = new $classname("", "");
        $param = $con->formParam;
        //$controller = $it['name'];
        //$configFile = PATH_PROJECT . '/web/' . $controller;
        //$tx = file_get_contents($configFile);
        $config[$it['name']] = $param;
    }
    $data['configText'] = json_encode($config);
} else {
    $data['isCreate'] = true;
}

$categoryDB = new categoryExe($db);
$allCates = $categoryDB->getAllCategory();

//get all category in test case.
if (!empty($data['caseId'])) {
    $caseCategoryDB = new caseCategoryExe($db);
    $categoryInCase = $caseCategoryDB->query(array(
        "caseId" => $caseId
    ));
    $allCatesLength = count($allCates);
    $n = count($categoryInCase);
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $allCatesLength; $j++) {
            if ($categoryInCase[$i]['category_id'] == $allCates[$j]['category_id']) {
                $allCates[$j]['on'] = true;
            }
        }
    }
}


$data['allCates'] = $allCates;

$body = array(
    array("grid" => "u-1", "T" => "case/editForm/editForm.hb.html", "dataObject" => $data),
);

