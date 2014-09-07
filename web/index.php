<?php
require_once "config.php";
require_once "lib/basicUtil.php";
require_once "lib/cookieHandler.php";


ini_set('date.timezone', TIMEZONE);

$db = new PDO('mysql:host=' . MYSQL_HOST .';dbname=' . MYSQL_DB, MYSQL_USER, MYSQL_PSWD);
//$db->exec("SET CHARACTER SET utf8");  // my.cnf: default-character-set=utf8

$isRealUser = cookieHandler::get('isRealUser');


if (empty($isRealUser)) {
    cookieHandler::set('isRealUser', 1);
    header('location: index.php');
    exit(1);
}


if (isset($_GET['page'])) {
    $page = basicUtil::filterInput($_GET['page']);
} else {
    $page = 'homepage';
}

$page = preg_replace("/[^\w]+/", "", $page);
if (strlen($page) > 30) { echo "<center>FileName illegal. </center>";exit(1);}

if (!is_file('php/' . $page . ".php")) {

    header("HTTP/1.1 404 file not found.");
    echo "<center>File not found. </center>";
    exit(1);
}

require "php/default.php";
include PATH_WEB . '/php/' .$page . ".php";


include PATH_WEB . "/views/page.phtml"; 





