<?php

class cookieHandler {

    static $expiredTime = 864000;

    static function get($key)
    {
        return $_COOKIE[$key];
    }

    static function set($key, $value)
    {
        setcookie($key, $value, time() + self::$expiredTime);
    }

}

