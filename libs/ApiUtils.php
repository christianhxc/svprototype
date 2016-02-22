<?php

class ApiUtils {

    public static function utf8_encode_all($dat) {
        if (is_string($dat)) return utf8_encode($dat);
        if (!is_array($dat)) return $dat;
        $ret = array();
        foreach($dat as $i=>$d) $ret[$i] = self::utf8_encode_all($d);
        return $ret;
    }

    public static function utf8_decode_all($dat) {
        if (is_string($dat)) return utf8_decode($dat);
        if (!is_array($dat)) return $dat;
        $ret = array();
        foreach($dat as $i=>$d) $ret[$i] = self::utf8_decode_all($d);
        return $ret;
    }

} 