<?php

class reportSql 
{
    public $tbName = "report";
    public $tbName_group = "execute";
    public $db;
    public function __construct($db) 
    {
        $this->db = $db;
    }


    public function queryReportGroup($args = "")
    {/*{{{*/
        $page = 1;
        $limit = 10;
        if (!empty($args['pageSize'])) $limit = $args['pageSize'];
        $start = ($page - 1) * $limit;
        if (empty($args['order'])) {
            $args['order'] = "create_time:desc";
        }

        if (isset($args['caseId'])) {
            if (is_array($args['caseId'])) {
                $id = "";
                foreach ($args['id'] as $it) { 
                    if ($id) { $id .= ',';}
                    $id .= $it;
                }
                $sql = "select * from %s where execute_id in(". $id. ") and job_type=:job_type order by create_time desc";
                $sql = sprintf($sql, $this->tbName_group);
                $st = $this->db->prepare($sql);
                $st->bindValue(':job_type', $args['job_type'], PDO::PARAM_STR);

            } else {
                $sql = "select * from %s where job_id=:id and job_type=:job_type order by create_time desc limit 1";
                $sql = sprintf($sql, $this->tbName_group);
                $st = $this->db->prepare($sql);
                $st->bindValue(':id', $args['caseId'], PDO::PARAM_INT);
                $st->bindValue(':job_type', $args['job_type'], PDO::PARAM_STR);
            }

            $st->execute();
        } else if (isset($args['categoryId'])) {
            if (is_array($args['categoryId'])) {
                $id = "";
/*                foreach ($args['id'] as $it) { 
                    if ($id) { $id .= ',';}
                    $id .= $it;
                }
                $sql = "select * from %s where execute_id in(". $id. ") and job_type=:job_type order by create_time desc";
                $sql = sprintf($sql, $this->tbName_group);
                $st = $this->db->prepare($sql);
                $st->bindValue(':job_type', $args['job_type'], PDO::PARAM_STR);
*/
            } else {
                $sql = "select * from %s where job_id=:id and job_type=:job_type order by create_time desc limit 1";
                $sql = sprintf($sql, $this->tbName_group);
                $st = $this->db->prepare($sql);
                $st->bindValue(':id', $args['categoryId'], PDO::PARAM_INT);
                $st->bindValue(':job_type', "category", PDO::PARAM_STR);
            }

            $st->execute();


        } else if (isset($args['executeId'])) {
            $sql = "select * from %s where execute_id=:id and job_type=:job_type";
            $sql = sprintf($sql, $this->tbName_group);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['executeId'], PDO::PARAM_INT);
            $st->bindValue(':job_type', $args['job_type'], PDO::PARAM_STR);
            $st->execute();

        } else {
            $where = "";
            if (!empty($args['searchText'])) {
                $strs = preg_split('/[\s]+/', $args['searchText']);
                $i = 0;
                foreach ($strs as $it) {
                    if ($where) $where .= ' and ';
                    $where .= ' `name` like :search_'. $i .'';
                    $i++;
                }
            }

            if (!empty($where)) $where = " where " . $where;
            $order = explode(":", $args['order']);
            $od = " order by ". $order[0] . " " . $order[1];
            $od2 = " order by a.". $order[0] . " " . $order[1];

            $sqlP = "select execute_id from " . $this->tbName_group . " $where $od limit $start, $limit";
            $sql = "select * from `" . $this->tbName_group ."` as a inner join ($sqlP) as b on a.execute_id = b.execute_id $od2 ";
            $st = $this->db->prepare($sql);
            if (!empty($strs) && is_array($strs)) {
                $i = 0;
                foreach ($strs as $it) {
                    $st->bindValue(':search_'. $i, '%' . $it . '%', PDO::PARAM_STR);
                    $i++;
                }
            }


            $st->execute();

        }
        return $st->fetchAll(PDO::FETCH_ASSOC); 
    }/*}}}*/

    public function queryReport($args = "")
    {/*{{{*/
        $page = 1;
        $limit = 10;
        if (!empty($args['pageSize'])) $limit = $args['pageSize'];
        $start = ($page - 1) * $limit;
        if (empty($args['order'])) {
            $args['order'] = "create_time:desc";
        }

        if (isset($args['caseId'])) {
            if (is_array($args['caseId'])) {
                $id = "";
                foreach ($args['id'] as $it) { 
                    if ($id) { $id .= ',';}
                    $id .= $it;
                }
                $sql = "select * from %s where report_id in(". $id. ") order by create_time desc";
                $sql = sprintf($sql, $this->tbName);
                $st = $this->db->prepare($sql);
            }
            $st->execute();

        } else if (isset($args['executeId'])) {
            $sql = "select * from %s where execute_id=:id";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['executeId'], PDO::PARAM_INT);
            $st->execute();
        } else if (isset($args['reportId'])) {
            $sql = "select * from %s where report_id=:id";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['reportId'], PDO::PARAM_INT);
            $st->execute();
        } else if (isset($args['status'])) {
            $sql = "select * from %s where status=:status";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':status', $args['status'], PDO::PARAM_STR);
            $st->execute();

        } else {
            $where = "";
            if (!empty($args['searchText'])) {
                $strs = preg_split('/[\s]+/', $args['searchText']);
                $i = 0;
                foreach ($strs as $it) {
                    if ($where) $where .= ' and ';
                    $where .= ' `name` like :search_'. $i .'';
                    $i++;
                }
            }

            if (!empty($where)) $where = " where " . $where;
            $order = explode(":", $args['order']);
            $od = " order by ". $order[0] . " " . $order[1];
            $od2 = " order by a.". $order[0] . " " . $order[1];

            $sqlP = "select execute_id from " . $this->tbName . " $where $od limit $start, $limit";
            $sql = "select * from `" . $this->tbName ."` as a inner join ($sqlP) as b on a.case_id = b.case_id $od2 ";
            $st = $this->db->prepare($sql);

            if (is_array($strs)) {
                $i = 0;
                foreach ($strs as $it) {
                    $st->bindValue(':search_'. $i, '%' . $it . '%', PDO::PARAM_STR);
                    $i++;
                }
            }


            $st->execute();

        }
        return $st->fetchAll(PDO::FETCH_ASSOC); 
    }/*}}}*/


    public function insertReportGroup($args) 
    {/*{{{*/
        $createTime = date("Y/m/d H:i:s", time());

        $sql = "insert into %s (`name`, `dirname`, `job_type`, `job_id`, `create_time`, `run_time`, `passed_case_num`, `failed_case_num`) values (:name, :dirname, :job_type, :job_id, :createTime, :run_time, 0, 0)";
        $sql = sprintf($sql, $this->tbName_group);
        $this->db->beginTransaction();
        $st = $this->db->prepare($sql);
        $st->bindValue(':name', $args['name'], PDO::PARAM_STR);
        $st->bindValue(':dirname', $args['dirname'], PDO::PARAM_STR);
        $st->bindValue(':job_type', $args['job_type'], PDO::PARAM_STR);
        $st->bindValue(':job_id', $args['job_id'], PDO::PARAM_INT);

        $st->bindValue(':createTime', $createTime, PDO::PARAM_STR);
        $st->bindValue(':run_time', 0, PDO::PARAM_STR);


        $st->execute();
        $id = $this->db->lastInsertId();
        $this->db->commit();
        return $id;
    }/*}}}*/

    public function insertReport($args) 
    {/*{{{*/
        $createTime = date("Y/m/d H:i:s", time());

        $sql = "insert into %s (`name`, `dirname`, `execute_id`, `case_id`, `status`, `create_time`, `passed_case_num`, `failed_case_num`) values (:name, :dirname, :execute_id, :caseId, :status, :createTime, :passed_case_num, :failed_case_num)";
        $sql = sprintf($sql, $this->tbName);
        $this->db->beginTransaction();

        $st = $this->db->prepare($sql);
        $st->bindValue(':name', $args['name'], PDO::PARAM_STR);
        $st->bindValue(':dirname', $args['dirname'], PDO::PARAM_STR);
        $st->bindValue(':execute_id', $args['executeId'], PDO::PARAM_INT);
        $st->bindValue(':caseId', $args['caseId'], PDO::PARAM_INT);

        $st->bindValue(':passed_case_num', $args['passed'], PDO::PARAM_STR);
        $st->bindValue(':failed_case_num', $args['failed'], PDO::PARAM_STR);

        $st->bindValue(':createTime', $createTime, PDO::PARAM_STR);
        if ($args['failed'] == 0 && $args['passed'] > 0) {
            $st->bindValue(':status', 'passed', PDO::PARAM_STR);
        } else if ($args['passed'] > 0 ) {
            $st->bindValue(':status', 'some_failed', PDO::PARAM_STR);
        } else if ($args['failed'] > 0) {
            $st->bindValue(':status', 'failed', PDO::PARAM_STR);
        } else {
            $st->bindValue(':status', 'none', PDO::PARAM_STR);
        }


        $st->execute();
        $this->db->commit();
    }/*}}}*/

    public function updateReport($args) 
    {
        if (empty($args['reportId'])) {
            return "";
        }

        $sql = "update ". $this->tbName . " set `passed_case_num` = :passed, `failed_case_num`=:failed, `status`=:status where report_id = :reportId";
        $this->db->beginTransaction();

        $st = $this->db->prepare($sql);
        $st->bindValue(':reportId', $args['reportId'], PDO::PARAM_INT);
        if (isset($args['status'])) {
            $st->bindValue(':status', $args['status'], PDO::PARAM_INT);
            $st->bindValue(':passed', 0, PDO::PARAM_INT);
            $st->bindValue(':failed', 0, PDO::PARAM_INT);
        } else {
            $st->bindValue(':passed', $args['passed'], PDO::PARAM_INT);
            $st->bindValue(':failed', $args['failed'], PDO::PARAM_INT);
            if ($args['failed'] == 0 && $args['passed'] > 0) {
                $st->bindValue(':status', 'passed', PDO::PARAM_STR);
            } else if ($args['passed'] > 0 ) {
                $st->bindValue(':status', 'some_failed', PDO::PARAM_STR);
            } else if ($args['failed'] > 0) {
                $st->bindValue(':status', 'failed', PDO::PARAM_STR);
            } else {
                $st->bindValue(':status', 'failed', PDO::PARAM_STR);
            }

        }

        $st->execute();
        $this->db->commit();
    }

    public function updateReportGroup($args) 
    {
        if (empty($args['executeId'])) {
            return "";
        }
        if (isset($args['add_passed'])) {
            $sql = "update ". $this->tbName_group . " set `passed_case_num` = `passed_case_num` + :passed, `failed_case_num` = `failed_case_num` + :failed where execute_id = :executeId";
            $this->db->beginTransaction();

            $st = $this->db->prepare($sql);
            $st->bindValue(':executeId', $args['executeId'], PDO::PARAM_INT);
            $st->bindValue(':passed', $args['add_passed'], PDO::PARAM_INT);
            $st->bindValue(':failed', $args['add_failed'], PDO::PARAM_INT);

        } else {
            $sql = "update ". $this->tbName_group . " set `passed_case_num` = :passed, `failed_case_num`=:failed where execute_id = :executeId";
            $this->db->beginTransaction();

            $st = $this->db->prepare($sql);
            $st->bindValue(':executeId', $args['executeId'], PDO::PARAM_INT);
            $st->bindValue(':passed', $args['passed'], PDO::PARAM_INT);
            $st->bindValue(':failed', $args['failed'], PDO::PARAM_INT);
        }
        $st->execute();
        $this->db->commit();

    }

    public function removeCase($args) 
    {
        $sql = "delete from testCase where case_id = :caseId";
        $st = $this->db->prepare($sql);
        $st->bindValue(':caseId', $args['caseId'], PDO::PARAM_INT);

        return $st->execute();

    }

}
