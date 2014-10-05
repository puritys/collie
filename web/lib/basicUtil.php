<?php

class basicUtil {

    static function filterInput($v, $rule = "") {
        $v = strip_tags($v);
        $replaceStr = array(
            '"' => '&quot;',
            "<" => "&#60;", 
            "%" => "&#37;",
            '\\'=>"&#92;",
            "'"=>"&#39;"
        );
        $v = strtr($v,$replaceStr);
        switch ($rule) {
            case 'a2z':
                $v = preg_replace('/[^a-z]+/i', '', $v);
                break;
            case 'word':
                $v = preg_replace('/[^a-z0-9_]+/i', '', $v);
                break;
            case 'variable':
                $v = preg_replace('/[^a-z0-9_\-]+/i', '', $v);
                break;

        }
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
