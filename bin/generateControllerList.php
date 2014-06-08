<?php



require_once dirname(__FILE__) . "/../web/config.php";

function arguments($argv) { 
    $ARG = array();
    $n = count($argv);
    for ($i = 0; $i < $n ; $i++) {
        $val = $argv[$i];
        if (preg_match('/^[\-]{1,2}([^=]+)=[\s]*([^\s]+)/', $val, $match)) {
            $ARG[$match[1]] = $match[2];
        } else if (preg_match('/^[\-]{1,2}([^=\s]+)$/', $val, $match)) {
            $i++;
            $ARG[$match[1]] = $argv[$i];
        }
    }
    return $ARG; 
} 


$argv = arguments($_SERVER['argv']);



function getController($dir, &$controller = array()) {
    $fh = opendir($dir);
    while (false !== ($file = readdir($fh))) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (is_dir($dir . '/' .$file)) {
            getController($dir . '/' . $file, $controller);
        } else if (preg_match('/Controller\.php$/i', $file)) {
            if (preg_match('/collieBasicC/', $file)) continue; //It is basic class
            $name = preg_replace('/Controller\.php/', '', $file);
            $controller[$name] = array(
                "filePath" => $dir . '/' .$file,
            );
        }
    }
}


$controller = array();
getController(PATH_CONTROLLER, $controller);

file_put_contents(PATH_CONTROLLER_LIST, json_encode($controller));
//print_r($controller);






