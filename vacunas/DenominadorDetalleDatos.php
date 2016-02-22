<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    
    if (IsSET ($_POST["idForm"])){
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        // Trae todos los datos de los grupos especiales
        $grupos = "";
        $sql = "select T2.nombre_condicion, T1.num_hombre, T1.num_mujer
                from vac_denominador_detalle T1
                inner join cat_vac_condicion T2 on T1.id_grupo_rango = T2.id_condicion
                where T1.tipo_poblacion = 1 and T1.id_vac_denominador = ".$_POST["idForm"];
        $resultados = dalVacunas::selectQuery($conn, $sql);
        foreach ($resultados as $resultado) {
            $grupos .= $resultado["nombre_condicion"]."###".$resultado["num_hombre"]."###".$resultado["num_mujer"]."%%%";
        }
        if ($grupos !== "")
            echo substr($grupos, 0, -3)."***";
        else 
            echo "***";
        
        // Trae todos los datos de los hombres
        $rangoh = 1;
        $numHombre = "";
        $sql = "select T1.id_grupo_rango as rango, T1.num_hombre 
                FROM vac_denominador_detalle T1
                where T1.tipo_poblacion = 2 and T1.num_hombre > 0 and T1.id_vac_denominador = ".$_POST["idForm"]." order by id_grupo_rango";
        $resultadosH = dalVacunas::selectQuery($conn, $sql);
        foreach ($resultadosH as $resultado) {
            while ($rangoh < $resultado["rango"]){
                $numHombre .= "0###";
                $rangoh++;
            }
            $numHombre .= $resultado["num_hombre"]."###";
            $rangoh++;
        }
        for ($i=$rangoh;$i<=17;$i++){
            $numHombre .= "0###";
        }
        if ($numHombre !== "")
            echo substr($numHombre, 0, -3)."***";
        else 
            echo "***";
        
        // Trae todos los datos de las mujeres
        $rango = 1;
        $numMujer = "";
        $sql = "select T1.id_grupo_rango as rango, T1.num_mujer 
                FROM vac_denominador_detalle T1
                where T1.tipo_poblacion = 2 and T1.num_mujer > 0 and T1.id_vac_denominador = ".$_POST["idForm"]." order by id_grupo_rango";
        $resultadosM = dalVacunas::selectQuery($conn, $sql);
        foreach ($resultadosM as $resultado) {
            while ($rango < $resultado["rango"]){
                $numMujer .= "0###";
                $rango++;
            }
            $numMujer .= $resultado["num_mujer"]."###";
            $rango++;
        }
        for ($i=$rango;$i<=17;$i++){
            $numMujer .= "0###";
        }
        if ($numMujer !== "")
            echo substr($numMujer, 0, -3);
        
    }
?>