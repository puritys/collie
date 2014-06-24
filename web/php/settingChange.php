<?php


$settingId = basicUtil::filterInput($_GET['user-setting']);
$settingId = intval($settingId);

cookieHandler::set('user-setting', $settingId);

header("location: " . $_SERVER['HTTP_REFERER']);
exit(1);
