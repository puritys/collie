<?php

require_once "lib/i18n/i18n.class.php";
$lang = basicUtil::filterInput($_GET['lang'], 'variable');
if (!empty($lang)) {
    cookieHandler::set('lang', $lang);
} else {
    $lang = cookieHandler::get('lang');
}
$lang = basicUtil::filterInput($lang, 'variable');
if (empty($lang)) $lang = 'en';
$_GET['lang'] = $lang;

$i18n = new i18n(dirname(__FILE__) . '/../lang/lang_{LANGUAGE}.ini', '/tmp/langcache', 'en');
$i18n->init();

$languages = array();
$languages[] = array(
    "name" => "English",
    "value" => "en",
);
$languages[] = array(
    "name" => "繁體中文",
    "value" => "zh-TW",
);

foreach ($languages as &$lang2) {
    if ($lang2['value'] == $lang) {
        $lang2['isSelected'] = true;
        break;
    }
}
