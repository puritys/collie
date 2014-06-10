<?php
require_once PATH_WEB . "/lib/configExe.php";



$configDB = new configExe($db);

$inputFilter = basicUtil::filterInputs(array('id'));

$data = array(
    "configId" => $inputFilter['id'],
);


$configDB->removeConfig($data);


header("location: " . $_SERVER['HTTP_REFERER']);
exit(1);
