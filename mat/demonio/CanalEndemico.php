<?php

require_once('Connection.php');

Class CanalEndemico{
    
    public static function iniciar($idEvento, $nivel1, $nivel2, $nivel3, $nivel4, $anio, $semanaEpi, $casos, $cantAnio){
            
        $valorT = ($cantAnio < 5) ? 4.30 : 2.78;
        $filtro = "";
        $datosCasos = array();
        $datosTasas = array();
        $datosLnTasas = array();
        $datosMedias = array();
        $datosTransformados = array();
        $datosDiferencia = array();
        $datos = array();
        $i = 0;
        $precision = 0;
        $poblacion = 11000000;
        $amplificador = 100000;
        $suma = 0;
        $filtro2 = "";
        
        if ($nivel4 != 0){
            $filtro = " and id_nivel_geo4 = ".$nivel4;
            $filtro2 = " and cor.id_corregimiento = ".$nivel4;
        }
        else if ($nivel3 != 0){
            $filtro = " and id_nivel_geo3 = ".$nivel3;
            $filtro2 = " and dis.id_distrito = ".$nivel3;
        }
        else if ($nivel2 != 0){
            $filtro = " and id_nivel_geo2 = ".$nivel2;
            $filtro2 = " and reg.id_region = ".$nivel2;
        }
        else if ($nivel1 != 0){
            $filtro = " and id_nivel_geo1 = ".$nivel1;
            $filtro2 = " and pro.id_provincia = ".$nivel1;
        }

        $anioMenos = ($cantAnio >= 5 ) ? $anio-5 : $anio-3 ;
        $conn = new Connection();
        $conn->initConn();

        $sql = "select  dia.anioToma, sum(dia.numero_casos) as total
                FROM tbl_mat_diagnostico dia
                where id_diagnostico = $idEvento and semana_epi = $semanaEpi and AnioToma between $anioMenos and ($anio-1) $fitro 
                group by AnioToma, semana_epi, id_diagnostico ";

        $conn->prepare($sql);
        $conn->execute();
        $datos = $conn->fetch();
        $conn->closeStmt();
        
        foreach ($datos as $data){
            $datosCasos[$i][0] = $data["anioToma"];
            $datosCasos[$i][1] = $data["total"];
            $i++;
        }
        
        $sql = "select sum(pob.num_poblacion) as total
                from mat_poblacion pob
                inner join cat_corregimiento cor on cor.id_corregimiento = pob.id_corregimiento
                inner join cat_distrito dis on dis.id_distrito = cor.id_distrito
                inner join cat_region_salud reg  on reg.id_region = dis.id_region
                inner join cat_provincia pro on pro.id_provincia = dis.id_provincia
                where 1 $filtro2";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeStmt();
        $conn->closeConn();
        if (isset($data["total"]))
            $poblacion = $data["total"];
//        echo "<br/> --------- CASOS -----------";
//        for ($i=0; $i<$cantAnio; $i++){
//            echo "<br/> - Anio: ".$datosCasos[$i][0]." - Casos: ".$datosCasos[$i][1];
//        }
//        
//        echo "<br/> --------- TASAS -----------";
        for ($i=0; $i<$cantAnio; $i++){
            $precision = $datosCasos[$i][1]/$poblacion;
            $datosTasas[$i][0] = $precision * $amplificador+1;
//            echo "<br/> - Tasas $i: ".$datosTasas[$i][0];
        }
        
//        echo "<br/> --------- CALCULO LN -----------";
        for ($i=0; $i<$cantAnio; $i++){
            $datosLnTasas[$i][0] = log($datosTasas[$i][0]);
//            echo "<br/> - LN Tasas $i: ".$datosLnTasas[$i][0];
        }
        
//        echo "<br/><br/> --------- CALCULO MEDIAS -----------";
        for ($i=0; $i<$cantAnio; $i++){
            $suma = $suma + $datosLnTasas[$i][0];
        }
        $datosMedias[0][0] = $suma/$cantAnio;
//        echo "<br/> - Media: ".$datosMedias[0][0];
        $suma = 0;
        for ($i=0; $i<$cantAnio; $i++){
            $suma = $suma + pow(($datosLnTasas[$i][0]-$datosMedias[0][0]),2);
        }
        $datosMedias[1][0] = sqrt($suma/($cantAnio-1));
//        echo "<br/> - Media DE: ".$datosMedias[1][0];
        
        $suma = 0;
        $datosMedias[2][0] = $datosMedias[0][0]-($valorT*$datosMedias[1][0]/sqrt($cantAnio));
//        echo "<br/> - Media IC Inf: ".$datosMedias[2][0];
        
        $datosMedias[3][0] = $datosMedias[0][0]+($valorT*$datosMedias[1][0]/sqrt($cantAnio));
//        echo "<br/> - Media IC Sup: ".$datosMedias[3][0];
        
//        echo "<br/><br/> --------- TRANSFORMACIONES -----------";
        $datosTransformados[0][0] = exp($datosMedias[2][0])-1;
//        echo "<br/> - IC Inf(tasa): ".$datosTransformados[0][0];
        
        $datosTransformados[1][0] = exp($datosMedias[0][0])-1;
//        echo "<br/> - Media(tasa): ".$datosTransformados[1][0];
        
        $datosTransformados[2][0] = exp($datosMedias[3][0])-1;
//        echo "<br/> - IC Sup(tasa): ".$datosTransformados[2][0];
        
        $datosTransformados[3][0] = ($datosTransformados[0][0]*$poblacion)/$amplificador;
//        echo "<br/> - IC Inf(casos): ".$datosTransformados[3][0];
        
        $datosTransformados[4][0] = ($datosTransformados[1][0]*$poblacion)/$amplificador;
//        echo "<br/> - Media(casos): ".$datosTransformados[4][0];
        
        $datosTransformados[5][0] = ($datosTransformados[2][0]*$poblacion)/$amplificador;
//        echo "<br/> - IC Sup(casos): ".$datosTransformados[5][0];
        
//        echo "<br/><br/> --------- CALCULO DE DIFERENCIAS -----------";
        $datosDiferencia[0][0] = $datosTransformados[3][0];
//        echo "<br/> - IC Inf: ".$datosDiferencia[0][0];
        
        $datosDiferencia[1][0] = $datosTransformados[4][0] - $datosTransformados[3][0];
//        echo "<br/> - Media - IC Inf: ".$datosDiferencia[1][0];
        
        $datosDiferencia[2][0] = $datosTransformados[5][0] - $datosTransformados[4][0];
//        echo "<br/> - IC Sup - Media: ".$datosDiferencia[2][0];
        
//        echo "<br/> ------------ Verificando si se encuentra en zona de Alerta ------------------";
        if ($casos > $datosDiferencia[2][0]){
            return true;
        }
        else {
            return false;
        }        
    }
    
}

?>
