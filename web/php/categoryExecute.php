<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/categoryExe.php";
require_once PATH_WEB . "/lib/caseCategoryExe.php";
require_once PATH_WEB . "/lib/reportExe.php";

$configDB = new configExe($db);
$configId = cookieHandler::get('user-setting');
$categoryId = basicUtil::filterInput($_GET['id']);
$configName = basicUtil::filterInput($_GET['settingName']);
if (!empty($configName)) {
    $config = $configDB->getConfig(array("name" => $configName));
} else {
    if (empty($configId)) {
        echo "Missing setting";
        exit(1);
    }

    $config = $configDB->getConfig(array("id" => $configId));
}

if (empty($config)) {
    echo "Unused setting id.";
    exit(1);
}

$caseExe = new caseExe($db);
$reportExe = new reportExe($db);
$categoryExe = new categoryExe($db);
$caseCategoryExe = new caseCategoryExe($db);

$caseList = $caseCategoryExe->query(array(
    "categoryId" => $categoryId
));


$n = count($caseList);
if ($n <= 0) {
    echo "This category has not any test case.";
    exit(1);
}

$catData = $categoryExe->queryCategory(array(
    "id" => $categoryId
));

if (!isset($catData[0])) { echo "Missing category"; exit(1);}

$dirname = 'category_' . time();

$catData = $catData[0];
//create executed group
$reportGroupId = $reportExe->insertReportGroup(array(
    "name" => $catData['name'],
    "dirname" => $dirname,
    "job_type" => 'category',
    "job_id" => $catData['category_id'],
));

//create case to run
for ($i = 0; $i < $n; $i++) {
    $caseId = $caseList[$i]['case_id'];
    $case = $caseExe->getCase(array(
        "id" => $caseId
    ));
    if (!isset($case[0])) {
        //remove case that had been deleted.
        $caseCategoryExe->remove(array(
            "caseId" => $caseId,
            "id" => $caseList[$i]['id'],
        )); 
        continue;
    }
    $case = $case[0];
    $testDirname = $dirname. '/case_' . $case['case_id'];
    $reportExe->insertReport(array(
        "executeId" => $reportGroupId, 
        "caseId" => $caseId,
        "name" => $case['title'],
        "dirname" => $testDirname,
        "passed" => 0,
        "failed" => 0,
        "configId" => $config[0]['config_id'],
    ));

}

//redirect to read execute report
header("location: index.php?page=categoryReport&reportGroupId=" . $reportGroupId );
flush();

//Run Case (Fixme  background or crontab).
//$configId = basicUtil::filterInput($_COOKIE["user-setting"]);
//$configId = intval($configId);
//$configDB = new configExe($db);
//
//if (!empty($configId)) {
//    $config = $configDB->getConfig(array("id" => $configId));
//} else {
//    $config = $configDB->getConfig(array("pageSize" => 1));
//
//}
//
//$unRunReport = $reportExe->queryReport(array(
//    "executeId" => $reportGroupId, 
//));
//$n = count($unRunReport);
//
//$totalResult = array(
//    "passed" => 0,
//    "failed" => 0,
//);
//$readLog = false;
//for ($i = 0 ; $i < $n; $i++) {
//    $reportId = $unRunReport[$i]['report_id'];
//    $caseId = $unRunReport[$i]['case_id'];
//
//    //get Case
//    $case = $caseExe->getCase(array(
//        "id" => $caseId
//    ));
//
//    if (!isset($case[0])) {
//        error_log("unused id " . $caseId);
//        continue;
//    }
//
//    $case = $case[0];
//
//    $reportExe->updateReport(array(
//        "reportId" => $reportId,
//        "status" => "running",
//    ));
//   
//    $testResult = $caseExe->runAutomationCase($case, json_decode($config[0]['config'], true), $unRunReport[$i]['dirname'], "", $readLog);
//    $reportExe->updateReport(array(
//        "reportId" => $reportId,
//        "passed" => $testResult['passed'],
//        "failed" => $testResult['failed'],
//    ));
//    $totalResult['passed'] += intval($testResult['passed']);
//    $totalResult['failed'] += intval($testResult['failed']);
//    $reportExe->updateReportGroup(array(
//        "executeId" => $reportGroupId,
//        "passed" => $totalResult['passed'],
//        "failed" => $totalResult['failed'],
//    ));
//}
//
