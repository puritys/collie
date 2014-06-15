<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/reportExe.php";
require_once PATH_WEB . "/lib/configExe.php";


$safeInputs = basicUtil::filterInputs('id');
$reportExe = new reportExe($db);

$id = intval($safeInputs['id']);


$status = $reportExe->checkTestsStatus($id);
echo $status;
exit(1);
