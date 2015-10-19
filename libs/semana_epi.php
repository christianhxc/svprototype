<?php

    if (isset ($_POST["time"])){
        $time = $_POST["time"];
        $data = array();
        $unDia = 86400;
        $varlist = strtotime($time);
        $anioActual = strftime("%Y", $varlist);

        $first_day = strtotime("01/01/".$anioActual);
        $wkday = strftime("%w", $first_day);
        $wkcalendar = date("W", strtotime($time));
        
        if ($wkday > 3) {
            $semanaEpi = $wkcalendar;
            $anioEpi = $anioActual;
        }else{
            $semanaEpi = $wkcalendar + 1;
            $anioEpi = $anioActual; 
        }
            
        $data["semana"] = $semanaEpi;
        $data["anio"] = $anioEpi;
        
        echo $semanaEpi."###".$anioEpi;
//        echo "### día de la semana de la fecha".$wkday. " - fecha del requerimiento ". $first_day . " - semana de la fecha seleccionada ". date("W", strtotime($time)) ." , ".date("W", $first_day);
        //return $data;
        
//        $fwb = ($wkday<3) ? ($first_day-($wkday*$unDia)) : ($first_day+(7*$unDia)-($wkday*$unDia));
//        if ($varlist < $fwb){
//            $first_day = strtotime("01/01/".($anioActual-1));
//            $wkday = strftime("%w", $first_day);
//            $fwb = ($wkday<=3) ? $first_day-($wkday*$unDia) : $first_day+(7*$unDia)-($wkday*$unDia);
//        }
//
//        $last_day = strtotime("12/31/".$anioActual);
//        $wkday = strftime("%w", $last_day);
//        $ewb = ($wkday<3) ? ($last_day-($wkday*$unDia))-(1*$unDia) : $last_day+(6*$unDia)-($wkday*$unDia);
//        if ($varlist > $ewb)
//            $fwb = $ewb+(1*$unDia);

//        $semanaEpi = floor((($varlist-$fwb)/(7*$unDia))+1);
//        $anioEpi = strftime("%Y", $fwb+(180*$unDia));
    }
    
?>
