<?php
    require_once ('libs/dal/dalMat.php');
    require_once ('libs/Configuration.php');

    $codigoError = '';
    $tabla = "";

    // Acción por realizar
    $mode=$_REQUEST["mode"];

    // Encabezado de tabla
    echo '<table id="tabla" width="80%" align="center">';
    echo "<tr class=\"dxgvDataRow_PlasticBlue\">
                  <td class=\"dxgv\" colspan = \"5\" align=\"right\">
                  <a href=\"javascript:nuevo()\"><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/crear.png\" title=\"Agregar\"/></a>
                  <a href=\"javascript:refresh()\"><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/actualizar.png\" title=\"Actualizar\"/></a>
                  </td>
              </tr>
              <tr class=\"dxgvDataRow_PlasticBlue\">
                  <th class=\"dxgvHeader_PlasticBlue\">Nombre</th>
                  <th class=\"dxgvHeader_PlasticBlue\">Activo</th>
                  <th class=\"dxgvHeader_PlasticBlue\">&nbsp;</th>
                  <th class=\"dxgvHeader_PlasticBlue\">&nbsp;</th>
              </tr>";

    
    // Para borrar
    if($mode=="delete")
    {
        $var_id=$_GET["id"];
        $error = dalMat::BorrarGrupoContacto($var_id);

        if($error!="1")
        {
            $codigoError = 'B';
            echo '<script type="text/javascript">';
            echo 'alert("Imposible borrar, por favor intente nuevamente, existe informaci\xe3n relacionada");';
            echo '</script>';
        }
    }


    // Nuevo
    if($mode=="new")
    {
       echo"<tr class=\"dxgvDataRow_PlasticBlue\">";
            echo"<td class=\"dxgv\" width=\"70%\"> <input  type=\"text\" maxlength=\"45\" nombre='nombre' size=\"75%\" id='a' /> </td>";
            echo"<td class=\"dxgv\" width=\"20%\"> <input  type=\"checkbox\" id='c'/></td> ";
            
            echo"<td class=\"dxgv\" width=\"5%\"><a href=\"javascript:refresh()\"><img border=0 src=\"".Configuration::getUrlprefix()."img/back.png\" title=\"Cancelar\"/></a></td>";
            echo"<td class=\"dxgv\" width=\"5%\"><a href=\"javascript:save_data();\"><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/save.png\" title=\"Guardar\"/></a></td>";
        echo"</tr>";
    }    


    // Guardar nuevo
    if($mode=="save_new")
    {
        $n = $_REQUEST["n"];
        $s = $_REQUEST["s"];

        $id = dalMat::GuardarGrupoContacto($n,$s);
        if($id=="2")
        {
            echo '<script type="text/javascript">';
            echo 'alert("Imposible agregar este grupo, ya existe uno con el mismo nombre.");';
            echo '</script>';
        }
    }

    // Guardar edición
    if($mode=="update_data")
    {
        $n = $_REQUEST["n"];
        $s = $_REQUEST["s"];
        $id = $_REQUEST["i"];

        $error = dalMat::ActualizarGrupoContacto($id, $n, $s);
        
        if($error=="2")
        {
            //$tabla.='</td></tr></table>';
            echo '<script type="text/javascript">';
            echo 'alert("Imposible editar, por favor intente nuevamente.");';
            echo '</script>';
        }
    }
    // End of Update

    // Parametros para limitar los resultados
    $config["paginado"] = Configuration::paginado; // Cuantos resultados por pagina se desean ver
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
    $config["inicio"] = ($config['page'] - 1) * $config["paginado"]; // Inicio del set de datos

    // Display all the data from the table
    $lista = dalMat::traerTablaGrupoContacto();

    if(isset ($lista))
    {
        foreach($lista as $lista)
        {
            $id = $lista['id_grupo_contacto'];
            $nombre=$lista['nombre_grupo_contacto'];
            $status=$lista['status'];

            $status = ($status == '1'?'S&iacute;':'No');
            $id_="";
            //// if Mode is Update then get the ID and display the text field with value Other wise print the data into the table
            if($mode=="update")
            $id_=$_GET["id"];

            if($id_==$id)
            {
                echo "<tr class=\"dxgvDataRow_PlasticBlue\">";
                    echo "<td class=\"dxgv\" width=\"70%\">
                                <input type='hidden' value='".$id_."' name='prev_id' id='i".$id_."'/>
                                <input type='text' value='".htmlentities($nombre)."' id='n".$id_."' size=\"75%\" />
                         </td>";
                    echo '<td class=\"dxgv\" width = \"20%\">
                                <input type="checkbox" value= "'.$status.'" name="estado" id="st'.$id_.'" '.($status!='No'?" checked ":" ").' />
                         </td> ';

                    echo "<td class=\"dxgv\" width = \"5%\"> <a href=\"javascript:refresh()\"><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/cancelar.png\" title=\"Cancelar\"/></a> </td> ";
                    echo "<td class=\"dxgv\" width = \"5%\"><a href=\"javascript:update_data('".$id_."');\"><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/save.png\" title=\"Guardar\"/></a></td>";
            }
            else
            {
                echo "<tr class=\"dxgvDataRow_PlasticBlue\">";
                    echo "<td class=\"dxgv\" width= \"70%\">".htmlentities($nombre)."</td>";
                    echo "<td class=\"dxgv\" width= \"20%\">".$status."</td>";
                    echo "<td class=\"dxgv\" width= \"5%\"><a href=\"javascript:editar('".$id."')\"><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/edit copy.png\" title=\"Editar\"/></a></td> ";
                    echo "<td class=\"dxgv\" width= \"5%\"><a href=\"javascript:requestInfo('js/dynamic/mat/tablaGrupoContacto.php?mode=delete&id=$id','resListGrupo','')\" onclick='return confirmLink(this);'><img border=0 src=\"".Configuration::getUrlprefix()."img/iconos/delete.png\" title=\"Eliminar\"/></a></td>";
            }
            echo "</tr>";
        }
    }
    echo "</table>";
    
    // ### PAGINADO ###
    // Almacenar cuantos registros hay en total

    $total = dalMat::totalGrupoContacto();

    // Mostrar HTML con numeros de paginas
   // ### PAGINADO ###
   require_once('libs/PagineoAjax.php');
   $pagineo = new PagineoAjax($total,$config['page'],$config["paginado"],'');
   $pagineo->renderPagineo();
   ### PAGINADO ###
?>