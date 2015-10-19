<?php
    require_once("libs/PagineoAjax.php");
    require_once("libs/Configuration.php");
    require_once("libs/DataGrid.php");
    require_once("libs/dal/dalMat.php");

    // PAGINADO
    $config["paginado"]= Configuration::paginado;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
    $config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

    $limite=" limit ".$config["inicio"].",".$config["paginado"];
    
    $data1 = dalMat::traerTablaContactos($limite);
    $config["total"] = dalMat::totalContactos();
    //echo $config["total"];exit;

    //construimos el array que se le va a pasar al datagrid
    if (is_array($data1)){
        foreach ($data1 as $data){
            if (isset ($_POST["idGrupo"])){
                if(dalMat::traerRelaciones($_POST["idGrupo"], $data["id_contacto"] ))
                    $checkbox = '<input type="checkbox" id="data["chexk'.$data["id_contacto"].']" onchange="javascript:checkboxsUpdate('.$data["id_contacto"].')" checked/>';
                else
                    $checkbox = '<input type="checkbox" id="data["chexk'.$data["id_contacto"].']" onchange="javascript:checkboxs('.$data["id_contacto"].')"/>';
            }
            $array[] = array("nombre" => $data["nombres"], "apellido" => $data["apellidos"], "email" => $data["email"], "sel" => $checkbox);
        }

        //instancio el objeto data grid, pasando como par�metro el array creado anteriormente
        Fete_ViewControl_DataGrid::getInstance($array)
        //atributos generales para la tabla
        ->setGridAttributes(array('cellspacing' => '3', 'cellpadding' => '4', 'border' => '0', 'width' => '100%'))
        //permito que haya caracter�sticas de ordenaci�n
        ->enableSorting(true)
        //hago un setup de las columnas del data grid, indicando el valor que se mostrar� en la primera fila
        ->setup(array(
           'nombre' => array('header' => 'Nombre','attributes' => array('width' => '300px')),
           'apellido' => array('header' => 'Apellidos','attributes' => array('width' => '300px')),
           'email' => array('header' => 'Correo', 'attributes' => array('width' => '200px')),
           'sel' => array('header' => 'Seleccione', 'attributes' => array('width' => '70px', 'align'=>'center'))
        ))
        //a�ado una columna en todos los registros del data grid (la primera columna ser� esta)
        //en esa columna muestro un contador para enumerar los registros
        ->addColumnBefore('Contador', '%counter%.', '', array('align' => 'right'))
        //defino a partir de qu� n�mero deseo empezar la cuenta de registros.
        ->setStartingCounter($config["inicio"] + 1)
        //defino el estilo para las filas
//        ->setRowClass('fila')
        //defino el estilo para las filas alternas
//        ->setAlterRowClass('filaalterna')
        //llamo al m�todo para mostrar el datagrid
        ->render();

        // ### PAGINADO ###
        require_once('libs/PagineoAjax.php');
        $pagineo = new PagineoAjax($config["total"],$config['page'],$config["paginado"],'');
        echo $pagineo->renderPagineo();

    }
    else
        echo "no";

?>