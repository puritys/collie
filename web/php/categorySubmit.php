<?php
require_once PATH_WEB . "/lib/categoryExe.php";


$categoryDB = new categoryExe($db);
$safeInputs = basicUtil::filterInputs('name,parentId,action,referer,id', 'POST');


$data = array(
    "name" => $safeInputs['name'],
    "parentId" => $safeInputs['parentId'] ,

);


$action = $safeInputs['action'];

if (!empty($action) && $action == "edit") {
    $data['id'] = $safeInputs['id'];
    $categoryDB->updateCategory($data);
} else {
    $categoryDB->insertCategory($data);

}

$referer = html_entity_decode($safeInputs['referer']);

if (!empty($referer)) {
    header("Location: " . $referer);
    exit(1);
} else {
    header("location: index.php?page=category");
    exit(1);
}
