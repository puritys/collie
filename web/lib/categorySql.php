<?php

class categorySql 
{
    public $db;
    public $tbName = "category";
    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function queryCategory($args = "")
    {/*{{{*/
        $page = 1;
        $limit = 10;
        if (!empty($args['page'])) $page = $args['page'];
        if (!empty($args['pageSize'])) $limit = $args['pageSize'];

        $start = ($page - 1) * $limit;
        $where = "";
        if (isset($args['id'])) {
            if (is_array($args['id'])) {
                $ids = "";
                foreach ($args['id'] as $id) {
                    if (!empty($ids)) {$ids .= ',';}
                    $ids .= $id;
                }

                $ids = "category_id in (" . $ids . ")";
                $sql = "select * from `%s` where " . $ids;
                $sql = sprintf($sql, $this->tbName);
                $st = $this->db->prepare($sql);

            } else {
                $sql = "select * from `%s` where category_id=:id";
                $sql = sprintf($sql, $this->tbName);
                $st = $this->db->prepare($sql);
                $st->bindParam(':id', $args['id'], PDO::PARAM_INT);


            }
            $st->execute();

        } else {
            if (!empty($args['excludeId'])) {
                if (!is_array($args['excludeId'])) {
                    $args['excludeId'] = array($args['excludeId']);
                }
                $where = ' category_id not in(';
                $ids = "";
                foreach ($args['excludeId'] as $it) {
                    if (!empty($ids)) $ids .= ',';
                    $ids .= $it;
                }

                $where .= $ids .  ")";
            }

            if ($where) $where = "where " . $where;
            if (!empty($args['isCount']) && $args['isCount'] == true) {
                $sql = "select count(*) as quantity from " . $this->tbName . " $where ";
                $st = $this->db->prepare($sql);

            } else {

                $sql = <<<SQL
                    select * from `%s` as t inner join (
                        select category_id from `%s` $where  order by create_time DESC limit :start, :limit
                    ) as b on t.category_id = b.category_id
SQL;
                $sql = sprintf($sql, $this->tbName, $this->tbName);
                $st = $this->db->prepare($sql);
                $st->bindParam(':start', $start, PDO::PARAM_INT);
                $st->bindParam(':limit', $limit, PDO::PARAM_INT);
            }

            $st->execute();

        }
        $ret = $st->fetchAll(PDO::FETCH_ASSOC); 
        if (!empty($args['isCount']) && $args['isCount'] == true) {
            return $ret[0]['quantity'];
        }
        return $ret;

    }/*}}}*/

    public function insertCategory($args)
    {/*{{{*/
        $createTime = date("Y/m/d H:i:s", time());
        if (!isset($args['parentId'])) {
            $parentId = 0;
        } else {
            $parentId = $args['parentId'];
        }
        $sql = <<<SQL
            insert into `%s`(`name`, `parent_id`, `create_time`) values(:name, :parentId, :createTime)
SQL;
        $sql = sprintf($sql, $this->tbName);
        $st = $this->db->prepare($sql);
        $st->bindValue(':parentId', $parentId, PDO::PARAM_INT);
        $st->bindValue(':name', $args['name'], PDO::PARAM_INT);
        $st->bindValue(':createTime', $createTime, PDO::PARAM_STR);

        return $st->execute();



    }/*}}}*/

    public function updateCategory($args)
    {/*{{{*/
        if (!isset($args['parentId'])) {
            $parentId = 0;
        } else {
            $parentId = $args['parentId'];
        }
        $sql = <<<SQL
            update `%s` set `name` = :name, `parent_id` = :parentId where category_id=:id
SQL;
        $sql = sprintf($sql, $this->tbName);
        $st = $this->db->prepare($sql);
        $st->bindValue(':parentId', $parentId, PDO::PARAM_INT);
        $st->bindValue(':name', $args['name'], PDO::PARAM_INT);
        $st->bindValue(':id', $args['id'], PDO::PARAM_STR);

        return $st->execute();



    }/*}}}*/

    public function removeCategory($args)
    {/*{{{*/
        $sql = <<<SQL
            delete from `%s` where category_id=:id
SQL;
        $sql = sprintf($sql, $this->tbName);
        $st = $this->db->prepare($sql);
        $st->bindValue(':id', $args['id'], PDO::PARAM_STR);

        return $st->execute();



    }/*}}}*/

}
