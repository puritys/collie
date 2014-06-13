<?php
require_once PATH_WEB . "/lib/dataUtil.php";
require_once PATH_WEB . "/lib/categoryExe.php";
require_once PATH_WEB . "/lib/caseCategoryExe.php";
require_once PATH_WEB . "/lib/reportExe.php";



$args = array();

$safeInputs = basicUtil::filterInputs('currentPage, searchText');

$currentPage = $safeInputs['currentPage'];

if (empty($currentPage)) $currentPage = 1;

$args['page'] = $currentPage;

if (!empty($safeInputs['searchText'])) {
    $searchText = $safeInputs['searchText'];
} else {
    $searchText = "";
}

if ($searchText) {
    $args['searchText'] = $searchText;
}


$reportExe = new reportExe($db);
$categoryDB = new categoryExe($db);
$res = $categoryDB->getCategory($args);
$parentIds = array();
foreach ($res as $it) {
    $parentIds[] = $it['category_id'];
}


$data = dataUtil::convertCategory($res);
if (!empty($parentIds)) {
    $parentCates = $categoryDB->getCategory(array(
        "id" => $parentIds
    ));
    $n = count($res);
    $parentCatesLength = count($parentCates);
    for ($i = 0; $i < $n ; $i++) {
        for ($j = 0; $j < $parentCatesLength; $j++) {
            if ($parentCates[$j]['category_id'] == $data[$i]['parentId']) {
                $data[$i]['parentName'] = $parentCates[$j]['name'];
                break;
            }
        }
    }
}

//Get Case Quanaity
$caseCategoryDB = new caseCategoryExe($db);
$n = count($data);
for ($i = 0; $i < $n; $i++) {
    $data[$i]['caseQty'] = $caseCategoryDB->query(array(
        "categoryId" => $data[$i]['categoryId'],
        "isCount" => true,
    ));
    $data[$i]['urlCaseRun'] = "index.php?page=categoryExecute&action=run&id=" . $data[$i]['categoryId']. "&jobType=category";
}
//Get Last Report
$n = count($data);
for ($i = 0; $i < $n; $i++) {
    $report = $reportExe->queryReportGroup(array(
        "categoryId" => $data[$i]['categoryId'],
    ));
    if (isset($report[0])) {
        $data[$i]['executeId'] = $report[0]['execute_id'];
        $data[$i]['urlLastReport'] = "index.php?page=categoryReport&reportGroupId=" . $report[0]['execute_id']. "&jobType=category";
        $key = dataUtil::convertReportStatus($report[0]);
        if ($key) $data[$i][$key] = true;

    }
}


//print_r($data);
//get pages
$args['isCount'] = true;
$count = $categoryDB->getCategory($args);
$pages = dataUtil::convertPages(array(
    "currentPage" => $currentPage,
    "pageSize" => 10,
    "quantity" => $count,
    "link" => $_SERVER['REQUEST_URI']
));



$categoryData = array(
     "list" => $data,
     "pages" => $pages,
);

$body = array(
    array("grid" => "u-1", "T" => "case/categoryList/categoryList.hb.html", "dataObject" => $categoryData),
);

