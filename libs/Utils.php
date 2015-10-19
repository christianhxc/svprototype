<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author cmelendez
 */
class Utils {
    //put your code here
    
    function __construct()
    {
    }

    /**
     * Sumar dias a una fecha dada
     * @param <date> $fecha, fecha a la que hay que sumarle dias en el formato "Y-m-d"
     * @param <date> $ndias, cantidad de dias que se van a sumar a la fecha
     * @return <date> nueva fecha en el formato "Y-m-d"
     */
    function sumarDias($fecha,$ndias){
        $fecha = str_replace("/", "-", $fecha);
        
        if (preg_match("/([0-9][0-9]){1,2}-[0-9]{1,2}-[0-9]{1,2}/",$fecha))
            list($anio, $mes, $dia)=split("-",$fecha);

        $nueva = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("Y-m-d",$nueva);

        return ($nuevafecha);
    }

    /**
     * Comparar dos fechas dadas y saber si la segunda fecha es igual, mayor o menor
     * Fechas iguales --> == 0
     * Fecha 1 es menor que fecha 2 --> < 0
     * Fecha 1 es mayor que fecha 2 --> > 0
     * @param <date> $fecha, fecha 1 en el formato "Y-m-d"
     * @param <date> $fecha2, fecha 2 en el formato "Y-m-d"
     * @return <int> Diferencia entre las fechas
     */
    function compararFechas($fecha,$fecha2){
        $fecha = str_replace("/", "-", $fecha);
        $fecha2 = str_replace("/", "-", $fecha2);

        if (preg_match("/([0-9][0-9]){1,2}-[0-9]{1,2}-[0-9]{1,2}/",$fecha))
            list($anio, $mes, $dia)=split("-",$fecha);

        if (preg_match("/([0-9][0-9]){1,2}-[0-9]{1,2}-[0-9]{1,2}/",$fecha2))
            list($anio2, $mes2, $dia2)=split("-",$fecha2);

        $dif = mktime(0,0,0,$mes,$dia,$anio) - mktime(0,0,0, $mes2,$dia2,$anio2);
        
        return ($dif);
    }

    /**
     * Genera una clave random
     * @return string
     */
    function crearClaveRandom() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }
    
     public static function calcularSemanaEpi($time){
        $data = array();
        $unDia = 86400;
        $varlist = strtotime($time);
        $anioActual = strftime("%Y", $varlist);

        $first_day = strtotime("01/01/".$anioActual);
        $wkday = strftime("%w", $first_day);
        $fwb = ($wkday<=3) ? ($first_day-($wkday*$unDia)) : ($first_day+(7*$unDia)-($wkday*$unDia));
        if ($varlist < $fwb){
            $first_day = strtotime("01/01/".($anioActual-1));
            $wkday = strftime("%w", $first_day);
            $fwb = ($wkday<=3) ? $first_day-($wkday*$unDia) : $first_day+(7*$unDia)-($wkday*$unDia);
        }

        $last_day = strtotime("12/31/".$anioActual);
        $wkday = strftime("%w", $last_day);
        $ewb = ($wkday<3) ? ($last_day-($wkday*$unDia))-(1*$unDia) : $last_day+(6*$unDia)-($wkday*$unDia);
        if ($varlist > $ewb)
            $fwb = $ewb+(1*$unDia);

        $semanaEpi = floor((($varlist-$fwb)/(7*$unDia))+1);
        $anioEpi = strftime("%Y", $fwb+(180*$unDia));
        
        $data["semana"] = $semanaEpi;
        $data["anio"] = $anioEpi;
        
        return $data;
    }
    
}
?>
