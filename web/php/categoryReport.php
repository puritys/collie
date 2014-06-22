<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/categoryExe.php";
require_once PATH_WEB . "/lib/reportExe.php";


$data = array("report" => array());

$reportGroupId = basicUtil::filterInput($_GET['reportGroupId']);

$caseExe = new caseExe($db);
$reportExe = new reportExe($db);
$categoryExe = new categoryExe($db);



$reportGroup = $reportExe->queryReportGroup(array(
    "job_type" => 'category',
    "executeId" => $reportGroupId,
));
if (!isset($reportGroup[0])) die("Missing report group ");
$reportGroup = $reportGroup[0];
$categoryId = $reportGroup['job_id'];
$data['reportName'] = $reportGroup['name'];

$data['passed'] = $reportGroup['passed_case_num'];
$data['failed'] = $reportGroup['failed_case_num'];
$data['total'] = $reportGroup['passed_case_num'] + $reportGroup['failed_case_num'];


$reportList = $reportExe->queryReport(array(
    "executeId" => $reportGroupId, 
));

$n = count($reportList);
for ($i = 0; $i < $n; $i++) {
    if ($reportList[$i]['status'] != 'none') $reportList[$i]['showReport'] = true;
    $key = dataUtil::convertReportStatusByStatus($reportList[$i]['status']);
    if ($key) $reportList[$i][$key] = true;

    $reportList[$i]['urlLastReport'] = 'index.php?page=readlog&id='.$reportList[$i]['report_id']. '&readType=report';
    $data['report'][] = $reportList[$i];

}


if (!empty($reportGroup['passed_case_num']) || !empty($reportGroup['failed_case_num'])) {
    require_once PATH_WEB . '/lib/jpgraph/jpgraph.php';
    require_once PATH_WEB . '/lib/jpgraph/jpgraph_pie.php';


    $dataChart = array($reportGroup['passed_case_num'], $reportGroup['failed_case_num']);

    // Create the Pie Graph. 
    $graph = new PieGraph(350,250);

    $theme_class="DefaultTheme";
    //$graph->SetTheme(new $theme_class());

    // Set A title for the plot
    $graph->title->Set("Test Result");
    $graph->SetBox(true);

    // Create
    $p1 = new PiePlot($dataChart);
    $graph->Add($p1);

    $p1->ShowBorder();
    $p1->SetColor('black');
    $p1->SetSliceColors(array('#1Ecc11','#cc1111'));
    if (!is_dir(PATH_DIR_RUN . "/reportChart")) {
        mkdir(PATH_DIR_RUN . "/reportChart");
    }
    $img = $graph->Stroke(PATH_DIR_RUN . "/reportChart/char_$reportGroupId.png");
    $data['chart'] = URL_DIR_RUN . "/reportChart/char_$reportGroupId.png";
}

$body = array(
    array("grid" => "u-1", "T" => "report/list/list.hb.html", "dataObject" => $data),
);

