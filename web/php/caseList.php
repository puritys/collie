<?php
require_once PATH_WEB . "/lib/caseExe.php";
require_once PATH_WEB . "/lib/reportExe.php";


$caseDB = new caseExe($db);

$args = array();
if (isset($_GET['currentPage'])) {
    $currentPage = basicUtil::filterInput($_GET['currentPage']);
}


if (empty($currentPage)) $currentPage = 1;
$args['page'] = $currentPage;

if (!empty($_GET['searchText'])) {
    $searchText = basicUtil::filterInput($_GET['searchText']);
} else {
    $searchText = "";
}

if (!empty($searchText)) {
    $args['searchText'] = $searchText;
}

$case = $caseDB->getCase($args);

$args['isCount'] = true;
$caseCount = $caseDB->getCase($args);
$pages = dataUtil::convertPages(array(
    "currentPage" => $currentPage,
    "pageSize" => 10,
    "quantity" => $caseCount,
    "link" => $_SERVER['REQUEST_URI']
));

$reportDB = new reportExe($db);
//get lastest report
$n = count($case);
for ($i = 0 ; $i < $n; $i++) {
    $reports = $reportDB->queryReportGroup(array(
        "caseId" => $case[$i]['case_id'],
        "job_type" => "case",
    ));
    if (isset($reports[0])) {
        $case[$i]['reportId'] = $reports[0]['execute_id'];
        $case[$i]['urlLastReport'] = 'index.php?page=readlog&id='.$reports[0]['execute_id']. '&job_type=case';
        $key = dataUtil::convertReportStatus($reports[0]);
        if ($key) $case[$i][$key] = true;
/*        if ($reports[0]['failed_case_num'] > 0 && $reports[0]['passed_case_num'] > 0) {
            $case[$i]['isPassSome'] = true;
        } else if ($reports[0]['failed_case_num'] > 0) {
            $case[$i]['isFail'] = true;
        } else if ($reports[0]['passed_case_num'] > 0) {
            $case[$i]['isPass'] = true;

    }*/
    }

}

if (!empty($reports)) {
    $case = dataUtil::matchCaseReport($case, $reports);
}

$caseListData = array(
        'searchText' => $searchText,
        "caseList" => $case,
        "pages" => $pages,
);

if (isset($_GET['pjax'])) {
    $pjax = basicUtil::filterInput($_GET['pjax']);
}

if ($pjax == 1) {
    $body = array(
        array("grid" => "u-1", "T" => "case/list/caseTable.hb.html", "dataObject" => $caseListData),
    );

} else {

    $body = array(
        array("grid" => "u-1", "T" => "case/list/list.hb.html", "dataObject" => $caseListData),
    );

}

