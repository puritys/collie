<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/reportExe.php";
require_once PATH_WEB . "/lib/configExe.php";


$safeInputs = basicUtil::filterInputs(id, 'GET');

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
    $config = $configDB->getConfig(array("id" => $safeCookies['user-setting']));
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

$descript = $case['content'];
$descript = preg_replace('/[\n\r]/', '<br />', $descript);
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

$settingName = $config[0]['name'];
$date = date("Y/m/d H:i:s");
$config = json_decode($config[0]['config'], true);
$logFile = $caseExe->getLogFile($config, $dirname, "report");

$html = <<<HTML
<h1 class="page-header">Case Name : ${case['title']}</h1>

<p>Case Description: $descript</p>

<p><b>Test Date: "$date"</b></p>
<p>Your setting is "$settingName"</p>

<p class="hide-text">
    The Result Path: $dirname
</p>
HTML;
echo $html;

file_put_contents($logFile, $html, FILE_APPEND);

@flush();


$testResult = $caseExe->runAutomationCase($res[0], $config, $dirname, "report");



//save log to report db.
$reportExe->insertReport(array(
    "executeId" => $reportGroupId, 
    "caseId" => $id,
    "name" => $case['title'],
    "dirname" => $testResult['dirname'],
    "passed" => $testResult['passed'],
    "failed" => $testResult['failed'],
));

$reportExe->updateReportGroup(array(
    "executeId" => $reportGroupId,
    "passed" => $testResult['passed'],
    "failed" => $testResult['failed'],

));

echo <<<HTML
</pre>
</body>
</html>
HTML;



exit(1);

