<?php
    require_once('libs/Connection.php');
    require_once('libs/Configuration.php');
    require_once ('libs/caus/Utils.php');

    class dalCatalogos
    {
        // Conteos de paginado
        public static function conteoUbicaciones()
        {
            $sql = "select count(*) as total from tbl_ubicacion_tipo";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoEnfermedadesCronicas()
        {
            $sql = "select count(*) as total from cat_enfermedad_cronica";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoAntecedentesVacunales()
        {
            $sql = "select count(*) as total from cat_antecendente_vacunal";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoAntibioticos()
        {
            $sql = "select count(*) as total from cat_antibiotico";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoVacunas()
        {
            $sql = "select count(*) as total from cat_vacuna";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoCondciones()
        {
            $sql = "select count(*) as total from cat_vac_condicion";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoAntivirales()
        {
            $sql = "select count(*) as total from cat_antiviral";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }
        
        public static function conteoTipoMuestras()
        {
            $sql = "select count(*) as total from cat_muestra_laboratorio";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }

        public static function conteoSecciones()
        {
            $sql = "select count(*) as total from tbl_seccion_tipo";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }

        public static function conteoAcciones()
        {
            $sql = "select count(*) as total from tbl_accion";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';

        }
        
        public static function conteoModalidad()
        {
            $sql = "select count(*) as total from cat_vac_modalidad";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetchOne();
            $conn->closeConn();

            if ($error==""){
                return $data["total"];
            }
            else
                return '-1';
        }

        public static function obtenerTiposUbicacion($config)
        {
            $sql="";
            $sql = "select tbl_ubicacion_tipo.idubicacion_tipo as id,
                    tbl_ubicacion_tipo.`nombre` as nom,
                    tbl_ubicacion_tipo.`orden` as ord,
                    tbl_ubicacion_tipo.`status` as stat
                    from `tbl_ubicacion_tipo`
                    order by tbl_ubicacion_tipo.`orden` ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerEnfermedadesCronicas($config)
        {
            $sql="";
            $sql = "select id_cat_enfermedad_cronica as id,
                    nombre_enfermedad_cronica as nom,
                    influenza as flu,
                    status as stat
                    from cat_enfermedad_cronica
                    order by nombre_enfermedad_cronica ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerAntecedentesVacunales($config)
        {
            $sql="";
            $sql = "select id_cat_antecendente_vacunal as id,
                    nombre_antecendente_vacunal as nom,
                    influenza as flu,
                    status as stat
                    from cat_antecendente_vacunal
                    order by nombre_antecendente_vacunal ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerAntibioticos($config)
        {
            $sql="";
            $sql = "select id_cat_antibiotico as id,
                    nombre_antibiotico as nom,
                    status as stat
                    from cat_antibiotico
                    order by nombre_antibiotico ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerVacunas($config)
        {
            $sql="";
            $sql = "select id_vacuna as id,
                    nombre_vacuna as nom,
                    codigo_vacuna as cod,
                    status as stat
                    from cat_vacuna
                    order by codigo_vacuna ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerCondiciones($config)
        {
            $sql="";
            $sql = "select id_condicion as id,
                    nombre_condicion as nom,
                    codigo_condicion as cod,
                    status as stat
                    from cat_vac_condicion
                    order by nombre_condicion ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerAntivirales($config)
        {
            $sql="";
            $sql = "select id_cat_antiviral as id,
                    nombre_antiviral as nom,
                    status as stat
                    from cat_antiviral
                    order by nombre_antiviral ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerTipoMuestras($config)
        {
            $sql="";
            $sql = "select id_cat_muestra_laboratorio as id,
                    nombre_muestra_laboratorio as nom,
                    status as stat
                    from cat_muestra_laboratorio
                    order by nombre_muestra_laboratorio ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }
        
        public static function obtenerModalidades($config)
        {
            $sql="";
            $sql = "select id_modalidad as id,
                    nombre_modalidad as nom,
                    habilita_nombre as hab,
                    status as stat
                    from cat_vac_modalidad
                    order by nombre_modalidad ASC
                    limit ".$config["inicio"].",".$config["paginado"];
            //echo $sql;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();
            
            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }

        public static function borrarTipoUbicacion($id)
        {
            $sql = "delete from tbl_ubicacion_tipo where idubicacion_tipo = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function editarTipoUbicacion($id, $n, $o, $s)
        {
            $sql = "update tbl_ubicacion_tipo
                   set nombre = '".trim($n)."', orden = '".trim($o)."', status = '".$s."' where idubicacion_tipo = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function agregarTipoUbicacion($n, $o, $s)
        {
            $sql = "insert into tbl_ubicacion_tipo (nombre, orden, status)
                    values ('".trim($n)."', '".trim($o)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function borrarEnfermedadCronica($id)
        {
            $sql = "delete from cat_enfermedad_cronica where id_cat_enfermedad_cronica = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarAntecedenteVacunal($id)
        {
            $sql = "delete from cat_antecendente_vacunal where id_cat_antecendente_vacunal = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarAntibiotico($id)
        {
            $sql = "delete from cat_antibiotico where id_cat_antibiotico = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarVacuna($id)
        {
            $sql = "delete from cat_vacuna where id_vacuna = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarCondicion($id)
        {
            $sql = "delete from cat_vac_condicion where id_condicion = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarAntiviral($id)
        {
            $sql = "delete from cat_antiviral where id_cat_antiviral = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarTipoMuestra($id)
        {
            $sql = "delete from cat_muestra_laboratorio where id_cat_muestra_laboratorio = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function borrarModadlidad($id)
        {
            $sql = "delete from cat_vac_modalidad where id_modalidad = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function editarEnfermedadCronica($id, $n, $s, $flu)
        {
            $sql = "update cat_enfermedad_cronica
                   set nombre_enfermedad_cronica = '".trim($n)."', influenza = '".trim($flu)."', status = '".$s."' where id_cat_enfermedad_cronica = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function editarAntecedenteVacunal($id, $n, $s, $flu)
        {
            $sql = "update cat_antecendente_vacunal
                   set nombre_antecendente_vacunal = '".trim($n)."', influenza = '".trim($flu)."', status = '".$s."' where id_cat_antecendente_vacunal = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function editarVacuna($id, $n, $c, $s)
        {
            $sql = "update cat_vacuna
                   set nombre_vacuna  = '".trim($n)."',codigo_vacuna  = '".trim($c)."', status = '".$s."' where id_vacuna = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function editarCondicion($id, $n, $c, $s)
        {
            $sql = "update cat_vac_condicion
                   set nombre_condicion  = '".trim($n)."',codigo_condicion  = '".trim($c)."', status = '".$s."' where id_condicion = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function editarAntiviral($id, $n, $s)
        {
            $sql = "update cat_antiviral
                   set nombre_antiviral  = '".trim($n)."', status = '".$s."' where id_cat_antiviral = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function editarTipoMuestra($id, $n, $s)
        {
            $sql = "update cat_muestra_laboratorio
                   set nombre_muestra_laboratorio = '".trim($n)."', status = '".$s."' where id_cat_muestra_laboratorio = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function editarModalidad($id, $n, $h, $s)
        {
            $sql = "update cat_vac_modalidad
                   set nombre_modalidad  = '".trim($n)."',habilita_nombre  = '".trim($h)."', status = '".$s."' where id_modalidad = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }
        
        public static function agregarEnfermedadCronica($n, $s, $flu)
        {
            $sql = "insert into cat_enfermedad_cronica (nombre_enfermedad_cronica, influenza, status)
                    values ('".trim($n)."', '".$flu."', '".$s."')";
            //echo $sql;
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarAntecedenteVacunal($n, $s, $flu)
        {
            $sql = "insert into cat_antecendente_vacunal (nombre_antecendente_vacunal, influenza, status)
                    values ('".trim($n)."', '".trim($flu)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarAntibiotico($n, $s)
        {
            $sql = "insert into cat_antibiotico (nombre_antibiotico, status)
                    values ('".trim($n)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarVacuna($n, $c, $s)
        {
            $sql = "insert into cat_vacuna (nombre_vacuna, codigo_vacuna, status)
                    values ('".trim($n)."','".trim($c)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarCondicion($n, $c, $s)
        {
            $sql = "insert into cat_vac_condicion (nombre_condicion, codigo_condicion, status)
                    values ('".trim($n)."','".trim($c)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarAntiviral($n, $s)
        {
            $sql = "insert into cat_antiviral (nombre_antiviral, status)
                    values ('".trim($n)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarTipoMuestra($n, $s)
        {
            $sql = "insert into cat_muestra_laboratorio (nombre_muestra_laboratorio, status)
                    values ('".trim($n)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function agregarModalidad($n, $h, $s)
        {
            $sql = "insert into cat_vac_modalidad (nombre_modalidad, habilita_nombre, status)
                    values ('".trim($n)."','".trim($h)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }
        
        public static function nivelExistente($o)
        {
            $sql = "select count(*) as total from tbl_ubicacion_tipo where orden = ".$o;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetchOne();
            $error = $conn->getError();
            $conn->closeConn();

            if($error=="")
                return $data["total"];
            else
                return '-1';
        }

        public static function obtenerTiposSeccion($config)
        {
            $sql="";
            $sql = "select tbl_seccion_tipo.idseccion_tipo as id,
                    tbl_seccion_tipo.`nombre` as nom,
                    tbl_seccion_tipo.`nivel` as ord,
                    tbl_seccion_tipo.`status` as stat
                    from `tbl_seccion_tipo`
                    order by tbl_seccion_tipo.`nivel` ASC
                    limit ".$config["inicio"].",".$config["paginado"];

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();

            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }

        public static function borrarTipoSeccion($id)
        {
            $sql = "delete from tbl_seccion_tipo where idseccion_tipo =".$id;
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function editarTipoSeccion($id, $n, $o, $s)
        {
            $sql = "update tbl_seccion_tipo
                   set nombre = '".trim($n)."', nivel = '".trim($o)."', status = '".$s."' where idseccion_tipo = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function agregarTipoSeccion($n, $o, $s)
        {
            $sql = "insert into tbl_seccion_tipo (nombre, nivel, status)
                    values ('".trim($n)."', '".trim($o)."', '".$s."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }

        public static function nivelExistenteSeccion($o)
        {
            $sql = "select count(*) as total from tbl_seccion_tipo where nivel = ".$o;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetchOne();
            $error = $conn->getError();
            $conn->closeConn();

            if($error=="")
                return $data["total"];
            else
                return '-1';
        }

        public static function obtenerAcciones($config)
        {
            $sql="";
            $sql = "select tbl_accion.idaccion as id,
                    tbl_accion.`nombre` as nom,
                    tbl_accion.`codigo` as ord
                    from `tbl_accion`
                    order by tbl_accion.`codigo` ASC
                    limit ".$config["inicio"].",".$config["paginado"];

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $data = $conn->fetch();
            $conn->closeConn();

            if ($error==""){
                return $data;
            }
            else
                return '-1';
        }

        public static function borrarAccion($id)
        {
            $sql = "delete from tbl_accion where idaccion = " . $id;

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function editarAccion($id, $n, $o)
        {
            $sql = "update tbl_accion
                   set nombre = '".trim($n)."', codigo = '".trim($o)."' where idaccion = " . $id;
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return '1';
            }
            else
                return '-1';
        }

        public static function agregarAccion($n, $o)
        {
            $sql = "insert into tbl_accion (nombre, codigo)
                    values ('".trim($n)."', '".trim($o)."')";

            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $id = $conn->getID();
            $error = $conn->getError();
            $conn->closeConn();

            if ($error==""){
                return $id;
            }
            else
                return '-1';
        }

        public static function accionExistente($n, $o)
        {
            $sql = "select count(*) as total from tbl_accion where nombre = '".$n."' AND codigo ='".$o."'";
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetchOne();
            $error = $conn->getError();
            $conn->closeConn();

            if($error=="")
                return $data["total"];
            else
                return '-1';
        }

    }
?>