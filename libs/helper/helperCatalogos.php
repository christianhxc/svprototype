<?php

require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/helper/helperCommon.php');

class helperCatalogos {

    // ejemplo de como traer los catalogos 
    public static function getTiposVigilancia() {
        $sql = "select * from tipo_vigilancia order by TIP_VIG_NOMBRE asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getServicios() {
        $sql = "select * from cat_servicio order by nombre_servicio asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getTipoIdentificacion() {
        $sql = "select * from cat_tipo_identidad where status = 1 order by nombre_tipo asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getModalidades() {
        $sql = "select * from cat_vac_modalidad where status = 1 order by nombre_modalidad asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getVisitas($id_tb) {
        $sql = "select id_tb_visita, tbv.id_tipo_visita, fecha_visita, id_tb_form, nombre_tipo_visita from tb_visitas tbv 
                LEFT JOIN cat_tipo_visita ctv ON tbv.id_tipo_visita=ctv.id_tipo_visita where id_tb_form =".$id_tb." order by id_tipo_visita";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getZonas() {
        $sql = "select * from cat_vac_zona where status = 1 order by id_zona asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getControles($id_tb) {
        $sql = "select tbv.* from tb_control tbv where id_tb_form =".$id_tb." order by id_tb_control";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getEtnia() {
        $sql = "select * from cat_etnia order by nombre_etnia asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getEtniaVih() {
        $sql = "select * from cat_etnia_vih where status = 1 order by orden asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getGrupoIndigena() {
        $sql = "select * from cat_grupo_indigena where status = 1 order by orden asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
        public static function getEtniatb() {
        $sql = "select * from cat_etnia_tb order by orden asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getProfesion() {
        $sql = "select * from cat_profesion order by nombre_profesion asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getGrupoRiesgoMDR() {
        $sql = "select * from cat_grupo_riesgo_mdr order by orden asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getInmunodepresor() {
        $sql = "select * from cat_inmunodepresor order by orden asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getGenero() {
        $sql = "select * from cat_genero order by genero_nombre asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getIdentidadGenero() {
        $sql = "select id_identidad_genero as id_genero, nombre_identidad_genero as genero_nombre
                from cat_identidad_genero where status = 1 order by nombre_identidad_genero asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene los eventos del área de análisis seleccionada
    public static function getEventos() {
        $sql = "select id_evento, nombre_evento, cie_10_1, cie_10_2, cie_10_3, cie_10_4, cie_10_5, cod_ref_minsa from cat_evento
                where activo = 1 order by nombre_evento asc";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getUnidadesNotificadorasAuto($search) {


        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
//        echo $search["id_un"];
//        print_r($_SESSION["user"]["ubicaciones"]);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= "and id_un in ('" . $temporal . "')";
            }
        }

//$idOrganizacion = $_SESSION["user"]["organizacion"];
//if ($idOrganizacion != ConfigurationCAUS::orgCdc && $idOrganizacion != ConfigurationCAUS::orgMinsa) {
//    $filtro .= " and idtipo_instalacion = " . $idOrganizacion;
//}

        $sql = "SELECT un.nombre_un, un.id_un, re.nombre_region 
                FROM cat_unidad_notificadora un, cat_region_salud re 
                WHERE un.status = 1 " . $filtro . " and un.id_region = re.id_region and nombre_un like '%" . $search . "%' order by nombre_un asc";

        $conn = new Connection();
        $conn->initConn();
        mysql_set_charset('utf8', $conn);
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getUnidadesNotificadoraCorregimiento($search) {
        
        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= "and id_un in ('" . $temporal . "')";
            }
        }

        $sql = "SELECT un.nombre_un, un.id_un, pro.id_provincia, reg.id_region, dis.id_distrito, un.id_corregimiento
                FROM cat_unidad_notificadora un 
                left join cat_corregimiento cor on cor.id_corregimiento = un.id_corregimiento
                left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                left join cat_region_salud reg on reg.id_region = dis.id_region
                left join cat_provincia pro on pro.id_provincia = reg.id_provincia
                WHERE un.status = 1 ".$filtro." and nombre_un like '%" . $search . "%' order by nombre_un asc";

        $conn = new Connection();
        $conn->initConn();
        mysql_set_charset('utf8', $conn);
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getFactorRiesgo($idGrupoFactorRiesgo) {

        $sql = "SELECT id_factor, factor_nombre 
                FROM cat_factor_riesgo
                WHERE status = 1 and id_factor <> 0 and id_grupo_factor = $idGrupoFactorRiesgo order by factor_nombre asc";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getOcupaciones($search) {

        $sql = "SELECT ocu.nombre_ocupacion, ocu.id_ocupacion 
                FROM cat_ocupacion ocu 
                WHERE ocu.status = 1 and nombre_ocupacion like '%" . $search . "%' order by nombre_ocupacion asc";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getCargo() {

        $sql = "select t1.id_cargo, trim(t1.nombre_cargo) as nombre_cargo
                from cat_cargo t1
                where t1.status = 1 order by t1.nombre_cargo asc";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getGrupoFactorRiesgo() {
        $sql = "select id_grupo_factor, grupo_factor_nombre 
                from cat_grupo_factor_riesgo
                where status = 1 order by grupo_factor_nombre asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getGrupoPoblacion() {
        $sql = "select id_grupo_poblacion, nombre_grupo_poblacion 
                from cat_grupo_poblacion
                where status = 1 order by nombre_grupo_poblacion asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getCondiciones() {
        $sql = "select id_condicion, nombre_condicion 
                from cat_vac_condicion
                where status = 1 order by nombre_condicion asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getVacunas() {
        $sql = "select id_vacuna, nombre_vacuna
                from cat_vacuna
                where status = 1 order by nombre_vacuna asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getTipoDenominador() {
        $sql = "SELECT T1.id_grupo_esp as id_denominador, T1.nombre_grupo_esp as nombre_denominador, 1 as tipo
                FROM cat_grupo_esp T1
                WHERE T1.status = 1
                UNION 
                SELECT T2.id_rango as id_denominador, T2.nombre_rango as nombre_denominador, 2 as tipo
                FROM cat_vac_rango T2
                WHERE T2.status = 1";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getGrupoEspPoblacion() {
        $sql = "select id_condicion, nombre_condicion
                from cat_vac_condicion
                where status = 1 order by nombre_condicion asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    // Obtiene los eventos del área de análisis seleccionada
    public static function getEventosAuto($search) {
        $sql = "select id_evento, nombre_evento, cie_10_1, cie_10_2, cie_10_3, cie_10_4, cie_10_5, cod_ref_minsa from cat_evento
                where activo = 1 and (nombre_evento like '%" . $search . "%'
                or cie_10_1 like '%" . $search . "%') order by nombre_evento asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene los eventos del área de análisis seleccionada
    public static function getEventosAutoJ($search) {
        $sql = "select id_evento, nombre_evento, cie_10_1, cie_10_2, cie_10_3, cie_10_4, cie_10_5, cod_ref_minsa from cat_evento
                where activo = 1 and (nombre_evento like '%" . $search . "%'
                or cie_10_1 like '%" . $search . "%') and cie_10_1 like 'j%' order by nombre_evento asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getAntibioticosAuto($search) {
        $sql = "select id_cat_antibiotico, nombre_antibiotico from cat_antibiotico
                where status = 1 and nombre_antibiotico like '%" . $search . "%' order by nombre_antibiotico asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getAntiviralesAuto($search) {
        $sql = "select id_cat_antiviral, nombre_antiviral from cat_antiviral
                where status = 1 and nombre_antiviral like '%" . $search . "%' order by nombre_antiviral asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getEvento($idevento) {
        $sql = "select id_evento, nombre_evento, cie_10_1, cie_10_2, cie_10_3, cie_10_4, cie_10_5, cod_ref_minsa from cat_evento
             where id_evento = ? and activo = 1 order by nombre_evento asc";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->param($idevento);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getDatosPersona($tipoId, $id) {
        $sql = "select per.*, dis.id_distrito, dis.id_region, dis.id_provincia, ide.nombre_tipo, ocu.nombre_ocupacion,
                date(per.fecha_nacimiento) as per_fecha_nacimiento, ocu.id_ocupacion, per.id_estado_civil, per.id_escolaridad,
                dis_diag.id_region as id_region_diagnostico, dis_diag.id_provincia as id_provincia_diagnostico, dis_diag.id_distrito as id_distrito_diagnostico,
                date(per.fecha_parto) as per_fecha_parto
                from tbl_persona per
                inner join cat_corregimiento cor on per.id_corregimiento = cor.id_corregimiento
                inner join cat_distrito dis on cor.id_distrito = dis.id_distrito
                inner join cat_tipo_identidad ide on per.tipo_identificacion = ide.id_tipo_identidad
                left join cat_corregimiento cor_diag on per.id_corregimiento_diagnostico = cor_diag.id_corregimiento
                left join cat_distrito dis_diag on cor_diag.id_distrito = dis_diag.id_distrito
                left join cat_ocupacion ocu on per.id_ocupacion  = ocu.id_ocupacion
                where tipo_identificacion = $tipoId and numero_identificacion = '$id'";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getDatosPersonaUceti($tipoId, $id) {
        $sql = "SELECT per . * , dis.id_distrito, dis.id_region as idregion, res.nombre_region, dis.id_provincia, prov.nombre_provincia, 
                ide.nombre_tipo, ocu.nombre_ocupacion, date( per.fecha_nacimiento ) AS per_fecha_nacimiento
                FROM tbl_persona per
                INNER JOIN cat_corregimiento cor ON per.id_corregimiento = cor.id_corregimiento
                INNER JOIN cat_distrito dis ON cor.id_distrito = dis.id_distrito
                INNER JOIN cat_tipo_identidad ide ON per.tipo_identificacion = ide.id_tipo_identidad
                LEFT JOIN cat_ocupacion ocu ON per.id_ocupacion = ocu.id_ocupacion
                LEFT JOIN cat_provincia prov ON dis.id_provincia = prov.id_provincia
                LEFT JOIN cat_region_salud res ON dis.id_region = res.id_region
                where tipo_identificacion = $tipoId and numero_identificacion = '$id'";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    // Obtiene el listado de personas
    public static function buscarPersonas($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }

        if ($flag == 0)
            $sql = "select * from tbl_persona order by ind_primer_nombre asc";
        else {
            $sql = "select * from tbl_persona WHERE 1 "
                    . ($config["identificador"] != "" ? " AND numero_identificacion LIKE '%" . $config["identificador"] . "%'" : "")
                    . ($config["nombre"] != "" ? " AND CONCAT_WS(' ',primer_nombre, segundo_nombre)  LIKE '%" . $config["nombre"] . "%'" : "")
                    . ($config["apellido"] != "" ? " AND CONCAT_WS(' ',primer_apellido, segundo_apellido)  LIKE '%" . $config["apellido"] . "%'" : "")
                    . ($config["edad_desde"] != "" ? " AND edad >= " . $conn->scapeString($config["edad_desde"]) : "")
                    . ($config["edad_hasta"] != "" ? " AND edad <= " . $conn->scapeString($config["edad_hasta"]) : "")
                    . ($config["tipo_edad"] == '' ? " " : " AND tipo_edad = " . $config["tipo_edad"])
                    . ($config["tipo_id"] == '' ? " " : " AND tipo_identificacion = " . $config["tipo_id"])
                    . ($config["sexo"] != "" ? " AND sexo = '" . $config["sexo"] . "'" : "") . ' order by primer_nombre desc'
                    . " limit " . $config["inicio"] . "," . $config["paginado"];
        }
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function buscarPersonasVacunas($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }

        if ($flag == 0)
            $sql = "select * from tbl_persona order by ind_primer_nombre asc";
        else {
            $sql = "select * from tbl_persona WHERE 1 "
                    . ($config["identificador"] != "" ? " AND numero_identificacion LIKE '%" . $config["identificador"] . "%'" : "")
                    . ($config["nombre"] != "" ? " AND primer_nombre  LIKE '%" . $config["nombre"] . "%'" : "")
                    . ($config["nombre_2"] != "" ? " AND segundo_nombre  LIKE '%" . $config["nombre_2"] . "%'" : "")
                    . ($config["apellido"] != "" ? " AND primer_apellido  LIKE '%" . $config["apellido"] . "%'" : "")
                    . ($config["apellido_2"] != "" ? " AND segundo_apellido  LIKE '%" . $config["apellido_2"] . "%'" : "")
                    . ($config["edad_desde"] != "" ? " AND edad >= " . $conn->scapeString($config["edad_desde"]) : "")
                    . ($config["edad_hasta"] != "" ? " AND edad <= " . $conn->scapeString($config["edad_hasta"]) : "")
                    . ($config["tipo_edad"] == '' ? " " : " AND tipo_edad = " . $config["tipo_edad"])
                    . ($config["tipo_id"] == '' ? " " : " AND tipo_identificacion = " . $config["tipo_id"])
                    . ($config["sexo"] != "" ? " AND sexo = '" . $config["sexo"] . "'" : "") . ' order by primer_nombre desc'
                    . " limit " . $config["inicio"] . "," . $config["paginado"];
        }
        
        //echo "SQL: ".$sql;exit;
        
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function buscarPersonasCantidadVac($config) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "select count(*) as total from tbl_persona WHERE 1 "
                . ($config["identificador"] != "" ? " AND numero_identificacion LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["nombre"] != "" ? " AND primer_nombre  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["nombre_2"] != "" ? " AND segundo_nombre  LIKE '%" . $config["nombre_2"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND primer_apellido  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["apellido_2"] != "" ? " AND segundo_apellido  LIKE '%" . $config["apellido_2"] . "%'" : "")
                . ($config["edad_desde"] != "" ? " AND edad >= " . $conn->scapeString($config["edad_desde"]) : "")
                . ($config["edad_hasta"] != "" ? " AND edad <= " . $conn->scapeString($config["edad_hasta"]) : "")
                . ($config["tipo_edad"] == '' ? " " : " AND tipo_edad = " . $config["tipo_edad"])
                . ($config["tipo_id"] == '' ? " " : " AND tipo_identificacion = " . $config["tipo_id"])
                . ($config["sexo"] != "" ? " AND sexo = '" . $config["sexo"] . "'" : "");

        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function buscarPersonasCantidad($config) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "select count(*) as total from tbl_persona WHERE 1 "
                . ($config["identificador"] != "" ? " AND numero_identificacion LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["nombre"] != "" ? " AND CONCAT_WS(' ',primer_nombre, segundo_nombre)  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND CONCAT_WS(' ',primer_apellido, segundo_apellido)  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["edad_desde"] != "" ? " AND edad >= " . $conn->scapeString($config["edad_desde"]) : "")
                . ($config["edad_hasta"] != "" ? " AND edad <= " . $conn->scapeString($config["edad_hasta"]) : "")
                . ($config["tipo_edad"] == '' ? " " : " AND tipo_edad = " . $config["tipo_edad"])
                . ($config["tipo_id"] == '' ? " " : " AND tipo_identificacion = " . $config["tipo_id"])
                . ($config["sexo"] != "" ? " AND sexo = '" . $config["sexo"] . "'" : "");

        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    // ejemplo de como traer los catalogos 
    public static function getITS() {
        $sql = "select * from cat_ITS where status = 1 order by nombre_ITS asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getDrogas() {
        $sql = "select * from cat_droga where status = 1 order by nombre_droga asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getSintomas() {
        $sql = "select * from cat_sintoma order by nombre_sintoma asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getSignoSintomas() {
        $sql = "select * from cat_signo_sintoma order by nombre_signo_sintoma asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Enfermedades Cronicas
    public static function getEnfermedadesCronicas() {
        $sql = "select * from cat_enfermedad_cronica where status = '1' order by nombre_enfermedad_cronica asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Enfermedades Cronicas
    public static function getEnfermedadesCronicasInfluenza() {
        $sql = "select * from cat_enfermedad_cronica where status = '1' and influenza = '1' order by nombre_enfermedad_cronica asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getEnfermedadesCronicasTotal() {
        $sql = "select count(*) as total from cat_enfermedad_cronica where status = '1'";
        //echo $sql;
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        //print_r($data);
        //echo $data["total"];
        return $data["total"];
    }

    public static function getEnfermedadesCronicasTotalInfluenza() {
        $sql = "select count(*) as total from cat_enfermedad_cronica where status = '1' and influenza = '1'";
        //echo $sql;
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        //print_r($data);
        //echo $data["total"];
        return $data["total"];
    }

    // Antecedentes Vacunales
    public static function getAntecedentesVacunales() {
        $sql = "select * from cat_antecendente_vacunal where status = '1' order by nombre_antecendente_vacunal asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Antecedentes Vacunales
    public static function getAntecedentesVacunalesInfluenza() {
        $sql = "select * from cat_antecendente_vacunal where status = '1' and influenza = '1' order by orden_antecendente_vacunal asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getAntecedentesVacunalesTotal() {
        $sql = "select count(*) as total from cat_antecendente_vacunal where status = '1'";
        //echo $sql;
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        //print_r($data);
        //echo $data["total"];
        return $data["total"];
    }

    public static function getAntecedentesVacunalesTotalInfluenza() {
        $sql = "select count(*) as total from cat_antecendente_vacunal where status = '1' and influenza = '1'";
        //echo $sql;
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        //print_r($data);
        //echo $data["total"];
        return $data["total"];
    }

    // Tipos muestra
    public static function getTiposMuestraInfluenza() {
        $sql = "select * from cat_muestra_laboratorio where status = '1' and influenza = '1' order by nombre_muestra_laboratorio asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getTiposMuestraTotalInfluenza() {
        $sql = "select count(*) as total from cat_antecendente_vacunal where status = '1' and influenza = '1'";
        //echo $sql;
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        //print_r($data);
        //echo $data["total"];
        return $data["total"];
    }

    public static function getSintomasITS($sexo) {
        $filtro = "";
        if ($sexo == "F")
            $filtro = " and sexo != '1'";
        else
            $filtro = " and sexo != '2'";
        $sql = "select *
                    from cat_signo_sintoma
                    where status = '1' " . $filtro . "
                    order by nombre_signo_sintoma";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getDxSindromicoITS($sexo) {
        $filtro = "";
        if ($sexo == "F")
            $filtro = " and sexo != '1'";
        else
            $filtro = " and sexo != '2'";
        $sql = "select *
                    from cat_diag_sindromico
                    where status = '1' " . $filtro . "
                    order by nombre_diag_sindromico";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getDxEtiologicoITS($sexo, $sindromico) {
        $filtro = "";
        if ($sexo == "F")
            $filtro = " and sexo != '1' ";
        else
            $filtro = " and sexo != '2' ";
//        $filtro .= " and id_diag_sindromico = '" . $sindromico . "' ";
        $sql = "select *
                from cat_diag_etiologico
                where status = '1' "
                . $filtro . " order by nombre_diag_etiologico";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getTratamientoITS($sexo, $sindromico) {
        $filtro = "";
        if ($sexo == "F")
            $filtro = " and sexo != '1' ";
        else
            $filtro = " and sexo != '2' ";
//        $filtro .= " and id_diag_sindromico = '" . $sindromico . "' ";
        $sql = "select *
                from cat_tratamiento
                where status = '1' "
                . $filtro . " order by nombre_tratamiento";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getClinicasTarv() {
        $sql = "select *
                from cat_clinica_tarv
                order by nombre_clinica_tarv";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getTipoPoblacion() {
        $sql = "select * from cat_grupo_poblacion where status = 1 order by id_grupo_poblacion asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
        public static function getTipoPoblaciontb() {
        $sql = "select * from cat_gpopoblacional where status = 1 order by id_gpopoblacional asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getTiposMuestras() {
        $sql = "select * from cat_tipos_muestras where status = 1 order by id_tipos_muestras asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getTipoMuestra() {
        $sql = "select * from cat_tipo_muestra where status = 1 order by nombre_tipo_muestra asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    public static function getPrueba() {
        $sql = "select * from cat_prueba where status = 1 order by id_prueba asc";
        $conn = new Connection();

        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
     public static function getDatosPersonatb($tipoId, $id) {
        $sql = "SELECT per . * , dis.id_distrito, dis.id_region as idregion, res.nombre_region, dis.id_provincia, prov.nombre_provincia, 
                date( per.fecha_nacimiento ) AS per_fecha_nacimiento
                FROM tbl_persona per
                INNER JOIN cat_corregimiento cor ON per.id_corregimiento = cor.id_corregimiento
                INNER JOIN cat_distrito dis ON cor.id_distrito = dis.id_distrito
                LEFT JOIN cat_provincia prov ON dis.id_provincia = prov.id_provincia
                LEFT JOIN cat_region_salud res ON dis.id_region = res.id_region
                where tipo_identificacion = $tipoId and numero_identificacion = '$id'";
//                INNER JOIN cat_tipo_identidad ide ON per.tipo_identificacion = ide.id_tipo_identidad
//                LEFT JOIN cat_ocupacion ocu ON per.id_ocupacion = ocu.id_ocupacion";
        
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
        public static function getGrupoEdad(){
            $conn = new Connection();
            $conn->initConn();

            $sql = "select id_grupo_edad as idGrupoEdad,descripcion as grupoEdadDescripcion from cat_grupo_edad order by id_grupo_edad";

            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();

            return $data;        
    }

    public static function getInsumosLDBI($search) {
        $sql = "SELECT cat.id_insumo, cat.nombre_insumo, cat.unidad_presentacion, cat.codigo_insumo, cat.orden
                FROM cat_insumos_LDBI cat
                WHERE cat.status = 1
                and (nombre_insumo like '%" . $search . "%' or codigo_insumo like '%" . $search . "%')
                order by nombre_insumo asc";

        $conn = new Connection();
        $conn->initConn();
        mysql_set_charset('utf8', $conn);
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    
}

