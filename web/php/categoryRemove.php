<?php
require_once PATH_WEB . "/lib/categoryExe.php";


$safeInputs = basicUtil::filterInputs('id');

$data = array(
    "id" => $safeInputs['id'],
);


$categoryDB = new categoryExe($db);
$res = $categoryDB->removeCategory($data);


header("location: " . html_entity_decode($_SERVER['HTTP_REFERER']));
exit(1);
