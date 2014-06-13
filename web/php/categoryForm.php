<?php
require_once PATH_WEB . "/lib/dataUtil.php";
require_once PATH_WEB . "/lib/categoryExe.php";


$data = array(
    "action" => "new",
    "referer" => $_SERVER['HTTP_REFERER'],
);

$safeInputs = basicUtil::filterInputs('id');

$categoryDB = new categoryExe($db);

$categoryId = $safeInputs['id'];


if (!empty($categoryId)) {
    $categoryEditData = $categoryDB->getCategory(array("id" => $categoryId));
    $categoryEditData = dataUtil::convertCategory($categoryEditData);
    $parentId = $categoryEditData[0]['parentId'];
    $data['action'] = "edit";
    $data['category'] = $categoryEditData[0];
} else {

}

//Get All category for parent category selecting.
if (isset($categoryEditData[0])) {
    $cate = $categoryDB->getAllCategory(array("excludeId" => $categoryEditData[0]['categoryId']));
} else {
    $cate = $categoryDB->getAllCategory();
}

$cateOption = array();
dataUtil::convertCateoryOption($cate, 0, 1, $parentId, $cateOption);
$data['categoryOption'] = $cateOption;

$body = array(
    array("grid" => "u-1", "T" => "case/editCategoryForm/editCategoryForm.hb.html", "dataObject" => $data),
);

