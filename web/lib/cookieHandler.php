<?php

class cookieHandler {

    static $expiredTime = 864000;

    static function get($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return "";
    }

    static function set($key, $value)
    {
        setcookie($key, $value, time() + self::$expiredTime);
    }

}

