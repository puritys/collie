<?php

class basicUtil {

    static function filterInput($v) {
        $v = strip_tags($v);
        $replaceStr = array(
            '"' => '&quot;',
            "<" => "&#60;", 
            "%" => "&#37;",
            '\\'=>"&#92;",
            "'"=>"&#39;"
        );
        $v = strtr($v,$replaceStr);
        return $v;
    }

    static function filterInputs($key, $type = "GET") {
        $res = array();
        $input = "";
        $type = mb_strtoupper($type, 'UTF-8');
        if ($type == "GET") {
            $input = $_GET;
        } else if ($type == "POST") {
            $input = $_POST;
        } else if ($type == "COOKIE") {
            $input = $_COOKIE;
        }

        if (is_string($key)) {
            $key = explode(',', $key);
        }
        foreach ($key as $k) {
            if (!empty($input[$k])) {
                $res[$k] = self::filterInput($input[$k]);
            } else {
                $res[$k] = "";
            }
        }
        return $res;
    }

}
