<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/controllerExe.php";
require_once PATH_WEB . "/lib/caseCategoryExe.php";



$caseDB = new caseExe($db);
$controllerExe = new controllerExe($db);

$action = basicUtil::filterInput($_POST['action']);
$title = basicUtil::filterInput($_POST['title']);
$content = basicUtil::filterInput($_POST['content']);
$descriptor = json_decode($_POST['descriptor'], true);
$category = basicUtil::filterInput($_POST['category']);

$referer = html_entity_decode(basicUtil::filterInput($_POST['referer']));



foreach ($descriptor['scenario'] as &$controller) {
    $name = $controller['name'];
    $params = array();
    if ($conSetting = $controllerExe->getControllerSetting($name)) {
        require_once $conSetting['filePath'];
        $con = new $conSetting['classname']("", "", "");
        $form = $con->formParam;
        foreach($form as $key => $v) {
            $fieldName = $name . '_' . $key;
            $value = basicUtil::filterInput($_POST[$fieldName]);
            $params[$key] = $value;
        } 
        $controller['params'] = $params;
    } else {
        unset($controller);
    }

}

foreach ($descriptor as $key ) {
    if ($key != "scenario") {
        unset($descriptor[$key]);
    }
}


$data = array(
    "title" => $title,
    "content" => $content,  
    "descriptor" => json_encode($descriptor),
);

if ("new" == $action) {
    $caseId = $caseDB->insertCase($data);
    $caseCateDB = new caseCategoryExe($db);
    $caseCateDB->update(array(
        "caseId" => $caseId,
        "category" => $category,
    ));

    header("Location: index.php?page=caseList");
    exit(1);
} else if ("edit" == $action) {
    $caseId =  basicUtil::filterInput($_POST['caseId']);
    $data['caseId'] = $caseId;
    $caseDB->updateCase($data);

    $caseCateDB = new caseCategoryExe($db);
    $caseCateDB->update(array(
        "caseId" => $caseId,
        "category" => $category,
    ));

    header("Location: " . $referer);
    exit(1);

}

