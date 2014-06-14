<?php
require_once PATH_WEB . "/lib/reportExe.php";


$reportExe = new reportExe($db);
$reportGroup = $reportExe->queryReportGroup(array(
    "pageSize" => 10,
));


$reportData = dataUtil::convertReportGroup($reportGroup);

$data = array(
    "report" => $reportData,
);

$body = array(
    array("grid" => "u-1", "T" => "report/report/report.hb.html", "dataObject" => $data),

);


