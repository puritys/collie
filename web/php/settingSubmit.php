<?php
require_once PATH_WEB . "/lib/configExe.php";

$defaultConfig = json_decode(file_get_contents(PATH_CONTROLLER_DEAFULT_SETTING), true);
if (json_last_error()) {
    echo "default config json_decode is failed.";
    exit(1);
}


$configDB = new configExe($db);

$inputFilter = basicUtil::filterInputs(array('action', 'name', 'configId'), "POST");

$action = $inputFilter['action'];
$name = $inputFilter['name'];

$data = array(
    "name" => $name,
);

$config = array();
foreach ($_POST as $key => $value) {
    if (!preg_match('/^config\-/', $key)) continue;
    $realKey = preg_replace('/^config\-/', '', $key);
    if (empty($realKey)) continue;

    $filterValue = basicUtil::filterInput($_POST[$key]);
    $config[$realKey] = $filterValue;
}

foreach ($config as $key => $value) {
    if (!isset($defaultConfig[$key])) {
        unset($config[$key]);
//        echo "Key of config is not exist.";
//        exit(1);
    }
}


$data['config'] = json_encode($config); 

if ("new" == $action) {
    $configDB->insertConfig($data);

} else if ("edit" == $action) {
    $configId = $inputFilter['configId'];
    $data['configId'] = $configId;
    $configDB->updateConfig($data);

}


header("location: index.php?page=settingList");
exit(1);
