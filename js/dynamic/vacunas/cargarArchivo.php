<?php
    set_time_limit(0);
    require_once("libs/dal/vacunas/dalvacunas.php");
    require_once('libs/Configuration.php');
    require_once('libs/Connection.php');
    require_once("libs/helper/helperVacunas.php");
    
    //$servicio = "Archivo externo EpiInfo";
    //// TODO: Tomar el servicio al que pertenece el usuario
    //$path = Configuration::bdEpiInfoPath.$servicio."-".date("dmYhis")." ";
  
    if (isset($_REQUEST['anio'])) {
$anio = $_REQUEST['anio'];
} else {
$anio = "";
}

$path .= $_FILES['flArchivo']['name'];
    $partes = explode(".", $path);
    
    $extension = strtolower($partes[count($partes) - 1]);
    if ($extension != "csv"){
        echo "La extension del archivo no es valida, por favor verifique el archivo e intente de nuevo";
        exit;
    }
    if ($extension == "csv"){
        if(move_uploaded_file($_FILES['flArchivo']['tmp_name'], $path)) {
            $handle = @fopen($path, "r");
            $fp = fopen ( $path , "r" );
            $conn = new Connection();
            $conn->initConn();
            $conn->begin();
           
            $count = 0;
            
           while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
             {
    	$count++;
   	 if ($count == 1) { continue; }
	
                try {            	   


   // if(isset($_POST['submit']))
    {
                          
       //Aquí es donde seleccionamos nuestro csv
     //    $fname = $_FILES['sel_file']['name'];
       //  echo 'Cargando archivo: '.$fname.' ';
         //$chk_ext = explode(".",$fname);
         
         //if(strtolower(end($chk_ext)) == "csv")
         {
             //si es correcto, entonces damos permisos de lectura para subir
         //    $filename = $_FILES['sel_file']['tmp_name'];
          //   $handle = fopen($filename, "r");
             
	//$count = 0;
	//while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
             {
    	//$count++;
   	// if ($count == 1) { continue; }
	
               // try {            	       	  
	  //Insertamos los datos 
                                
                  //Insertamos datos en tabla temporal
                    $sql = " INSERT into denominador(id_vac_denominador, nivel, id_provincia, id_region, id_distrito, id_corregimiento, id_un, anio, tipo_poblacion, id_grupo_rango, num_hombre, num_mujer) values('','5',(select id_provincia from cat_region_salud where cod_ref_minsa = '$data[0]'),(select id_region from cat_region_salud where cod_ref_minsa = '$data[0]'),"
                        . "(select id_distrito from cat_distrito where cod_ref_minsa = '$data[2]'), (select id_corregimiento from cat_corregimiento where cod_ref_minsa = '$data[4]' and id_distrito = (select id_distrito from cat_distrito where cod_ref_minsa = '$data[2]')),"
                        . "'0',$anio,'$data[6]','$data[7]','$data[8]','$data[9]')";
                    $result = $conn->prepare($sql);
   		
                    $result->execute();
                    $lastId = $conn->lastInsertId();
               
                            
		 $conn->commit();

 		   echo 'Datos insertados';

		} catch (PDOException $e) {

  	  // si ocurre un error hacemos rollback para anular todos los insert

   	// $conn->rollback();

        echo $e->getMessage();;
}
             }
             //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
            fclose ( $fp );
             
              //Insertamos datos en tabla encabezado
                     $sql = " INSERT INTO `vac_denominador`(`nivel`,`id_region`, `id_distrito`, `id_corregimiento`, `anio`) SELECT distinct nivel, id_region, id_distrito, id_corregimiento, anio from denominador";
                    $result = $conn->prepare($sql);
   		
                    $result->execute();
                 
                   //Actualizamos datos de tabla temporal
                    $sql = " UPDATE `denominador` SET `id_detalle`= (select id_vac_denominador from vac_denominador where denominador.id_corregimiento = vac_denominador.id_corregimiento and denominador.anio = vac_denominador.anio)";
                     $result = $conn->prepare($sql);
   		
                     $result->execute();
                 
                     //Insertamos datos en tabla detalle
                     
                      $sql = "INSERT INTO `vac_denominador_detalle`(`id_vac_denominador`, `tipo_poblacion`, `id_grupo_rango`, `num_hombre`, `num_mujer`) select id_detalle, tipo_poblacion, id_grupo_rango, num_hombre, num_mujer from denominador";
                
                      $result = $conn->prepare($sql);
   		      $result->execute();
             
           echo "Importación exitosa!";
             
         }
         else
         {
            //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para ver si esta separado por " , "
             
             echo"<br/>EL ARCHIVO NO ES VALIDO (debe tener extencion .csv separado por comas)";
         }   
    }