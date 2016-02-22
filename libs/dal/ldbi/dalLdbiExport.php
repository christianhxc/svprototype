<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperUceti.php');
require_once ('libs/caus/clsCaus.php');

class dalLdbiExport {

    public static function getTables(){
        $tables = array();

        $tables[] = array("name" => "vac_registro_diario", "id" => "id_vac_registro_diario", "replacements" => array("vac_registro_diario_dosis" => array("id_vac_registro_diario")));
        $tables[] = array("name" => "vac_registro_diario_dosis", "id" => "id_registro_diario_dosis", "replacements" => array("vac_LDBI_inventario" => array("id_registro_diario_dosis")));

        $tables[] = array("name" => "vac_LDBI_envio", "id" => "id_envio", "replacements" => array("vac_LDBI_inventario" => array("id_envio")));
        $tables[] = array("name" => "vac_LDBI_requesicion", "id" => "id_requesicion", "replacements" => array("vac_LDBI_envio_bodega" => array("id_requesicion"), "vac_LDBI_requesicion_detalle" => array("id_requesicion")));
        $tables[] = array("name" => "vac_LDBI_requesicion_detalle", "id" => "");
        $tables[] = array("name" => "vac_LDBI_envio_bodega", "id" => "id_envio", "replacements" => array("vac_LDBI_inventario" => array("id_envio_bodega"), "vac_LDBI_envio_bodega_detalle" => array("id_envio")));
        $tables[] = array("name" => "vac_LDBI_envio_bodega_detalle", "id" => "");
        $tables[] = array("name" => "vac_LDBI_nota", "id" => "id_nota", "replacements" => array("vac_LDBI_inventario" => array("id_nota"), "vac_LDBI_nota_detalle" => array("id_nota")));
        $tables[] = array("name" => "vac_LDBI_nota_detalle", "id" => "");
        $tables[] = array("name" => "vac_LDBI_inventario", "id" => "id");
        return $tables;
    }

    public static function getTablesCatalogo(){
        $tables = array();



        $tables[] = array("name" => "cat_razon_LDBI", "id" => "id_razon", "replacements" => array("vac_LDBI_nota" => array("id_razon")));
        $tables[] = array("name" => "cat_insumos_LDBI", "id" => "id_insumo", "replacements" => array("vac_LDBI_inventario" => array("id_insumo"), "vac_LDBI_requesicion_detalle" => array("id_insumo"), "vac_LDBI_envio_bodega_detalle" => array("id_insumo")));
        $tables[] = array("name" => "cat_proveedor_LDBI", "id" => "id_proveedor", "replacements" => array("vac_LDBI_envio" => array("id_proveedor")));

        $tables[] = array("name" => "cat_provincia", "id" => "id_provincia", "replacements" => array("cat_region_salud" => array("id_provincia"), "cat_distrito" => array("id_provincia")));
        $tables[] = array("name" => "cat_region_salud", "id" => "id_region", "replacements" => array("cat_distrito" => array("id_region"), "cat_unidad_notificadora" => array("id_region"), "vac_LDBI_envio_bodega" => array("id_region_origen","id_region_destino"), "vac_LDBI_inventario" => array("id_region"), "vac_LDBI_nota" => array("id_region"), "vac_LDBI_requesicion" => array("id_region")));
        $tables[] = array("name" => "cat_distrito", "id" => "id_distrito", "replacements" => array("cat_corregimiento" => array("id_distrito")));
        $tables[] = array("name" => "cat_corregimiento", "id" => "id_corregimiento", "replacements" => array("cat_unidad_notificadora" => array("id_corregimiento")));
        $tables[] = array("name" => "tbl_nivel_instalacion", "id" => "idnivel_instalacion", "replacements" => array("tbl_tipo_instalacion" => array("idnivel_instalacion")));
        $tables[] = array("name" => "tbl_tipo_instalacion", "id" => "idtipo_instalacion", "replacements" => array("cat_unidad_notificadora" => array("idtipo_instalacion")));
        $tables[] = array("name" => "cat_unidad_notificadora", "id" => "id_un", "replacements" => array("vac_LDBI_envio_bodega" => array("id_un_origen","id_un_destino"), "vac_LDBI_inventario" => array("id_un"), "vac_LDBI_nota" => array("id_un"), "vac_LDBI_requesicion" => array("id_un"), "vac_registro_diario_dosis" => array("id_un")));
        return $tables;
    }

    public static function generarCodigosUnicos($fechaFin){
        $tables = array();
        $tables = array_merge($tables, self::getTablesCatalogo());
        $tables = array_merge($tables, self::getTables());
        foreach ($tables as $table){

        }
        // Recorrer tabla por tabla
        // Verificar si tiene campos "fh_modifica" y "codigoglobal"
        // -- Si no los tiene, crearlos
        // -- Para fh_modifica poner por default el rango final de la fecha que se esta solicitado el export
        // Contar todos los registros que no tengan codigo unico generado
        // Recorrer los registros que no tengan codigo unico de 100 en 100
        // Generar codigo unico con PHP registro por registro
        // Actualizar fh_modifica con la fechaFin para los campos que no tienen
    }

    public static function getColumns($table){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SHOW COLUMNS FROM ".$table;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getData($table, $filters){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT * FROM ".$table." WHERE 1 AND ";
        if ($filters != null){
            $sql .= "fh_modifica BETWEEN '".$filters[0]."' AND '".$filters[1]."'";
        }

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function Guardar($conn, $tabla, $objeto, $pk) {
        $ok = true;
        $sql = self::AgregarActualizarQuery($tabla, $objeto, $pk);
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $id = $conn->getID();
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function AgregarActualizarQuery($tabla,$data, $pk){
        $values = array();
        $update = array();
        $fields = array();

        foreach($data as $key=>$value) {
            $fields[] = $key;
            $values[] = "'".$value."'";
            $update[] = " ".$key." = '".$value."' ";
        }

        $fields = implode('`,`', $fields);
        $values = implode(",", $values);
        $update = implode(',', $update);

        $sql = "INSERT INTO `".$tabla."`(`".$fields."`) VALUES(".$values.") ON DUPLICATE KEY UPDATE ";
        if ($pk != ""){
            $sql .= $pk."=LAST_INSERT_ID(".$pk."), ";
        }
        $sql .= $update;
        return $sql;
    }
} 