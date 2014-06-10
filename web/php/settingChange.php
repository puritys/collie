<?php


$settingId = basicUtil::filterInput($_GET['user-setting']);
$settingId = intval($settingId);

setcookie('user-setting', $settingId, time() + 86400*30);


header("location: " . $_SERVER['HTTP_REFERER']);
exit(1);
