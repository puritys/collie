<?php

require_once dirname(__FILE__) . "/../config.php";
require_once PATH_WEB . "/lib/basicUtil.php";
require_once PATH_WEB . "/lib/caseExe.php";
//require_once PATH_WEB . "/lib/categoryExe.php";
//require_once PATH_WEB . "/lib/caseCategoryExe.php";
require_once PATH_WEB . "/lib/reportExe.php";
require_once PATH_WEB . "/lib/configExe.php";

ini_set('date.timezone', TIMEZONE);
$db = new PDO('mysql:host=' . MYSQL_HOST .';dbname=' . MYSQL_DB, MYSQL_USER, MYSQL_PSWD);

$caseExe = new caseExe($db);
$reportExe = new reportExe($db);

//$configId = basicUtil::filterInput($_COOKIE["user-setting"]);
//$configId = intval($configId);
$configDB = new configExe($db);


$runningReport = $reportExe->queryReport(array(
    "status" => "running", 
));

if (count($runningReport) > 1) {
    //skip too old report.
    $caseRunningExpiredSeconds = 3600;
    foreach ($runningReport as $case) {
        $time = strtotime($case['create_time']);
        if ($time < (time() - $caseRunningExpiredSeconds)) {
            $reportExe->updateReport(array(
                "reportId" => $case['report_id'],
                "status" => "failed",
            ));

        }
    }
    error_log("Some casees were running now. Waiting for it.");
    exit(1);
}

$unRunReport = $reportExe->queryReport(array(
    "status" => "none", 
));

$n = count($unRunReport); 

//print_r($unRunReport);
// Run two case each time.

$totalResult = array(
    "passed" => 0,
    "failed" => 0,
);
//print_r($unRunReport);

for ($i = 0, $j = 0 ; $i < $n && $j < 5; $i++) {
    $reportId = $unRunReport[$i]['report_id'];
    $caseId = $unRunReport[$i]['case_id'];
    $reportGroupId = $unRunReport[$i]['execute_id'];

    $config = $configDB->getConfig(array("id" => $unRunReport[$i]['config_id']));

    //get Case
    $case = $caseExe->getCase(array(
        "id" => $caseId
    ));

    if (!isset($case[0])) {
        error_log("unused id " . $caseId);
        continue;
    }
    $j++;
    $case = $case[0];

    $reportExe->updateReport(array(
        "reportId" => $reportId,
        "status" => "running",
    ));

    $testResult = $caseExe->runAutomationCase($case, json_decode($config[0]['config'], true), $unRunReport[$i]['dirname'], "", false);
    $reportExe->updateReport(array(
        "reportId" => $reportId,
        "passed" => $testResult['passed'],
        "failed" => $testResult['failed'],
    ));

    $reportExe->updateReportGroup(array(
        "executeId" => $reportGroupId,
        "add_passed" => $testResult['passed'],
        "add_failed" => $testResult['failed'],
    ));
}
