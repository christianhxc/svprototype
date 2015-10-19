<?php

    require_once('Connection.php');
    require_once('Configuration.php');
    require_once('sendMails.php');
    require_once('CanalEndemico.php');
    date_default_timezone_set('America/Guatemala');
    
    echo "Empezando el demonio...";
    $conn = new Connection();
    $conn->initConn();
    $sql = "select esc.*, eve.nombre_evento 
            from mat_escenario esc
            inner join cat_evento eve on eve.id_evento = esc.id_n1
            where status = 1";
    $conn->prepare($sql);
    $conn->execute();
    $datosEsc = $conn->fetch();
    $conn->closeStmt();
    $alerta = false;

    $numEsc = 0;
    foreach ($datosEsc as $dataEsc){
        $diaSemana = true;
        $alerta = false;
        if ($dataEsc["tipo_alerta"]==2){
            if ($dataEsc["tipo_alerta"]!= null && $dataEsc["tipo_alerta"]!= 0)
                $diaSemana = ($dataEsc["dia_alerta"] == date("N") ) ? true: false;
        } 
        if ($diaSemana){    
            $semanaEpi = Demonio::calcularSemanaEpi(date("m/d/Y"));
            if ($semanaEpi["semana"] != $dataEsc["semana_lanzada"]){                   

                $idNivelGeo1 = 0;
                $idNivelGeo2 = 0;
                $idNivelGeo3 = 0;
                $idNivelGeo4 = 0;
                $filtro = "";

                if ($dataEsc["nivel_geo"]==2){
                    $idNivelGeo1 = $dataEsc["id_nivel_geo"];
                    $filtro.= " and id_nivel_geo1 = ".$idNivelGeo1;
                }
                else if ($dataEsc["nivel_geo"]==2){
                    $idNivelGeo2 = $dataEsc["id_nivel_geo"];
                    $filtro.= " and id_nivel_geo2 = ".$idNivelGeo2;
                }
                else if ($dataEsc["nivel_geo"]==2){
                    $idNivelGeo3 = $dataEsc["id_nivel_geo"];
                    $filtro.= " and id_nivel_geo3 = ".$idNivelGeo3;
                }
                else if ($dataEsc["nivel_geo"]==2){
                    $idNivelGeo4 = $dataEsc["id_nivel_geo"];
                    $filtro.= " and id_nivel_geo4 = ".$idNivelGeo4;
                }

                $sql = "select sum(dia.numero_casos) as total
                    FROM tbl_mat_diagnostico dia
                    where id_diagnostico = ".$dataEsc["id_n1"]." and semana_epi = ".$semanaEpi["semana"]."-1 and AnioToma = ".$semanaEpi["anio"]." $filtro 
                    group by AnioToma, id_diagnostico ";
                $conn->prepare($sql);
                $conn->execute();
                $dataTabla = $conn->fetchOne();
                $conn->closeStmt();

                if ($dataEsc["tipo_algoritmo"] == 2){
                    $datoHistorico = Demonio::datosSufientesParaCanalEndemico($dataEsc["id_n1"], $semanaEpi["semana"], $filtro);
                    $cantAnio = count($datoHistorico);
                    $alerta = CanalEndemico::iniciar($dataEsc["id_n1"], $idNivelGeo1, $idNivelGeo2, $idNivelGeo3, $idNivelGeo4, $semanaEpi["anio"], $semanaEpi["semana"], $dataTabla["total"], $cantAnio);
                }
                else{
                    $alerta = $dataTabla["total"] > 0 ? true:false;
                }
                if ($alerta == true){
                    Demonio::enviarAlertas($semanaEpi["semana"], $dataEsc["nombre_evento"], $dataTabla["total"], $dataEsc["id_escenario"], $dataEsc["mensaje"]);
                    $alerta = "si se lanza";
                    $sql = "update mat_escenario set semana_lanzada = ".$semanaEpi["semana"]." where id_escenario = ".$dataEsc["id_escenario"];
                    $conn->prepare($sql);
                    $conn->execute();
                    $conn->closeStmt();
                }
                else
                    $alerta = "no se lanza";
            }
        }
        $numEsc++;
        echo "<br/>Para el escenario $numEsc, la alerta: $alerta";
    }
    $conn->closeConn();
    echo "<br/>Finalizando el demonio...";

    Class Demonio{

        public static function datosSufientesParaCanalEndemico($id_n1, $semanaEpi, $filtro) {
            $sql = "select mat.id_diagnostico, mat.nombre_diagnostico, mat.semana_epi, mat.anio
                    from tbl_mat_diagnostico mat
                    where mat.id_diagnostico = ".$id_n1." and mat.semana_epi = $semanaEpi".$filtro."
                    group by mat.anio, mat.semana_epi
                    order by mat.anio desc"; 
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        
        public static function enviarAlertas($semana, $nombreEvento, $casos, $idEscenrio, $menPersonalizado){
            $mensaje = "Para el evento: $nombreEvento \n\n";
            $mensaje.= "En la semana: $semana\n\n";
            $mensaje.= "Se presentaron $casos casos y segun el algoritmo seleccionado se debe intervenir\n\n";
            $mensaje.= $menPersonalizado;
            
            $sql = "select distinct cont.email
                    from mat_escenario_grupo_contacto escgru
                    inner join mat_contacto_grupo_contacto congru on escgru.id_grupo_contacto = congru.id_grupo_contacto
                    inner join mat_contacto cont on cont.id_contacto = congru.id_contacto
                    where escgru.id_escenario = ".$idEscenrio;
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $datos = $conn->fetch();
            $conn->closeConn();
            $envio = sendMails::enviarMail($datos, $mensaje);
            return $envio;
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
