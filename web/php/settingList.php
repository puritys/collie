<?php
require_once PATH_WEB . "/lib/configExe.php";



$configDB = new configExe($db);

if (isset($_GET['action'])) {
    $action = basicUtil::filterInput($_GET['action']);
}

if (isset($_GET['id'])) {
    $id = basicUtil::filterInput($_GET['id']);
}


if (!empty($action) && "read" == $action) {
    $res = $configDB->getConfig(array("id" => $id));
    echo "<pre>";
    print_r(json_decode($res[0]['config'], true));
    echo "</pre>";
    exit(1);
} else {

    $res = $configDB->getConfig();


}

$body = array(
    array("grid" => "u-1", "T" => "setting/list/list.hb.html", "dataObject" => array("setting" => $res)),
);

