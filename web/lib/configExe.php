<?php

require_once "configSql.php";
class configExe extends configSql 
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getConfig ($args = "") 
    {
        return $this->queryConfig($args);
        
    }

}
