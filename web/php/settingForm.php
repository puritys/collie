<?php
require_once PATH_WEB . "/lib/configExe.php";
require_once PATH_WEB . "/lib/dataUtil.php";




$defaultConfig = json_decode(file_get_contents(PATH_CONTROLLER_DEAFULT_SETTING), true);
if (json_last_error()) {
    echo "default config json_decode is failed.";
    exit(1);
}



$data = array(
    "action" => "new",
);

$configInput = array();

foreach ($defaultConfig as $key => $value) {
    $configInput[$key] = $value;
}

$inputFilter = basicUtil::filterInputs(array('id'));

$configId = $inputFilter['id'];

if (!empty($configId)) {
    $configDB = new configExe($db);
    $config = $configDB->getConfig(array("id" => $configId));
    if (!isset($config[0])) {
        echo "This config is not exist.";
        exit(1);
    }
    $data['action'] = "edit";
    $data['id'] = $configId;

    $data['name'] = $config[0]['name'];
    $configList = json_decode($config[0]['config'], true);
    foreach ($configList as $key => $value) {
        if (empty($key)) continue;
        $configInput[$key] = $value;
    }

} else {
    $data['isCreate'] = true;
}

$configInput = dataUtil::configToHandlebar($configInput);

$data['configInput'] = $configInput;

$body = array(
    array("grid" => "u-1", "T" => "setting/editForm/editForm.hb.html", "dataObject" => $data),
);

