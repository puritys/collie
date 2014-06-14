<?php
require_once PATH_WEB . "/lib/reportExe.php";

$reportDB = new reportExe($db);

$safeInputs = basicUtil::filterInputs('id,job_type,readType', "GET");

$id = $safeInputs['id'];
$job_type = $safeInputs['job_type'];
$readType = $safeInputs['readType'];



if (!empty($job_type) && $job_type == "case") {
    $reportGroup = $reportDB->queryReportGroup(array(
        "executeId" => $id,
        "job_type" => $job_type,
    ));
    if (!isset($reportGroup[0])) {
        echo " Report not found.";
        exit(1);
    }

    $reportName = $reportGroup[0]['name'];

    $report = $reportDB->queryReport(array(
        "executeId" => $id,
        "job_type" => $job_type,
    ));
} else if (!empty($readType) && $readType == "report") {
    $report = $reportDB->queryReport(array(
        "reportId" => $id,
        "job_type" => 'category',
    ));
    if (!isset($report[0])) {
        echo " Report not found.";
        exit(1);
    }

    $reportName = $report[0]['name'];

}

$dirname = $report[0]['dirname'];

//print_r($report);
$logFile = PATH_DIR_RUN . "/" . $dirname . '/log';
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
<h1 >$reportName</h1>
<h4 style="margin: 20px 0;">Log File Path: $logFile</h4>
HTML;

@readFile($logFile);

echo <<<HTML
</body>
</html>
HTML;

exit(1);