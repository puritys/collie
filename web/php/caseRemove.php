<?php
require_once PATH_WEB . "/lib/caseExe.php";


$safeInputs = basicUtil::filterInputs('id');

$data = array(
    "caseId" => $safeInputs['id'],
);


$caseDB = new caseExe($db);
$res = $caseDB->removeCase($data);


header("location: " . $_SERVER['HTTP_REFERER']);
exit(1);
