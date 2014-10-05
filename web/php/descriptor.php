<?php
exit(1);
require_once PATH_WEB . "/lib/caseExe.php";


$args = array(
    "id" => preg_replace('/[^0-9]+/', '', $_GET['id']),
);



function unicodeToString($str) {
    $str = preg_replace('/\\\u([0-9a-f]+)/i', '&#x$1;', $str);
    $str =  html_entity_decode($str, 0, 'UTF-8');
    return $str;

}


$caseDB = new caseExe($db);
$res = $caseDB->getCase($args);
$descriptor = $caseDB->createDescriptor($res[0]);

echo '<pre style="word-break: break-word;">'. unicodeToString(json_encode($descriptor)) . '</pre>';

exit(1);

