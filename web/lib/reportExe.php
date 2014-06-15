<?php

require_once "reportSql.php";

class reportExe extends reportSql {

    /**
    * status 0:none, 1:success, 2:fail, 
    */
    public function checkTestsStatus($id) {
        $status = 1;
        $args = array(
            "executeId" => $id,
            "job_type" => "category",
        );
        $res = $this->queryReport($args);
        foreach ($res as $row) {
            if ($row['status'] == "running" || $row['status'] == "none") {
                $status = 0;
                return $status;
            } else if ($row['status'] == "failed" || $row['status'] == "some_failed") {
                $status = 2;
            }

        }

        return $status;
    }
}
