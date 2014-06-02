<?php
//require_once "caseSql.php";

class controllerExe // extends caseSql 
{
    public function __construct($db = "")
    {
        $this->controllerList = json_decode(file_get_contents(PATH_CONTROLLER_LIST), true);
    }

    public function getControllerSetting($name) {
        foreach ($this->controllerList as $cname => $val) {
            if ($name == $cname) {
                $val['classname'] = $cname . 'Controller';
                $val['filePath'] =  PATH_CONTROLLER .'/'. $val['filePath'];
                return $val;
            }
        }
        return false;
    }

}
