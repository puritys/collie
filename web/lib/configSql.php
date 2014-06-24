<?php

class configSql 
{
    public $tbName = "config";
    public $db;
    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function queryConfig($args = "")
    {/*{{{*/
        $page = 1;
        $limit = 10;
        $start = ($page - 1) * $limit;
        if (empty($args['order'])) {
            $args['order'] = "create_time:desc";
        }

        if (isset($args['name'])) {
            $sql = "select * from %s where name=:name";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':name', $args['name'], PDO::PARAM_STR);

            $st->execute();

        } else if (isset($args['id'])) {
            $sql = "select * from %s where config_id=:id";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);
            $st->bindValue(':id', $args['id'], PDO::PARAM_INT);

            $st->execute();

        } else {

            $order = explode(":", $args['order']);
            $od = " order by ". $order[0] . " " . $order[1];
            $od2 = " order by a.". $order[0] . " " . $order[1];
            $sqlP = "select config_id from " . $this->tbName . " $od limit $start, $limit";
            $sql = "select * from `%s` as a inner join ($sqlP) as b on a.config_id = b.config_id $od2 ";
            $sql = sprintf($sql, $this->tbName);
            $st = $this->db->prepare($sql);

            $st->execute();

        }
        return $st->fetchAll(PDO::FETCH_ASSOC); 
    }/*}}}*/

    public function insertConfig($args) 
    {
        $createTime = date("Y/m/d H:i:s", time());

        $sql = "insert into config (`name`, `config`, `create_time`) values (:name, :config, :createTime)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':name', $args['name'], PDO::PARAM_STR);
        $st->bindValue(':config', $args['config'], PDO::PARAM_STR);
        $st->bindValue(':createTime', $createTime, PDO::PARAM_STR);

        return $st->execute();

    }

    public function updateConfig($args) 
    {
        $sql = "update config set `name` = :name, `config` = :config where config_id = :configId";
        $st = $this->db->prepare($sql);
        $st->bindValue(':name', $args['name'], PDO::PARAM_STR);
        $st->bindValue(':config', $args['config'], PDO::PARAM_STR);
        $st->bindValue(':configId', $args['configId'], PDO::PARAM_INT);

        return $st->execute();

    }

    public function removeConfig($args) 
    {
        $sql = "delete from config where config_id = :configId";
        $st = $this->db->prepare($sql);
        $st->bindValue(':configId', $args['configId'], PDO::PARAM_INT);

        return $st->execute();

    }

}
