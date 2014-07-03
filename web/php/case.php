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

$settingId = cookieHandler::get('user-setting');
$settingId = basicUtil::filterInput($settingId); 

$configDB = new configExe($db);
if (!empty($settingId)) {
    $config = $configDB->getConfig(array("id" => $settingId));
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
    <title>Executing automation test</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="css/log.css" rel="stylesheet" type="text/css" />
</head>
<body>
HTML;

$settingName = $config[0]['name'];
$settingId = $config[0]['config_id'];
$date = date("Y/m/d H:i:s");
$config = json_decode($config[0]['config'], true);
$config['configName'] = $settingName;

$logFile = $caseExe->getLogFile($config, $dirname, "report");

$html = <<<HTML
<h1 class="page-header">${case['title']}</h1>

<table class="table-display">
<tr>
    <td class="title">Description ：</td>
    <td>$descript</td>
</tr>
<tr>
    <td class="title">Executing Date ：</td>
    <td>$date</td>
</tr>
<tr>
    <td class="title">Environment Setting ：</td>
    <td><a href="index.php?page=settingList&action=read&id=$settingId" target="_blank">$settingName</a></td>
</tr>
</table>

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

