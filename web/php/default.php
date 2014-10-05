<?php
require_once PATH_WEB . "/lib/dataUtil.php";
require_once PATH_WEB . "/lib/configExe.php";
require_once PATH_WEB . "/lib/handlebar/template.php";
require_once PATH_WEB . "/php/lang.php";

$templateObj = new template('/tmp/template_cache');
if (!is_dir('/tmp/langcache')) mkdir('/tmp/langcache');


$menu = array();

$on = false; if ($page == "homepage")  $on = true;
$menu[] = array("name" => L::home, "link" => URL_HOME . "/", "on" => $on);
$on = false; if ($page == "caseList")  $on = true;
$menu[] = array("name" => L::case_list, "link" => URL_HOME . "/index.php?page=caseList", "on" => $on);
$on = false; if ($page == "category")  $on = true;
$menu[] = array("name" => L::category, "link" => URL_HOME . "/index.php?page=category", "on" => $on);
$on = false; if (preg_match('/^setting/', $page))  $on = true;
$menu[] = array("name" => L::env_setting, "link" => URL_HOME . "/index.php?page=settingList", "on" => $on);
$on = false; if ($page == "readme")  $on = true;
$menu[] = array("name" => L::readme, "link" => URL_HOME . "/index.php?page=readme", "on" => $on);


$configDB = new configExe($db);
$configsRaw = $configDB->getConfig(array("order" => "config_id:ASC"));
$configs = dataUtil::configToHandlebar($configsRaw);

$userSetting = cookieHandler::get('user-setting');
if (!empty($userSetting)) {
    $selectConfigId = basicUtil::filterInput($userSetting);
} else {
    $selectConfigId = "";
    if (isset($configsRaw[0]['config_id'])) {
        cookieHandler::set('user-setting', $configsRaw[0]['config_id']);
    }
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
    "languages" => $languages,
);


$header = array(
    array("grid" => "u-1", "T" => "common/header/header.hb.html", "dataObject" => $headerData)
);

$footer = array(
    array("grid" => "u-1", "T" => "common/footer/footer.hb.html", "dataObject" => array())
);

