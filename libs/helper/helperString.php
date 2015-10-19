<?php

class helperString {

    public static function cleanText($text) {
        $text = str_replace('&', 'i', $text);

        $text = preg_replace('/([\xc0-\xdf].)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 192) * 64 + (ord(substr('$1', 1, 1)) - 128)) . ';'", $text);
        $text = preg_replace('/([\xe0-\xef]..)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 224) * 4096 + (ord(substr('$1', 1, 1)) - 128) * 64 + (ord(substr('$1', 2, 1)) - 128)) . ';'", $text);
        $text = preg_replace("/&#225;/", "a", $text);
        $text = preg_replace("/&#233;/", "e", $text);
        $text = preg_replace("/&#237;/", "i", $text);
        $text = preg_replace("/&#243;/", "o", $text);
        $text = preg_replace("/&#250;/", "u", $text);
        $text = preg_replace("/&#241;/", "ni", $text);
        $text = preg_replace("/&#193;/", "a", $text);
        $text = preg_replace("/&#201;/", "e", $text);
        $text = preg_replace("/&#205;/", "i", $text);
        $text = preg_replace("/&#211;/", "o", $text);
        $text = preg_replace("/&#218;/", "u", $text);
        $text = preg_replace("/&#209;/", "ni", $text);
        $text = preg_replace("/([^a-zA-Z0-9\-])+/", "-", $text);
        $text = preg_replace("/-(-)+/", "-", $text);
        $text = preg_replace("/^(-)+/", "", $text);
        $text = preg_replace("/(-)+$/", "", $text);
        return strtolower($text);
    }

    public static function toDateOther($date) {
        if ($date == "")
            return null;
        list($day, $month, $year) = explode('/', $date);
        return $year . "-" . $month . "-" . $day;
    }

    public static function toDate($date) {
        if ($date == "" || $date == "dd/mm/aaaa")
            return null;
        list($day, $month, $year) = explode('/', $date);
        return $year . "-" . $month . "-" . $day;
    }

    public static function toDateIngles($date) {
        if ($date == "" || $date == "dd/mm/aaaa")
            return null;
        list($month, $day, $year) = explode('/', $date);
        return $year . "-" . $month . "-" . $day;
    }

    public static function toDateTrim($date) {
        if ($date == "" || $date == "dd/mm/aaaa")
            return null;
        list($day, $month, $year) = explode('/', $date);
        return (int) trim($year) . "-" . (int) trim($month) . "-" . (int) trim($day);
    }

    public static function toDateView($date) {
        if ($date == "" || $date == "0000-00-00")
            return null;
        list($year, $month, $day) = explode('-', $date);
        return $day . "/" . $month . "/" . $year;
    }

    public static function toDateViewOther($date) {
        if ($date == "")
            return null;
        list($year, $month, $day) = explode('-', $date);
        return $day . "/" . $month . "/" . $year;
    }
    
    public static function toDateGuion($date) {
        if ($date == "")
            return null;
        list($day, $month, $year) = explode('-', $date);
        return $year . "-" . $month . "-" . $day;
    }
    public static function completeZeros($string) {
        $prev = '';
        $string = (string) $string;
        for ($i = 0; $i < (7 - strlen($string)); $i++)
            $prev.='0';
        return $prev . $string;
    }

    public static function calcularSemanaEpiOtroFormato($date) {
        if ($date == "" || $date == "dd/mm/aaaa")
            return null;
        list($day, $month, $year) = explode('/', $date);
        $time = (int) trim($month) . "/" . (int) trim($day) . "/" . (int) trim($year);
        return helperString::calcularSemanaEpi($time);
    }

    public static function calcularSemanaEpi($time) {
        $data = array();
        $unDia = 86400;
        $varlist = strtotime($time);
        $anioActual = strftime("%Y", $varlist);

        $first_day = strtotime("01/01/" . $anioActual);
        $wkday = strftime("%w", $first_day);
        $fwb = ($wkday <= 3) ? ($first_day - ($wkday * $unDia)) : ($first_day + (7 * $unDia) - ($wkday * $unDia));
        if ($varlist < $fwb) {
            $first_day = strtotime("01/01/" . ($anioActual - 1));
            $wkday = strftime("%w", $first_day);
            $fwb = ($wkday <= 3) ? $first_day - ($wkday * $unDia) : $first_day + (7 * $unDia) - ($wkday * $unDia);
        }

        $last_day = strtotime("12/31/" . $anioActual);
        $wkday = strftime("%w", $last_day);
        $ewb = ($wkday < 3) ? ($last_day - ($wkday * $unDia)) - (1 * $unDia) : $last_day + (6 * $unDia) - ($wkday * $unDia);
        if ($varlist > $ewb)
            $fwb = $ewb + (1 * $unDia);

        $semanaEpi = floor((($varlist - $fwb) / (7 * $unDia)) + 1);
        $anioEpi = strftime("%Y", $fwb + (180 * $unDia));

        $data["semana"] = $semanaEpi;
        $data["anio"] = $anioEpi;

        return $data;
    }

}