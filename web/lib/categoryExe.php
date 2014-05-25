<?php
require_once "categorySql.php";
class categoryExe extends categorySql
{

    public function getCategory($args = "")
    {
        $res = $this->queryCategory($args);

        return $res;
    }

    /**
    * get all row of category.  We will show the all category that user need to select.
    */
    public function getAllCategory($args = array())
    {
        $args["pageSize"] = 999999;
        $res = $this->queryCategory($args);

        return $res;


    }

}
