<?php

class caseCategorySql 
{
    public $tbName = "caseCategory";
    public $db;
    public function __construct($db) 
    {
        $this->db = $db;
    }


    public function query($args = "")
    {/*{{{*/
        $page = 1;
        $limit = 10;
        if (!empty($args['pageSize'])) $limit = $args['pageSize'];
        $start = ($page - 1) * $limit;
        if (empty($args['order'])) {
            $args['order'] = "create_time:desc";
        }

        if (isset($args['categoryId'])) {
            return $this->queryByCategory($args);
        }

        if (isset($args['caseId'])) {
            if (is_array($args['caseId'])) {
                $id = "";
                foreach ($args['id'] as $it) { 
                    if ($id) { $id .= ',';}
                    $id .= $it;
                }
                $sql = "select * from %s where case_id in(". $id. ") order by create_time desc";
                $sql = sprintf($sql, $this->tbName);
                $st = $this->db->prepare($sql);
            } else {
                if (!empty($args['isCount'])) {
                    $sql = "select count(*) as quantity from %s where case_id =:id";
                    $sql = sprintf($sql, $this->tbName);
                    $st = $this->db->prepare($sql);
                } else {
                    $sql = "select * from %s where case_id =:id order by create_time desc";
                    $sql = sprintf($sql, $this->tbName);
                    $st = $this->db->prepare($sql);
                    $st->bindValue(':id', $args['caseId'], PDO::PARAM_INT);
                }
            }
            $st->execute();

        } 
        $res = $st->fetchAll(PDO::FETCH_ASSOC); 
        if (!empty($args['isCount'])) {
            return $res[0]['quantity'];
        } else {
            return $res;
        }

    }/*}}}*/


    public function update($args) 
    {/*{{{*/

error_log("cate = " . print_r($args,1));
        $category = $args['category'];
        if (!is_array($category)) $category = array($category);
        $caseId = $args['caseId'];
        $categoryInCase = $this->query(array(
            "caseId" => $caseId,
            "pageSize" => 99999999,
        ));

        //remove the category, if user unselect it.
        foreach ($categoryInCase as $it) {
            $isRemove = true;
            foreach ($category as $cat) {
                if ($it['category_id'] == $cat) {
                    $isRemove = false;
                    break;
                }
            }
            if ($isRemove == true) {
                $this->remove(array(
                    "caseId" => $caseId,
                    "id" => $it['id'],
                ));
            }
        }

        //add new category
        foreach ($category as $cat) {
            $isAdd = true;
            foreach ($categoryInCase as $it) {
                if ($it['category_id'] == $cat) {
                    $isAdd = false;
                    break;
                }
            }
            if ($isAdd == true) {
                $this->insert(array(
                   "caseId" => $caseId,
                   "categoryId" => $cat,             
                ));
            }
        }

     }/*}}}*/

    public function insert($args) 
    {/*{{{*/
        if (empty($args['caseId'])) return ;
        $createTime = date("Y/m/d H:i:s", time());
        $sql = "insert into %s (`case_id`, `category_id`, `create_time`) values (:caseId, :categoryId, :createTime)";
        $sql = sprintf($sql, $this->tbName);
        $this->db->beginTransaction();
        $st = $this->db->prepare($sql);

        $st->bindValue(':caseId', $args['caseId'], PDO::PARAM_INT);
        $st->bindValue(':categoryId', $args['categoryId'], PDO::PARAM_INT);
        $st->bindValue(':createTime', $createTime, PDO::PARAM_STR);

        $st->execute();
        $this->db->commit();


    }/*}}}*/

    public function remove($args) 
    {/*{{{*/

        $createTime = date("Y/m/d H:i:s", time());
        $sql = "delete from %s where id=:id and case_id=:caseId";
        $sql = sprintf($sql, $this->tbName);
        $this->db->beginTransaction();
        $st = $this->db->prepare($sql);
  
        $st->bindValue(':caseId', $args['caseId'], PDO::PARAM_INT);
        $st->bindValue(':id', $args['id'], PDO::PARAM_INT);

        $st->execute();
        $this->db->commit();


    }/*}}}*/

    public function queryByCategory($args) 
    {/*{{{*/

       if (!empty($args['isCount'])) {
            $sql = "select count(*) as quantity from %s where category_id =:id";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['categoryId'], PDO::PARAM_INT);
        } else {
            $sql = "select * from %s where category_id =:id";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['categoryId'], PDO::PARAM_INT);

        }

        $st->execute();

        $res = $st->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($args['isCount'])) {
            return intval($res[0]['quantity']);
        } else {
            return $res; 
        }

    }/*}}}*/

}
