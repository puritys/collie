<?php
require_once PATH_WEB . "/lib/dataUtil.php";
require_once PATH_WEB . "/lib/configExe.php";
require_once PATH_WEB . "/lib/handlebar/template.php";
$templateObj = new template('/tmp/template_cache');

$menu = array();

$on = false; if ($page == "homepage")  $on = true;
$menu[] = array("name" => "Home", "link" => URL_HOME . "/", "on" => $on);
$on = false; if ($page == "caseList")  $on = true;
$menu[] = array("name" => "Case List", "link" => URL_HOME . "/index.php?page=caseList", "on" => $on);
$on = false; if ($page == "category")  $on = true;
$menu[] = array("name" => "Category", "link" => URL_HOME . "/index.php?page=category", "on" => $on);
$on = false; if (preg_match('/^setting/', $page))  $on = true;
$menu[] = array("name" => "Setting", "link" => URL_HOME . "/index.php?page=settingList", "on" => $on);
$on = false; if ($page == "readme")  $on = true;
$menu[] = array("name" => "Readme", "link" => URL_HOME . "/index.php?page=readme", "on" => $on);


$configDB = new configExe($db);
$configs = $configDB->getConfig(array("order" => "config_id:ASC"));
$configs = dataUtil::configToHandlebar($configs);

if (isset($_COOKIE['user-setting'])) {
    $selectConfigId = basicUtil::filterInput($_COOKIE['user-setting']);
} else {
    $selectConfigId = "";
}

$n = count($configs);
for ($i = 0; $i < $n; $i++) {
    if (!empty($selectConfigId) && $configs[$i]['value']['config_id'] == $selectConfigId) {
        $settingDefaultName = $configs[$i]['value']['name'];
        $configs[$i]['isSelected'] = true;
    }
}

if (empty($settingDefaultName) && isset($configs[0]['value'])) {
    $settingDefaultName = $configs[0]['value']['name'];
    $configs[0]['isSelected'] = true;
    setcookie('user-setting', $configs[0]['value']['config_id']); 
}
$headerData = array(
    "menu" => $menu, 
    "setting" => $configs,
);


$header = array(
    array("grid" => "u-1", "T" => "common/header/header.hb.html", "dataObject" => $headerData)
);

$footer = array(
    array("grid" => "u-1", "T" => "common/footer/footer.hb.html", "dataObject" => array())
);

