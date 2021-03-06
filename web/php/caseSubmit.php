<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/controllerExe.php";
require_once PATH_WEB . "/lib/caseCategoryExe.php";



$caseDB = new caseExe($db);
$controllerExe = new controllerExe($db);

$action = basicUtil::filterInput($_POST['action']);
$title = basicUtil::filterInput($_POST['title']);
$content = basicUtil::filterInput($_POST['content']);
$descriptor = json_decode(trim(html_entity_decode($_POST['descriptor'])), true);
$category = $_POST['category'];
$n = count($category);
for ($i = 0; $i < $n; $i++) {
    $category[$i] = basicUtil::filterInput($category[$i]);
}

$referer = html_entity_decode(basicUtil::filterInput($_POST['referer']));

$i = 0;
$index = $_POST['index'];
foreach ($descriptor['scenario'] as &$controller) {
    $id = $controller['id'];
    $params = array();
    if ($conSetting = $controllerExe->getControllerSetting($id)) {
        require_once $conSetting['filePath'];
        $con = new $conSetting['classname']("", "", "");
        $form = $con->formParam;
        foreach($form as $key => $v) {
            $fieldName = $id. '_' . $index[$i] . '_' . $key;
            $value = basicUtil::filterInput(html_entity_decode($_POST[$fieldName]));
            $params[$key] = $value;
        }
        $controller['params'] = $params;
    } else {
        unset($controller);
    }
    $i++;
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

