<?php

class caseSql 
{
    public $tbName = "testCase";
    public $db;
    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function queryCase($args = "")
    {/*{{{*/
        $page = 1;
        $limit = 10;
        if (!empty($args['page'])) $page = $args['page'];
        if (!empty($args['pageSize'])) $limit = $args['pageSize'];
        $start = ($page - 1) * $limit;
        if (empty($args['order'])) {
            $args['order'] = "create_time:desc";
        }

        if (isset($args['id'])) {
            $sql = "select * from %s where case_id=:id";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['id'], PDO::PARAM_INT);

            $st->execute();

        } else {
            $where = "";
            if (!empty($args['searchText'])) {
                $strs = preg_split('/[\s]+/', $args['searchText']);
                $i = 0;
                foreach ($strs as $it) {
                    if ($where) $where .= ' and ';
                    $where .= ' `title` like :search_'. $i .'';
                    $i++;
                }
            }

            if (!empty($where)) $where = " where " . $where;
            $order = explode(":", $args['order']);
            $od = " order by ". $order[0] . " " . $order[1];
            $od2 = " order by a.". $order[0] . " " . $order[1];

            if (!empty($args['isCount']) && $args['isCount'] == true) {
                $sql = "select count(*) as quantity from " . $this->tbName . " $where ";
            } else {
                $sqlP = "select case_id from " . $this->tbName . " $where $od limit $start, $limit";
                $sql = "select * from `" . $this->tbName ."` as a inner join ($sqlP) as b on a.case_id = b.case_id $od2 ";
            }
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
        $ret = $st->fetchAll(PDO::FETCH_ASSOC); 
        if (!empty($args['isCount']) && $args['isCount'] == true) {
            return intval($ret[0]['quantity']);
        }
        return $ret;
    }/*}}}*/

    public function insertCase($args) 
    {
        $createTime = date("Y/m/d H:i:s", time());

        $sql = "insert into testCase (`title`, `content`, `descriptor`, `create_time`) values (:title, :content, :descriptor, :createTime)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':title', $args['title'], PDO::PARAM_STR);
        $st->bindValue(':content', $args['content'], PDO::PARAM_STR);
        $st->bindValue(':descriptor', $args['descriptor'], PDO::PARAM_STR);
        $st->bindValue(':createTime', $createTime, PDO::PARAM_STR);

        $this->db->beginTransaction();
        $st->execute();
        $id = $this->db->lastInsertId();
        $this->db->commit();
        return $id;
    }

    public function updateCase($args) 
    {
        $sql = "update testCase set `title` = :title, `content` = :content, `descriptor` = :descriptor where case_id = :caseId";
        $st = $this->db->prepare($sql);
        $st->bindValue(':title', $args['title'], PDO::PARAM_STR);
        $st->bindValue(':content', $args['content'], PDO::PARAM_STR);
        $st->bindValue(':descriptor', $args['descriptor'], PDO::PARAM_STR);
        $st->bindValue(':caseId', $args['caseId'], PDO::PARAM_INT);

        return $st->execute();

    }

    public function removeCase($args) 
    {
        $sql = "delete from testCase where case_id = :caseId";
        $st = $this->db->prepare($sql);
        $st->bindValue(':caseId', $args['caseId'], PDO::PARAM_INT);

        return $st->execute();

    }

}
