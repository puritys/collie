<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/reportExe.php";
require_once PATH_WEB . "/lib/configExe.php";


$safeInputs = basicUtil::filterInputs('id');

$caseExe = new caseExe($db);
$reportExe = new reportExe($db);

$id = intval($safeInputs['id']);
$args = array(
    "id" => $safeInputs['id'],
);

$res = $caseExe->getCase($args);
$case = $res[0];

$safeCookies = basicUtil::filterInputs('user-setting', 'cookie'); 

$configDB = new configExe($db);

if (!empty($safeCookies['user-setting'])) {
    $configId = $safeCookies['user-setting'];
    $config = $configDB->getConfig(array("id" => $configId));
} else {
    $config = $configDB->getConfig(array("pageSize" => 1));

}

$dirname = 'case_' . time();

$reportGroupId = $reportExe->insertReportGroup(array(
    "name" => $case['title'],
    "dirname" => $dirname,
    "job_type" => 'case',
    "job_id" => $case['case_id'],
));


echo <<<HTML
<!DOCTYPE html>
<html>
<head>    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="css/log.css" rel="stylesheet" type="text/css" />
</head>
<body>
HTML;

echo <<<HTML
<h1 class="page-header">Start Test</h1>
<div class="bs-callout bs-callout-danger">
    PATH: $dirname
</div>
HTML;
flush();



$testResult = $caseExe->runAutomationCase($res[0], json_decode($config[0]['config'], true), $dirname, "report");
flush();

echo UILogUtil::testResult($testResult['total'], $testResult['passed'], $testResult['failed']);
echo UILogUtil::testReport($testResult);

//print_r($testResult);

//save log to report db.
//$reportExe->insertReport(array(
//    "executeId" => $reportGroupId, 
//    "caseId" => $id,
//    "name" => $case['title'],
//    "dirname" => $testResult['dirname'],
//    "passed" => $testResult['passed'],
//    "failed" => $testResult['failed'],
//));
//
//$reportExe->updateReportGroup(array(
//    "executeId" => $reportGroupId,
//    "passed" => $testResult['passed'],
//    "failed" => $testResult['failed'],
//
//));
flush();

echo <<<HTML
</pre>
</body>
</html>
HTML;



exit(1);

