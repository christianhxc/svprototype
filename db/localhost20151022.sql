-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2015 at 10:39 AM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sisvigdb`
--
CREATE DATABASE `sisvigdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sisvigdb`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `sp_create_eno_sabana`$$
CREATE  PROCEDURE `sp_create_eno_sabana`()
BEGIN
  create table eno_sabana as
 select * from eno_comprimido;
END$$

DROP PROCEDURE IF EXISTS `sp_create_mat_alerta_temprana`$$
CREATE  PROCEDURE `sp_create_mat_alerta_temprana`()
BEGIN 
create table tbl_mat_diagnostico as
select * from view_alerta_temprana;
END$$

DROP PROCEDURE IF EXISTS `sp_delete_eno_sabana`$$
CREATE  PROCEDURE `sp_delete_eno_sabana`()
BEGIN
drop table eno_sabana; 
END$$

DROP PROCEDURE IF EXISTS `sp_drop_mat_alerta_temprana`$$
CREATE  PROCEDURE `sp_drop_mat_alerta_temprana`()
BEGIN 
DROP TABLE tbl_mat_diagnostico;
END$$

DROP PROCEDURE IF EXISTS `sp_encabezado_crear`$$
CREATE  PROCEDURE `sp_encabezado_crear`()
BEGIN 
create table eno_detalle_nuevo as
select *
from eno_comprimido; 
END$$

DROP PROCEDURE IF EXISTS `sp_encabezado_eliminar`$$
CREATE  PROCEDURE `sp_encabezado_eliminar`()
BEGIN
  drop table eno_detalle_nuevo; 
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Sintomas2`
--

DROP TABLE IF EXISTS `Sintomas2`;
CREATE TABLE IF NOT EXISTS `Sintomas2` (
  `idSintomas2` int(11) NOT NULL,
  `descripcion` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idSintomas2`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auditoria_rae`
--

DROP TABLE IF EXISTS `auditoria_rae`;
CREATE TABLE IF NOT EXISTS `auditoria_rae` (
  `idauditoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_rae` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `id_un` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`idauditoria`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45868 ;

-- --------------------------------------------------------

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
CREATE TABLE IF NOT EXISTS `bitacora` (
  `idbitacora` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID unico generado automaticamente para hacer referencia a una entrada en la bitacora',
  `usuid` int(11) NOT NULL COMMENT 'ID del usuario que esta generando la accion en el sistema',
  `fecha` datetime NOT NULL COMMENT 'Fecha en la que se esta ejecutando la accion en el sistema por el usuario',
  `accion` int(11) NOT NULL COMMENT 'ID de la accion que esta generando el usuario, 1 = Agregar, 2 = Modificar y 3 = Eliminar',
  `tabla` varchar(60) NOT NULL COMMENT 'Nombre de la tabla en la que esta efectuando la accion',
  PRIMARY KEY (`idbitacora`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Registro de las acciones realizadas por el usuario en el sis' AUTO_INCREMENT=207061 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_ITS`
--

DROP TABLE IF EXISTS `cat_ITS`;
CREATE TABLE IF NOT EXISTS `cat_ITS` (
  `id_ITS` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la ITS',
  `nombre_ITS` varchar(100) NOT NULL COMMENT 'Nombre de la ITS',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_ITS`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con las ITS' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_adm_tratamiento`
--

DROP TABLE IF EXISTS `cat_adm_tratamiento`;
CREATE TABLE IF NOT EXISTS `cat_adm_tratamiento` (
  `id_adm_tratamiento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_adm_tratamiento` varchar(45) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_adm_tratamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_antecendente_vacunal`
--

DROP TABLE IF EXISTS `cat_antecendente_vacunal`;
CREATE TABLE IF NOT EXISTS `cat_antecendente_vacunal` (
  `id_cat_antecendente_vacunal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del antecedente vacunal',
  `nombre_antecendente_vacunal` varchar(45) NOT NULL COMMENT 'Descripcion del antecedente vacunal',
  `orden_antecendente_vacunal` int(11) NOT NULL COMMENT 'Orden del antecedente vacunal',
  `influenza` int(1) NOT NULL DEFAULT '0' COMMENT '0: No pertenece a flu 1: Pertenece a flu',
  `requerido` int(1) NOT NULL DEFAULT '0' COMMENT '0: No Requerido 1: Requerido',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_cat_antecendente_vacunal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de los antecedentes vacunales' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_antibiotico`
--

DROP TABLE IF EXISTS `cat_antibiotico`;
CREATE TABLE IF NOT EXISTS `cat_antibiotico` (
  `id_cat_antibiotico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del antibiotico',
  `nombre_antibiotico` varchar(45) NOT NULL COMMENT 'Descripcion del antibiotico',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_cat_antibiotico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de los antibioticos' AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_antiviral`
--

DROP TABLE IF EXISTS `cat_antiviral`;
CREATE TABLE IF NOT EXISTS `cat_antiviral` (
  `id_cat_antiviral` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del antiviral',
  `nombre_antiviral` varchar(45) NOT NULL COMMENT 'Descripcion del antiviral',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_cat_antiviral`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de los antivirales' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_cargo`
--

DROP TABLE IF EXISTS `cat_cargo`;
CREATE TABLE IF NOT EXISTS `cat_cargo` (
  `id_cargo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del cargo',
  `nombre_cargo` varchar(45) NOT NULL COMMENT 'Descripcion del cargo o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_cargo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de los cargos de quienes notifican' AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_clasi_reac_adv`
--

DROP TABLE IF EXISTS `cat_clasi_reac_adv`;
CREATE TABLE IF NOT EXISTS `cat_clasi_reac_adv` (
  `id_clasi_reac_adv` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_clasi_reac_adv` varchar(100) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_clasi_reac_adv`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_clasificacion_bk`
--

DROP TABLE IF EXISTS `cat_clasificacion_bk`;
CREATE TABLE IF NOT EXISTS `cat_clasificacion_bk` (
  `id_clasificacion_BK` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_clasificacion_BK` varchar(100) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_clasificacion_BK`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_clinica_tarv`
--

DROP TABLE IF EXISTS `cat_clinica_tarv`;
CREATE TABLE IF NOT EXISTS `cat_clinica_tarv` (
  `id_clinica_tarv` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la clinica TARV de ITS',
  `nombre_clinica_tarv` varchar(100) NOT NULL COMMENT 'Nombre de la clinica TARV',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_clinica_tarv`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con las clinicas TARV de Panama' AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_condicion_salida`
--

DROP TABLE IF EXISTS `cat_condicion_salida`;
CREATE TABLE IF NOT EXISTS `cat_condicion_salida` (
  `id_condicion_salida` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la condicion de salida',
  `nombre_condicion_salida` varchar(45) NOT NULL COMMENT 'Descripcion o nombre de la condicion de salida',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_condicion_salida`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de las condiciones de salida' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_conducta`
--

DROP TABLE IF EXISTS `cat_conducta`;
CREATE TABLE IF NOT EXISTS `cat_conducta` (
  `id_conducta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_conducta` varchar(100) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_conducta`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_corregimiento`
--

DROP TABLE IF EXISTS `cat_corregimiento`;
CREATE TABLE IF NOT EXISTS `cat_corregimiento` (
  `id_corregimiento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_corregimiento` varchar(100) NOT NULL COMMENT 'Nombre del corregimiento de Panamá',
  `id_distrito` int(11) NOT NULL COMMENT 'Código del distrito al que pertenece el corregimiento.',
  `cod_ref_minsa` varchar(10) DEFAULT NULL COMMENT 'Código de referencia con los catálogos del MINSA.',
  PRIMARY KEY (`id_corregimiento`),
  KEY `fk_distrito` (`id_distrito`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos de los corregimientos de Panamá' AUTO_INCREMENT=651 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_diag_etiologico`
--

DROP TABLE IF EXISTS `cat_diag_etiologico`;
CREATE TABLE IF NOT EXISTS `cat_diag_etiologico` (
  `id_diag_etiologico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del diagnostico etiologico',
  `nombre_diag_etiologico` varchar(100) NOT NULL COMMENT 'Nombre del diagnostico stiologico',
  `id_diag_sindromico` int(11) NOT NULL COMMENT 'Idetificador del diag sindromico al que pertenece el diag etilogico.',
  `sexo` int(11) DEFAULT '0' COMMENT 'Aquien le sirve 1: Hombre, 2: Mujer, 0:Ambos',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_diag_etiologico`),
  KEY `fk_diag_sindromico_etiologico` (`id_diag_sindromico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los diagnosticos etiologicos para VICITS' AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_diag_sindromico`
--

DROP TABLE IF EXISTS `cat_diag_sindromico`;
CREATE TABLE IF NOT EXISTS `cat_diag_sindromico` (
  `id_diag_sindromico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del diagnostico sindromico',
  `nombre_diag_sindromico` varchar(100) NOT NULL COMMENT 'Nombre del diagnostico sindromico',
  `sexo` int(11) DEFAULT '0' COMMENT 'Aquien le sirve 1: Hombre, 2: Mujer, 0:Ambos',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_diag_sindromico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los diagnosticos sindromicos para VICITS' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_distrito`
--

DROP TABLE IF EXISTS `cat_distrito`;
CREATE TABLE IF NOT EXISTS `cat_distrito` (
  `id_distrito` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_distrito` varchar(100) NOT NULL COMMENT 'Nombre del distrito de Panamá',
  `id_provincia` int(11) NOT NULL COMMENT 'Provincia a la que pertenece el distrito',
  `cod_ref_minsa` varchar(10) DEFAULT NULL COMMENT 'Código de referencia con los catálogos del MINSA',
  `id_region` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_distrito`),
  KEY `fk_provincia` (`id_provincia`),
  KEY `fk_region_distrito` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos de los distritos de Panamá' AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_droga`
--

DROP TABLE IF EXISTS `cat_droga`;
CREATE TABLE IF NOT EXISTS `cat_droga` (
  `id_droga` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la droga',
  `nombre_droga` varchar(100) NOT NULL COMMENT 'Nombre de la droga',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_droga`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con las drogas para VICITS' AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_edad`
--

DROP TABLE IF EXISTS `cat_edad`;
CREATE TABLE IF NOT EXISTS `cat_edad` (
  `tipo_edad` int(1) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  PRIMARY KEY (`tipo_edad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cat_embarazo_multiple`
--

DROP TABLE IF EXISTS `cat_embarazo_multiple`;
CREATE TABLE IF NOT EXISTS `cat_embarazo_multiple` (
  `id_embarazo_multiple` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tipo de embarazo multiple',
  `nombre_embarazo_multiple` varchar(45) NOT NULL COMMENT 'Descripcion del tipo de embarazo multiple',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_embarazo_multiple`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo del tipo de embarazo multiple' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_enfermedad_cronica`
--

DROP TABLE IF EXISTS `cat_enfermedad_cronica`;
CREATE TABLE IF NOT EXISTS `cat_enfermedad_cronica` (
  `id_cat_enfermedad_cronica` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la enfermedad cronica',
  `nombre_enfermedad_cronica` varchar(45) NOT NULL COMMENT 'Descripcion de la enfermedad cronica',
  `influenza` int(1) NOT NULL DEFAULT '0' COMMENT '0: No pertenece a flu 1: Pertenece a flu',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_cat_enfermedad_cronica`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de las enfermedades cronicas' AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_enfermedad_madre`
--

DROP TABLE IF EXISTS `cat_enfermedad_madre`;
CREATE TABLE IF NOT EXISTS `cat_enfermedad_madre` (
  `id_enfermedad_madre` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de las enfermedades que puede tener la madre',
  `nombre_enfermedad_madre` varchar(45) NOT NULL COMMENT 'Descripcion de la enfermedad de la madre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_enfermedad_madre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de las enfermedades que puede tener la madre' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_esquema_arv`
--

DROP TABLE IF EXISTS `cat_esquema_arv`;
CREATE TABLE IF NOT EXISTS `cat_esquema_arv` (
  `id_esquema_ARV` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_esquema_ARV` varchar(45) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_esquema_ARV`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_estudio`
--

DROP TABLE IF EXISTS `cat_estudio`;
CREATE TABLE IF NOT EXISTS `cat_estudio` (
  `id_estudio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del nivel de estudio de la madre',
  `nombre_estudio` varchar(45) NOT NULL COMMENT 'Descripcion del nivel de estudio o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_estudio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo del nivel de estudio al que puede pertencer la madr' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_etnia`
--

DROP TABLE IF EXISTS `cat_etnia`;
CREATE TABLE IF NOT EXISTS `cat_etnia` (
  `id_etnia` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la etnia',
  `nombre_etnia` varchar(45) NOT NULL COMMENT 'Descripcion de la etnia o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_etnia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de las etnias a la que puede pertencer la madre' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_etnia_tb`
--

DROP TABLE IF EXISTS `cat_etnia_tb`;
CREATE TABLE IF NOT EXISTS `cat_etnia_tb` (
  `id_etnia` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_etnia` varchar(100) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_etnia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_evento`
--

DROP TABLE IF EXISTS `cat_evento`;
CREATE TABLE IF NOT EXISTS `cat_evento` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_evento` varchar(250) NOT NULL COMMENT 'Nombre del Evento de Notificación Obligatoria',
  `activo` tinyint(1) NOT NULL COMMENT 'Determina si el evento está en la lista activa.',
  `id_gevento` int(11) NOT NULL COMMENT 'Código del grupo de evento',
  `cie_10_1` varchar(10) DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `cie_10_2` varchar(10) DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `cie_10_3` varchar(10) DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `cie_10_4` varchar(10) DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `cie_10_5` varchar(10) DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `cod_ref_minsa` varchar(10) DEFAULT NULL COMMENT 'Código de referencia del MINSA',
  PRIMARY KEY (`id_evento`),
  KEY `fk_gevento` (`id_gevento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos de los eventos de notificacion' AUTO_INCREMENT=37097 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_evento_causa_externa`
--

DROP TABLE IF EXISTS `cat_evento_causa_externa`;
CREATE TABLE IF NOT EXISTS `cat_evento_causa_externa` (
  `cie_10_evento` varchar(10) NOT NULL COMMENT 'codigo CIE10 del evento',
  `tipo_evento` int(1) NOT NULL COMMENT '1. Evento egreso cap XIX, 2. Causa Externa cap XX',
  PRIMARY KEY (`cie_10_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla donde se relaciona los eventos del capitulo XIX con lo';

-- --------------------------------------------------------

--
-- Table structure for table `cat_examen`
--

DROP TABLE IF EXISTS `cat_examen`;
CREATE TABLE IF NOT EXISTS `cat_examen` (
  `id_examen` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del examen',
  `nombre_examen` varchar(45) NOT NULL COMMENT 'Descripcion del examen o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_examen`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de examenes solicitados' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_exclusion`
--

DROP TABLE IF EXISTS `cat_exclusion`;
CREATE TABLE IF NOT EXISTS `cat_exclusion` (
  `id_exclusion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_exclusion` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_exclusion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_factor_riesgo`
--

DROP TABLE IF EXISTS `cat_factor_riesgo`;
CREATE TABLE IF NOT EXISTS `cat_factor_riesgo` (
  `id_factor` int(11) NOT NULL AUTO_INCREMENT,
  `factor_nombre` varchar(100) NOT NULL COMMENT 'Nombre del corregimiento de PanamÃ¡',
  `id_grupo_factor` int(11) NOT NULL COMMENT 'CÃ³digo del distrito al que pertenece el corregimiento.',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_factor`),
  KEY `fk_factor_riego_grupo_factor_riesgo` (`id_grupo_factor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los factores de riesgo para VIH' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_fluoroquinolonas`
--

DROP TABLE IF EXISTS `cat_fluoroquinolonas`;
CREATE TABLE IF NOT EXISTS `cat_fluoroquinolonas` (
  `id_fluoroquinolonas` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_fluoroquinolonas` varchar(45) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_fluoroquinolonas`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla de fluoroquinolonas para TB' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_genero`
--

DROP TABLE IF EXISTS `cat_genero`;
CREATE TABLE IF NOT EXISTS `cat_genero` (
  `id_genero` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del genero',
  `genero_nombre` varchar(100) NOT NULL COMMENT 'Nombre o especificacion del genero',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_genero`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con el catalogo de genero' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_gpopoblacional`
--

DROP TABLE IF EXISTS `cat_gpopoblacional`;
CREATE TABLE IF NOT EXISTS `cat_gpopoblacional` (
  `id_gpopoblacional` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_gpopoblacional` varchar(45) NOT NULL,
  `status` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_gpopoblacional`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla con los grupos poblacional para TB' AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_grupo_edad`
--

DROP TABLE IF EXISTS `cat_grupo_edad`;
CREATE TABLE IF NOT EXISTS `cat_grupo_edad` (
  `id_grupo_edad` int(11) NOT NULL,
  `desde` float NOT NULL,
  `hasta` float NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `limite_inferior_meses` int(11) DEFAULT NULL,
  `limite_superior_meses` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_grupo_edad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cat_grupo_evento`
--

DROP TABLE IF EXISTS `cat_grupo_evento`;
CREATE TABLE IF NOT EXISTS `cat_grupo_evento` (
  `id_gevento` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código del grupo de evento numérico, correlativo y autoincremental',
  `nombre_gevento` varchar(100) NOT NULL COMMENT 'Nombre del grupo de eventos',
  PRIMARY KEY (`id_gevento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_grupo_factor_riesgo`
--

DROP TABLE IF EXISTS `cat_grupo_factor_riesgo`;
CREATE TABLE IF NOT EXISTS `cat_grupo_factor_riesgo` (
  `id_grupo_factor` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_factor_nombre` varchar(100) NOT NULL COMMENT 'Nombre del corregimiento de PanamÃ¡',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_grupo_factor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los grupos de factores de riesgo para VIH' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_grupo_poblacion`
--

DROP TABLE IF EXISTS `cat_grupo_poblacion`;
CREATE TABLE IF NOT EXISTS `cat_grupo_poblacion` (
  `id_grupo_poblacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del grupo de poblacion para VICITS',
  `nombre_grupo_poblacion` varchar(100) NOT NULL COMMENT 'Nombre del grupo de poblacion para VICITS',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_grupo_poblacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los grupos de poblacion para VICITS' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_grupo_riesgo_mdr`
--

DROP TABLE IF EXISTS `cat_grupo_riesgo_mdr`;
CREATE TABLE IF NOT EXISTS `cat_grupo_riesgo_mdr` (
  `id_grupo_riesgo_MDR` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_MDR` varchar(45) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_grupo_riesgo_MDR`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_identidad_genero`
--

DROP TABLE IF EXISTS `cat_identidad_genero`;
CREATE TABLE IF NOT EXISTS `cat_identidad_genero` (
  `id_identidad_genero` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la identidad de genero',
  `nombre_identidad_genero` varchar(100) NOT NULL COMMENT 'Nombre o especificacion de la identidad de genero',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_identidad_genero`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con el catalogo de identidad de genero' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_infeccion_madre`
--

DROP TABLE IF EXISTS `cat_infeccion_madre`;
CREATE TABLE IF NOT EXISTS `cat_infeccion_madre` (
  `id_infeccion_madre` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de las infecciones que puede tener la madre',
  `nombre_infeccion_madre` varchar(45) NOT NULL COMMENT 'Descripcion de las infecciones de la madre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_infeccion_madre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de las infecciones que puede tener la madre' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_inmunodepresor`
--

DROP TABLE IF EXISTS `cat_inmunodepresor`;
CREATE TABLE IF NOT EXISTS `cat_inmunodepresor` (
  `id_inmunodepresor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_inmunodepresor` varchar(45) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_inmunodepresor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_inyect_2linea`
--

DROP TABLE IF EXISTS `cat_inyect_2linea`;
CREATE TABLE IF NOT EXISTS `cat_inyect_2linea` (
  `id_inyect_2linea` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_inyect_2linea` varchar(45) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_inyect_2linea`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla de Inyectables de 2a linea para TB' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_manifestacion`
--

DROP TABLE IF EXISTS `cat_manifestacion`;
CREATE TABLE IF NOT EXISTS `cat_manifestacion` (
  `id_manifestacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_manifestacion` varchar(100) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_manifestacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_muestra_laboratorio`
--

DROP TABLE IF EXISTS `cat_muestra_laboratorio`;
CREATE TABLE IF NOT EXISTS `cat_muestra_laboratorio` (
  `id_cat_muestra_laboratorio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la muestra de laboratorio',
  `nombre_muestra_laboratorio` varchar(45) NOT NULL COMMENT 'Descripcion de la muestra de laboratorio',
  `influenza` int(1) NOT NULL DEFAULT '0' COMMENT '0: No pertenece a flu 1: Pertenece a flu',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_cat_muestra_laboratorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de las muestras de laboratorio' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_ocupacion`
--

DROP TABLE IF EXISTS `cat_ocupacion`;
CREATE TABLE IF NOT EXISTS `cat_ocupacion` (
  `id_ocupacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la ocupacion',
  `nombre_ocupacion` varchar(45) NOT NULL COMMENT 'Descripcion de la ocupacion o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactiva 1: activada',
  `codigo_ocupacion` int(11) DEFAULT NULL COMMENT 'Codigo de relacion con el censo del 2000 en Panama',
  PRIMARY KEY (`id_ocupacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de ocupaciones' AUTO_INCREMENT=1691 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_pais`
--

DROP TABLE IF EXISTS `cat_pais`;
CREATE TABLE IF NOT EXISTS `cat_pais` (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del pais',
  `nombre_pais` varchar(30) NOT NULL COMMENT 'Nombre del pais',
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de los paises del mundo' AUTO_INCREMENT=242 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_procedimiento`
--

DROP TABLE IF EXISTS `cat_procedimiento`;
CREATE TABLE IF NOT EXISTS `cat_procedimiento` (
  `id_procedimiento` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del procedimiento',
  `codigo_procedimiento` varchar(10) NOT NULL COMMENT 'Codigo del procedimiento',
  `nombre_procedimiento` varchar(45) NOT NULL COMMENT 'Descripcion del procedimiento o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_procedimiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de los procedimientos' AUTO_INCREMENT=4040 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_profesion`
--

DROP TABLE IF EXISTS `cat_profesion`;
CREATE TABLE IF NOT EXISTS `cat_profesion` (
  `id_profesion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la ocupacion',
  `nombre_profesion` varchar(45) NOT NULL COMMENT 'Descripcion de la ocupacion o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactiva 1: activada',
  `orden` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_profesion`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de ocupaciones para TB' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_provincia`
--

DROP TABLE IF EXISTS `cat_provincia`;
CREATE TABLE IF NOT EXISTS `cat_provincia` (
  `id_provincia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_provincia` varchar(100) NOT NULL COMMENT 'Nombre de las provincias de Panamá',
  `cod_ref_minsa` varchar(10) DEFAULT NULL COMMENT 'Cóidgo de referencia con catálogo de provincias del MINSA',
  PRIMARY KEY (`id_provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos de las provincias de Panamá' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_prueba`
--

DROP TABLE IF EXISTS `cat_prueba`;
CREATE TABLE IF NOT EXISTS `cat_prueba` (
  `id_prueba` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de las pruebas solicitadas para VICITS',
  `nombre_prueba` varchar(100) NOT NULL COMMENT 'Nombre de las pruebas solicitadas para VICITS',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_prueba`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con las pruebas solicitadas para VICITS' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_rango`
--

DROP TABLE IF EXISTS `cat_rango`;
CREATE TABLE IF NOT EXISTS `cat_rango` (
  `id_rango` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_rango` varchar(45) NOT NULL,
  `activo` tinyint(1) unsigned NOT NULL,
  `tipo_rango` int(2) NOT NULL DEFAULT '1' COMMENT '1 para fomulario ENO, 2 para EHOS',
  PRIMARY KEY (`id_rango`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_region_salud`
--

DROP TABLE IF EXISTS `cat_region_salud`;
CREATE TABLE IF NOT EXISTS `cat_region_salud` (
  `id_region` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código correlativo númerico y autoincremental de las regiones de salud del MINSA',
  `nombre_region` varchar(100) NOT NULL COMMENT 'Nombre de la Región de Salud',
  `id_provincia` int(11) DEFAULT NULL COMMENT 'Identificador de la provincia',
  PRIMARY KEY (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos de las regiones de salud' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_reporte`
--

DROP TABLE IF EXISTS `cat_reporte`;
CREATE TABLE IF NOT EXISTS `cat_reporte` (
  `id_reporte` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_reporte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla con los grupos de factores de riesgo para VIH';

-- --------------------------------------------------------

--
-- Table structure for table `cat_servicio`
--

DROP TABLE IF EXISTS `cat_servicio`;
CREATE TABLE IF NOT EXISTS `cat_servicio` (
  `id_servicio` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del servicio de salud',
  `nombre_servicio` varchar(100) NOT NULL COMMENT 'Nombre del servicio de salud',
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene el nombre del servicio de salud' AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_servicio_rae`
--

DROP TABLE IF EXISTS `cat_servicio_rae`;
CREATE TABLE IF NOT EXISTS `cat_servicio_rae` (
  `id_servicio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del servicio',
  `codigo_servicio` varchar(45) NOT NULL COMMENT 'Codigo del servicio',
  `nombre_servicio` varchar(45) NOT NULL COMMENT 'Descripcion o nombre del servicio',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactiva 1: activada',
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de servicios medicos para RAE' AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_signo_sintoma`
--

DROP TABLE IF EXISTS `cat_signo_sintoma`;
CREATE TABLE IF NOT EXISTS `cat_signo_sintoma` (
  `id_signo_sintoma` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del signo y sintoma',
  `nombre_signo_sintoma` varchar(100) NOT NULL COMMENT 'Nombre del signo o sintoma',
  `sexo` int(11) DEFAULT '0' COMMENT 'Aquien le sirve 1: Hombre, 2: Mujer, 0:Ambos',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_signo_sintoma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los signos y sintomas' AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_sintoma`
--

DROP TABLE IF EXISTS `cat_sintoma`;
CREATE TABLE IF NOT EXISTS `cat_sintoma` (
  `id_sintoma` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del signo o sintoma',
  `nombre_sintoma` varchar(45) NOT NULL COMMENT 'Descripcion del sintoma o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_sintoma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de signos o sintomas' AUTO_INCREMENT=216 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_tipo_identidad`
--

DROP TABLE IF EXISTS `cat_tipo_identidad`;
CREATE TABLE IF NOT EXISTS `cat_tipo_identidad` (
  `id_tipo_identidad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tipo de identidad',
  `nombre_tipo` varchar(100) NOT NULL COMMENT 'Nombre del tipo de identidad',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1:activo, 0:no activo',
  PRIMARY KEY (`id_tipo_identidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tipo del documento de identidad del paciente' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_tipo_muestra`
--

DROP TABLE IF EXISTS `cat_tipo_muestra`;
CREATE TABLE IF NOT EXISTS `cat_tipo_muestra` (
  `id_tipo_muestra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tipo de muestra',
  `nombre_tipo_muestra` varchar(45) NOT NULL COMMENT 'Descripcion del tipo de muestra o nombre',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0: desactivo 1: activo',
  PRIMARY KEY (`id_tipo_muestra`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Catalogo de tipo muestra' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_tipo_paciente`
--

DROP TABLE IF EXISTS `cat_tipo_paciente`;
CREATE TABLE IF NOT EXISTS `cat_tipo_paciente` (
  `id_tipo_paciente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del tipo de paciente',
  `nombre_tipo_paciente` varchar(60) NOT NULL COMMENT 'Nombre o descripcion del tipo de paciente',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1: activo, 0: no activo',
  PRIMARY KEY (`id_tipo_paciente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='tipo de paciente para el formulario RAE' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_tipo_visita`
--

DROP TABLE IF EXISTS `cat_tipo_visita`;
CREATE TABLE IF NOT EXISTS `cat_tipo_visita` (
  `id_tipo_visita` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_tipo_visita` varchar(100) NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `orden` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_tipo_visita`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_tipos_muestras`
--

DROP TABLE IF EXISTS `cat_tipos_muestras`;
CREATE TABLE IF NOT EXISTS `cat_tipos_muestras` (
  `id_tipos_muestras` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del tipo de muestra para VICITS',
  `nombre_tipos_muestras` varchar(100) NOT NULL COMMENT 'Nombre del tipo de muestra para VICITS',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_tipos_muestras`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los tipos de muestras para VICITS' AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_tratamiento`
--

DROP TABLE IF EXISTS `cat_tratamiento`;
CREATE TABLE IF NOT EXISTS `cat_tratamiento` (
  `id_tratamiento` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del tratamiento',
  `nombre_tratamiento` varchar(200) NOT NULL COMMENT 'Nombre del tratamiento',
  `id_diag_sindromico` int(11) NOT NULL COMMENT 'Identificador del diag sindromico al que pertenece el tratamiento.',
  `sexo` int(11) DEFAULT '0' COMMENT 'Aquien le sirve 1: Hombre, 2: Mujer, 0:Ambos',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_tratamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los tratamiento para VICITS' AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_unidad_notificadora`
--

DROP TABLE IF EXISTS `cat_unidad_notificadora`;
CREATE TABLE IF NOT EXISTS `cat_unidad_notificadora` (
  `id_un` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_un` varchar(100) NOT NULL COMMENT 'Nombre de la Unidad Notificadora',
  `sector_un` int(1) NOT NULL COMMENT 'Sector al que pertenece la Unidad notificadora:\n1 = MINSA\n2 = Caja del Seguro Social\n3 = Privado\n4 = ONG\n5 = Cooperación Externa\n6 = Otros',
  `id_region` int(11) NOT NULL COMMENT 'Código de la región de salud a la que pertenece.',
  `id_corregimiento` int(11) NOT NULL COMMENT 'Código del corregimiento en el que está ubicada la unidad notificadora',
  `cod_ref_minsa` varchar(10) DEFAULT NULL COMMENT 'Código referencia del MINSA',
  `status` int(11) DEFAULT '1' COMMENT 'Estado del registro, 1 = Habilitado y 0 = Deshabilitado',
  `idtipo_instalacion` int(11) NOT NULL COMMENT 'Identificador del tipo de instalacion',
  PRIMARY KEY (`id_un`),
  KEY `fk_region` (`id_region`),
  KEY `fk_corregimiento` (`id_corregimiento`),
  KEY `fk_tipo_unidad` (`idtipo_instalacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos de la Unidad Notificadora' AUTO_INCREMENT=3211 ;

-- --------------------------------------------------------

--
-- Table structure for table `denominador`
--

DROP TABLE IF EXISTS `denominador`;
CREATE TABLE IF NOT EXISTS `denominador` (
  `id_denominador` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_ingreso` date NOT NULL,
  `fecha_notificacion` date NOT NULL,
  `responsable` varchar(150) NOT NULL,
  `semana_epidemilogica` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `id_un` int(11) NOT NULL,
  PRIMARY KEY (`id_denominador`),
  UNIQUE KEY `id_unidad_notificadora_2` (`id_un`,`semana_epidemilogica`,`anio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `denominador_detalle`
--

DROP TABLE IF EXISTS `denominador_detalle`;
CREATE TABLE IF NOT EXISTS `denominador_detalle` (
  `id_denominador_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_denominador` int(11) NOT NULL,
  `hospitalizacion_m` int(11) NOT NULL DEFAULT '0',
  `hospitalizacion_f` int(11) NOT NULL DEFAULT '0',
  `hospitalizacion_m_irag` int(11) NOT NULL DEFAULT '0',
  `hospitalizacion_f_irag` int(11) NOT NULL DEFAULT '0',
  `uci_m` int(11) NOT NULL DEFAULT '0',
  `uci_f` int(11) NOT NULL DEFAULT '0',
  `uci_m_irag` int(11) NOT NULL DEFAULT '0',
  `uci_f_irag` int(11) NOT NULL DEFAULT '0',
  `defuncion_m` int(11) NOT NULL DEFAULT '0',
  `defuncion_f` int(11) NOT NULL DEFAULT '0',
  `defuncion_m_irag` int(11) NOT NULL DEFAULT '0',
  `defuncion_f_irag` int(11) NOT NULL DEFAULT '0',
  `id_grupo_edad` int(11) NOT NULL,
  PRIMARY KEY (`id_denominador_detalle`),
  KEY `fk_denominador_denominador_detalle_iddenominador` (`id_denominador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `eno_comprimido`
--
DROP VIEW IF EXISTS `eno_comprimido`;
CREATE TABLE IF NOT EXISTS `eno_comprimido` (
`id_un` int(11)
,`nombre_un` varchar(100)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`semana_epi` int(2)
,`anio` int(4)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`id_evento` int(11)
,`id_gevento` int(11)
,`sexo` varchar(1)
,`id_rango` int(10) unsigned
,`nombre_rango` varchar(45)
,`numero_casos` decimal(32,0)
,`sector_un` int(1)
,`id_enc` int(11)
,`menor_uno_m` decimal(32,0)
,`uno_cuatro_m` decimal(32,0)
,`cinco_nueve_m` decimal(32,0)
,`diez_catorce_m` decimal(32,0)
,`quince_diecinueve_m` decimal(32,0)
,`veinte_veinticuatro_m` decimal(32,0)
,`veinticinco_treitaycuatro_m` decimal(32,0)
,`treintaycinco_cuarentaynueve_m` decimal(32,0)
,`cincuenta_cincuentaynueve_m` decimal(32,0)
,`sesenta_sesentaycuantro_m` decimal(32,0)
,`mas_sesentaycinco_m` decimal(32,0)
,`ne_m` decimal(32,0)
,`menor_uno_f` decimal(32,0)
,`uno_cuatro_f` decimal(32,0)
,`cinco_nueve_f` decimal(32,0)
,`diez_catorce_f` decimal(32,0)
,`quince_diecinueve_f` decimal(32,0)
,`veinte_veinticuatro_f` decimal(32,0)
,`veinticinco_treitaycuatro_f` decimal(32,0)
,`treintaycinco_cuarentaynueve_f` decimal(32,0)
,`cincuenta_cincuentaynueve_f` decimal(32,0)
,`sesenta_sesentaycuantro_f` decimal(32,0)
,`mas_sesentaycinco_f` decimal(32,0)
,`ne_f` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `eno_detallado`
--
DROP VIEW IF EXISTS `eno_detallado`;
CREATE TABLE IF NOT EXISTS `eno_detallado` (
`id_un` int(11)
,`sector_un` int(1)
,`nombre_un` varchar(100)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`semana_epi` int(2)
,`anio` int(4)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`id_evento` int(11)
,`id_gevento` int(11)
,`total` decimal(54,0)
,`menor_uno_m` decimal(54,0)
,`uno_cuatro_m` decimal(54,0)
,`cinco_nueve_m` decimal(54,0)
,`diez_catorce_m` decimal(54,0)
,`quince_diecinueve_m` decimal(54,0)
,`veinte_veinticuatro_m` decimal(54,0)
,`veinticinco_treitaycuatro_m` decimal(54,0)
,`treintaycinco_cuarentaynueve_m` decimal(54,0)
,`cincuenta_cincuentaynueve_m` decimal(54,0)
,`sesenta_sesentaycuantro_m` decimal(54,0)
,`mas_sesentaycinco_m` decimal(54,0)
,`ne_m` decimal(54,0)
,`menor_uno_f` decimal(54,0)
,`uno_cuatro_f` decimal(54,0)
,`cinco_nueve_f` decimal(54,0)
,`diez_catorce_f` decimal(54,0)
,`quince_diecinueve_f` decimal(54,0)
,`veinte_veinticuatro_f` decimal(54,0)
,`veinticinco_treitaycuatro_f` decimal(54,0)
,`treintaycinco_cuarentaynueve_f` decimal(54,0)
,`cincuenta_cincuentaynueve_f` decimal(54,0)
,`sesenta_sesentaycuantro_f` decimal(54,0)
,`mas_sesentaycinco_f` decimal(54,0)
,`ne_f` decimal(54,0)
);
-- --------------------------------------------------------

--
-- Table structure for table `eno_detalle`
--

DROP TABLE IF EXISTS `eno_detalle`;
CREATE TABLE IF NOT EXISTS `eno_detalle` (
  `id_enc` int(11) NOT NULL COMMENT 'Código del encabezado del formulario de notificación',
  `id_evento` int(11) NOT NULL COMMENT 'Código del evento notificado',
  `sexo` varchar(1) NOT NULL COMMENT 'Sexo de los casos reportados:\nM= Masculino\nF = Femenino',
  `id_rango` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Edad en años de los casos notificados',
  `numero_casos` int(11) NOT NULL DEFAULT '0' COMMENT 'Número de casos reportados',
  PRIMARY KEY (`id_evento`,`sexo`,`id_rango`,`id_enc`),
  KEY `fk_enc` (`id_enc`),
  KEY `fk_evento` (`id_evento`),
  KEY `FK_cat_edad` (`id_rango`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos del detalle de los ENO';

-- --------------------------------------------------------

--
-- Table structure for table `eno_detalle_nuevo`
--

DROP TABLE IF EXISTS `eno_detalle_nuevo`;
CREATE TABLE IF NOT EXISTS `eno_detalle_nuevo` (
  `id_un` int(11) NOT NULL DEFAULT '0',
  `nombre_un` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la Unidad Notificadora',
  `id_provincia` int(11) NOT NULL DEFAULT '0',
  `nombre_provincia` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de las provincias de Panamá',
  `id_region` int(11) NOT NULL DEFAULT '0' COMMENT 'Código correlativo númerico y autoincremental de las regiones de salud del MINSA',
  `nombre_region` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la Región de Salud',
  `id_distrito` int(11) NOT NULL DEFAULT '0',
  `nombre_distrito` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del distrito de Panamá',
  `id_corregimiento` int(11) NOT NULL DEFAULT '0',
  `nombre_corregimiento` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del corregimiento de Panamá',
  `semana_epi` int(2) NOT NULL COMMENT 'Semana epidemiológica',
  `anio` int(4) NOT NULL,
  `cie_10_1` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `nombre_evento` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del Evento de Notificación Obligatoria',
  `id_evento` int(11) NOT NULL DEFAULT '0',
  `id_gevento` int(11) NOT NULL COMMENT 'Código del grupo de evento',
  `sexo` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT 'Sexo de los casos reportados:\nM= Masculino\nF = Femenino',
  `id_rango` int(10) unsigned NOT NULL DEFAULT '0',
  `nombre_rango` varchar(45) NOT NULL,
  `numero_casos` decimal(32,0) DEFAULT NULL,
  `sector_un` int(1) NOT NULL COMMENT 'Sector al que pertenece la Unidad notificadora:\n1 = MINSA\n2 = Caja del Seguro Social\n3 = Privado\n4 = ONG\n5 = Cooperación Externa\n6 = Otros',
  `id_enc` int(11) NOT NULL DEFAULT '0' COMMENT 'Código numérico correlativo y autoincremental del formulario',
  `menor_uno_m` decimal(32,0) DEFAULT NULL,
  `uno_cuatro_m` decimal(32,0) DEFAULT NULL,
  `cinco_nueve_m` decimal(32,0) DEFAULT NULL,
  `diez_catorce_m` decimal(32,0) DEFAULT NULL,
  `quince_diecinueve_m` decimal(32,0) DEFAULT NULL,
  `veinte_veinticuatro_m` decimal(32,0) DEFAULT NULL,
  `veinticinco_treitaycuatro_m` decimal(32,0) DEFAULT NULL,
  `treintaycinco_cuarentaynueve_m` decimal(32,0) DEFAULT NULL,
  `cincuenta_cincuentaynueve_m` decimal(32,0) DEFAULT NULL,
  `sesenta_sesentaycuantro_m` decimal(32,0) DEFAULT NULL,
  `mas_sesentaycinco_m` decimal(32,0) DEFAULT NULL,
  `ne_m` decimal(32,0) DEFAULT NULL,
  `menor_uno_f` decimal(32,0) DEFAULT NULL,
  `uno_cuatro_f` decimal(32,0) DEFAULT NULL,
  `cinco_nueve_f` decimal(32,0) DEFAULT NULL,
  `diez_catorce_f` decimal(32,0) DEFAULT NULL,
  `quince_diecinueve_f` decimal(32,0) DEFAULT NULL,
  `veinte_veinticuatro_f` decimal(32,0) DEFAULT NULL,
  `veinticinco_treitaycuatro_f` decimal(32,0) DEFAULT NULL,
  `treintaycinco_cuarentaynueve_f` decimal(32,0) DEFAULT NULL,
  `cincuenta_cincuentaynueve_f` decimal(32,0) DEFAULT NULL,
  `sesenta_sesentaycuantro_f` decimal(32,0) DEFAULT NULL,
  `mas_sesentaycinco_f` decimal(32,0) DEFAULT NULL,
  `ne_f` decimal(32,0) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eno_encabezado`
--

DROP TABLE IF EXISTS `eno_encabezado`;
CREATE TABLE IF NOT EXISTS `eno_encabezado` (
  `id_enc` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código numérico correlativo y autoincremental del formulario',
  `no_form` varchar(60) DEFAULT NULL COMMENT 'Número de referencia del formulario',
  `id_un` int(11) NOT NULL COMMENT 'Código de unidad notificadora',
  `semana_epi` int(2) NOT NULL COMMENT 'Semana epidemiológica',
  `fecha_inic` date NOT NULL COMMENT 'Fecha de inicio del reporte',
  `fecha_fin` date NOT NULL COMMENT 'Fecha fin reporte',
  `anio` int(4) NOT NULL,
  `zona` varchar(2) NOT NULL DEFAULT 'NA' COMMENT 'Se guarda la relación de la residencia del paciente con la instalación donde fue atendido, si reside dentro del corregimiento = "1"; si esta fuera del o los corregimientos = "FR"; los que estan fuera de la región de salud = "FA" y si no aplica = "NA"',
  `org_codigo` text CHARACTER SET latin1 NOT NULL COMMENT 'Codigo de la organizacion a la que pertence el usuario que ingreso el formulario',
  `id_servicio` int(4) NOT NULL DEFAULT '14' COMMENT 'Identificador del servicio que reporta',
  PRIMARY KEY (`id_enc`),
  UNIQUE KEY `uk_encabezado` (`id_un`,`semana_epi`,`anio`,`id_servicio`),
  KEY `fk_un` (`id_un`),
  KEY `fk_eno_enc_servicio` (`id_servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos del encabezado del formulario de ENO' AUTO_INCREMENT=76849 ;

-- --------------------------------------------------------

--
-- Table structure for table `eno_sabana`
--

DROP TABLE IF EXISTS `eno_sabana`;
CREATE TABLE IF NOT EXISTS `eno_sabana` (
  `id_un` int(11) NOT NULL DEFAULT '0',
  `nombre_un` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la Unidad Notificadora',
  `id_provincia` int(11) NOT NULL DEFAULT '0',
  `nombre_provincia` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de las provincias de Panamá',
  `id_region` int(11) NOT NULL DEFAULT '0' COMMENT 'Código correlativo númerico y autoincremental de las regiones de salud del MINSA',
  `nombre_region` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la Región de Salud',
  `id_distrito` int(11) NOT NULL DEFAULT '0',
  `nombre_distrito` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del distrito de Panamá',
  `id_corregimiento` int(11) NOT NULL DEFAULT '0',
  `nombre_corregimiento` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del corregimiento de Panamá',
  `semana_epi` int(2) NOT NULL COMMENT 'Semana epidemiológica',
  `anio` int(4) NOT NULL,
  `cie_10_1` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Código CIE-10 de referencia',
  `nombre_evento` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del Evento de Notificación Obligatoria',
  `id_evento` int(11) NOT NULL DEFAULT '0',
  `id_gevento` int(11) NOT NULL COMMENT 'Código del grupo de evento',
  `sexo` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT 'Sexo de los casos reportados:\nM= Masculino\nF = Femenino',
  `id_rango` int(10) unsigned NOT NULL DEFAULT '0',
  `nombre_rango` varchar(45) NOT NULL,
  `numero_casos` decimal(32,0) DEFAULT NULL,
  `sector_un` int(1) NOT NULL COMMENT 'Sector al que pertenece la Unidad notificadora:\n1 = MINSA\n2 = Caja del Seguro Social\n3 = Privado\n4 = ONG\n5 = Cooperación Externa\n6 = Otros',
  `id_enc` int(11) NOT NULL DEFAULT '0' COMMENT 'Código numérico correlativo y autoincremental del formulario',
  `menor_uno_m` decimal(32,0) DEFAULT NULL,
  `uno_cuatro_m` decimal(32,0) DEFAULT NULL,
  `cinco_nueve_m` decimal(32,0) DEFAULT NULL,
  `diez_catorce_m` decimal(32,0) DEFAULT NULL,
  `quince_diecinueve_m` decimal(32,0) DEFAULT NULL,
  `veinte_veinticuatro_m` decimal(32,0) DEFAULT NULL,
  `veinticinco_treitaycuatro_m` decimal(32,0) DEFAULT NULL,
  `treintaycinco_cuarentaynueve_m` decimal(32,0) DEFAULT NULL,
  `cincuenta_cincuentaynueve_m` decimal(32,0) DEFAULT NULL,
  `sesenta_sesentaycuantro_m` decimal(32,0) DEFAULT NULL,
  `mas_sesentaycinco_m` decimal(32,0) DEFAULT NULL,
  `ne_m` decimal(32,0) DEFAULT NULL,
  `menor_uno_f` decimal(32,0) DEFAULT NULL,
  `uno_cuatro_f` decimal(32,0) DEFAULT NULL,
  `cinco_nueve_f` decimal(32,0) DEFAULT NULL,
  `diez_catorce_f` decimal(32,0) DEFAULT NULL,
  `quince_diecinueve_f` decimal(32,0) DEFAULT NULL,
  `veinte_veinticuatro_f` decimal(32,0) DEFAULT NULL,
  `veinticinco_treitaycuatro_f` decimal(32,0) DEFAULT NULL,
  `treintaycinco_cuarentaynueve_f` decimal(32,0) DEFAULT NULL,
  `cincuenta_cincuentaynueve_f` decimal(32,0) DEFAULT NULL,
  `sesenta_sesentaycuantro_f` decimal(32,0) DEFAULT NULL,
  `mas_sesentaycinco_f` decimal(32,0) DEFAULT NULL,
  `ne_f` decimal(32,0) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eno_transicion`
--

DROP TABLE IF EXISTS `eno_transicion`;
CREATE TABLE IF NOT EXISTS `eno_transicion` (
  `id_reg` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para reiniciar',
  `cod_ref_minsa` varchar(10) NOT NULL COMMENT 'Codigo de la instalación de salud segun el MINSA',
  `semana_epi` int(11) NOT NULL COMMENT 'Numero de la semana epidemiológica ',
  `anio` int(11) NOT NULL COMMENT 'anio en que se reporta',
  `zona` varchar(5) NOT NULL COMMENT 'NA: No aplica, FR: Fuera de zona, 1: Dentro de la instalación de salud',
  `cie10` varchar(10) NOT NULL COMMENT 'Código cie10 del evento que reportan',
  `rango_f_1` int(3) NOT NULL COMMENT 'Rango < de un año para el sexo femenino',
  `rango_f_2` int(3) NOT NULL COMMENT 'Rango 1-4 años de edad para el sexo femenino',
  `rango_f_3` int(3) NOT NULL COMMENT 'Rango 5-9 años de edad para el sexo femenino',
  `rango_f_4` int(3) NOT NULL COMMENT 'Rango 10-14 años de edad para el sexo femenino',
  `rango_f_5` int(3) NOT NULL COMMENT 'Rango 15-19 años de edad para el sexo femenino',
  `rango_f_6` int(3) NOT NULL COMMENT 'Rango 20-24 años de edad para el sexo femenino',
  `rango_f_7` int(3) NOT NULL COMMENT 'Rango 25-34 años de edad para el sexo femenino',
  `rango_f_8` int(3) NOT NULL COMMENT 'Rango 35-49 años de edad para el sexo femenino',
  `rango_f_9` int(3) NOT NULL COMMENT 'Rango 50-59 años de edad para el sexo femenino',
  `rango_f_10` int(3) NOT NULL COMMENT 'Rango 60-64 años de edad para el sexo femenino',
  `rango_f_11` int(3) NOT NULL COMMENT 'Rango de 65 o + años de edad para el sexo femenino',
  `rango_f_12` int(3) NOT NULL COMMENT 'Rango para edades NE (no especificadas) para el sexo femenino',
  `rango_m_1` int(3) NOT NULL COMMENT 'Rango < de un año para el sexo masculino',
  `rango_m_2` int(3) NOT NULL COMMENT 'Rango 1-4 años de edad para el sexo masculino',
  `rango_m_3` int(3) NOT NULL COMMENT 'Rango 5-9 años de edad para el sexo masculino',
  `rango_m_4` int(3) NOT NULL COMMENT 'Rango 10-14 años de edad para el sexo masculino',
  `rango_m_5` int(3) NOT NULL COMMENT 'Rango 15-19 años de edad para el sexo masculino',
  `rango_m_6` int(3) NOT NULL COMMENT 'Rango 20-24 años de edad para el sexo masculino',
  `rango_m_7` int(3) NOT NULL COMMENT 'Rango 25-34 años de edad para el sexo masculino',
  `rango_m_8` int(3) NOT NULL COMMENT 'Rango 35-49 años de edad para el sexo masculino',
  `rango_m_9` int(3) NOT NULL COMMENT 'Rango 50-59 años de edad para el sexo masculino',
  `rango_m_10` int(3) NOT NULL COMMENT 'Rango 60-64 años de edad para el sexo masculino',
  `rango_m_11` int(3) NOT NULL COMMENT 'Rango de 65 o + años de edad para el sexo masculino',
  `rango_m_12` int(3) NOT NULL COMMENT 'Rango para edades NE (no especificadas) para el sexo masculino',
  `id_servicio` int(11) NOT NULL DEFAULT '14' COMMENT 'Identificador del servicio',
  PRIMARY KEY (`id_reg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de transicion para organizar los datos del archivo de ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_antecendente_vacunal`
--

DROP TABLE IF EXISTS `flureg_antecendente_vacunal`;
CREATE TABLE IF NOT EXISTS `flureg_antecendente_vacunal` (
  `id_flureg_antecendente_vacunal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `tipo_identificacion` int(11) NOT NULL COMMENT 'Identificador del tipo de identidad del paciente',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Numero de identificacion del paciente',
  `id_cat_antecendente_vacunal` int(11) NOT NULL COMMENT 'Identificador del antecedente vacunal',
  `dosis` int(2) DEFAULT NULL COMMENT 'Numero de la dosis de la vacuna',
  `fecha` date DEFAULT NULL COMMENT 'Fecha antecedente vacunal',
  PRIMARY KEY (`id_flureg_antecendente_vacunal`),
  KEY `fk_flureg_antecendente_vacunal_tbl_persona` (`tipo_identificacion`,`numero_identificacion`),
  KEY `fk_flureg_antecendente_vacunal_cat_antecendente_vacunal` (`id_cat_antecendente_vacunal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre el flureg y los antecedentes vacunal' AUTO_INCREMENT=3027010 ;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_enfermedad_cronica`
--

DROP TABLE IF EXISTS `flureg_enfermedad_cronica`;
CREATE TABLE IF NOT EXISTS `flureg_enfermedad_cronica` (
  `id_flureg_enfermedad_cronica` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `tipo_identificacion` int(11) NOT NULL COMMENT 'Identificador del tipo de identidad del paciente',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Numero de identificacion del paciente',
  `id_cat_enfermedad_cronica` int(11) NOT NULL COMMENT 'Identificador de la enfermedad cronica',
  `resultado` int(11) NOT NULL COMMENT 'Resultado 1:si, 0:no, 2:desconocido',
  PRIMARY KEY (`id_flureg_enfermedad_cronica`),
  KEY `fk_flureg_enfermedad_cronica_tbl_persona` (`tipo_identificacion`,`numero_identificacion`),
  KEY `fk_flureg_enfermedad_cronica_cat_enfermedad_cronica` (`id_cat_enfermedad_cronica`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre el flureg y las enfermedades' AUTO_INCREMENT=3009343 ;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_form`
--

DROP TABLE IF EXISTS `flureg_form`;
CREATE TABLE IF NOT EXISTS `flureg_form` (
  `id_flureg` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario, autoincrementado',
  `tipo_identificacion` int(11) DEFAULT NULL COMMENT 'Identificador del tipo de identidad del paciente',
  `numero_identificacion` varchar(30) DEFAULT NULL COMMENT 'Numero de identificacion del paciente',
  `id_un` int(11) DEFAULT NULL COMMENT 'Identificador de la unidad que reporta el caso',
  `unidad_disponible` int(11) DEFAULT NULL COMMENT 'Unidad notificadora disponible 1:si 0:no',
  `per_tipo_paciente` int(1) DEFAULT NULL COMMENT '1:Ambulatorio, 2:Hospitalizado, 3:Desconoce',
  `per_hospitalizado` int(1) DEFAULT NULL COMMENT '1:Si, 2:No',
  `per_hospitalizado_lugar` int(1) DEFAULT NULL COMMENT '1:Observacion, 2:Sala, 3:UCI',
  `nombre_investigador` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que investiga',
  `nombre_registra` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `fecha_formulario` date DEFAULT NULL COMMENT 'Fecha de cuando se creo el formulario',
  `per_asegurado` int(1) DEFAULT NULL COMMENT '1:asegurado, 0:no asegurado',
  `per_edad` int(11) DEFAULT NULL COMMENT 'edad de la persona a la hora de llenar el formulario',
  `per_tipo_edad` int(11) DEFAULT NULL COMMENT 'tipo edad de la persona a la hora de llenar el formulario',
  `id_pais` int(11) DEFAULT NULL COMMENT 'Identificador del pais de residencia de la persona',
  `id_corregimiento` int(11) DEFAULT NULL COMMENT 'Identificador del corregimiento de la persona a la hora de llenar el formulario',
  `per_direccion` varchar(100) DEFAULT NULL COMMENT 'direccion de la persona al momento de llenar el formulario',
  `per_direccion_otra` varchar(150) DEFAULT NULL COMMENT 'Referencia para localizar la casa',
  `per_telefono` int(20) DEFAULT NULL COMMENT 'Telefono de quien reporta',
  `vac_tarjeta` int(1) DEFAULT NULL COMMENT 'Porta la tarjeta de vacuna 1:si, 0:no',
  `vac_segun_esquema` int(1) DEFAULT NULL COMMENT 'Corresponde vacuna segun esquema 1:si, 0:no',
  `vac_fecha_ultima_dosis` varchar(45) DEFAULT NULL COMMENT 'Fecha de la ultima dosis anio en anterior 00/00/0000: No recibida, 99/99/9999: se desconoce',
  `vac_fecha_anio_previo` varchar(45) DEFAULT NULL COMMENT 'Fecha vacunacion influenza año previo',
  `vac_nombre_otra` varchar(150) DEFAULT NULL COMMENT 'Nombre de otra vacuna aplicada',
  `riesgo_embarazo` int(1) DEFAULT NULL COMMENT 'Esta en embarazo 1:si, 0:no, 2:desconocido',
  `riesgo_trimestre` int(1) DEFAULT NULL COMMENT 'Trimestre si esta embarazada 1:primero, 2:2do, 3:tercero',
  `riesgo_enf_cronica` int(1) DEFAULT NULL COMMENT 'Enfermedad cronica 1:si, 0:no, 2:desconocido',
  `riesgo_enf_relacionadas` varchar(150) DEFAULT NULL COMMENT 'Enfermedades Relacionadas',
  `riesgo_profesional` int(1) DEFAULT NULL COMMENT 'Riesgo profesional 1:si, 0:no, 2:desconocido',
  `riesgo_pro_cual` int(1) DEFAULT NULL COMMENT 'cual riesgo profesional tiene 1:Trab. agropecuario, 2:Trab. salud, 3:otro',
  `riesgo_viaje` int(1) DEFAULT NULL COMMENT 'ha viajadado 15 dias antes 1:si, 0:no, 2:desconocido',
  `riesgo_viaje_donde` varchar(150) DEFAULT NULL COMMENT 'Donde ha viajado el paciente en los ultimos 15 dias',
  `riesgo_contacto_confirmado` int(1) DEFAULT NULL COMMENT 'Contacto de caso confirmado 1:si, 0:no, 2:desconocido',
  `riesgo_contacto_tipo` int(1) DEFAULT NULL COMMENT 'Tipo de contacto 0:familiar, 1:laboral, 2:escolar, 3:social',
  `riesgo_aislamiento` int(1) DEFAULT NULL COMMENT 'Indicacion de aislamiento 1:si, 0:no, 2:desconocido',
  `riesgo_contacto_nombre` varchar(150) DEFAULT NULL COMMENT 'Nombre la persona con quien tuvo el contacto',
  `id_evento` int(11) DEFAULT NULL COMMENT 'Identificador del evento',
  `eve_sindrome` int(1) DEFAULT NULL COMMENT 'Evento Sindrome gripal 1:marcado, 0:no marcado',
  `eve_centinela` int(1) DEFAULT NULL COMMENT 'Evento IRAG centinela 1:marcado, 0:no marcado',
  `eve_inusitado` int(1) DEFAULT NULL COMMENT 'Evento IRAG inusitado 1:marcado, 0:no marcado',
  `eve_imprevisto` int(1) DEFAULT NULL COMMENT 'Evento IRAG imprevisto 1:marcado, 0:no marcado',
  `eve_excesivo` int(1) DEFAULT NULL COMMENT 'Evento IRAG Numero excesivo 1:marcado, 0:no marcado',
  `eve_conglomerado` int(1) DEFAULT NULL COMMENT 'Evento IRAG Conglomerado 1:marcado, 0:no marcado',
  `eve_neumo_bacteriana` int(1) DEFAULT NULL COMMENT 'Evento Neumonia bacteriana 1:marcado, 0:no marcado',
  `fecha_inicio_sintoma` date DEFAULT NULL COMMENT 'Fecha de inicio de sintomas',
  `fecha_hospitalizacion` date DEFAULT NULL COMMENT 'Fecha de hospitalizacion',
  `fecha_notificacion` date DEFAULT NULL COMMENT 'Fecha de inicio de notificacion',
  `fecha_egreso` date DEFAULT NULL COMMENT 'Fecha de egreso del paciente',
  `fecha_defuncion` date DEFAULT NULL COMMENT 'Fecha de defuncion si el paciente muere',
  `antibiotico` int(1) DEFAULT NULL COMMENT 'uso de antibioticos en la ultima semana 1:si, 0:no, 2:desconocido',
  `antibiotico_cual` varchar(150) DEFAULT NULL COMMENT 'Nombre del antibiotico que uso en la ultima semana',
  `id_cat_antibiotico` int(11) DEFAULT NULL COMMENT 'Identificador del antibiotico',
  `antibiotico_fecha` date DEFAULT NULL COMMENT 'Fecha en que uso el antibiotico',
  `antiviral` int(1) DEFAULT NULL COMMENT 'uso antivirales en la ultima semana 1:si, 0:no, 2:desconocido',
  `antiviral_cual` varchar(150) DEFAULT NULL COMMENT 'Nombre del antiviral que uso en la ultima semana',
  `id_cat_antiviral` int(11) DEFAULT NULL COMMENT 'Identificador del antibiotico',
  `antiviral_fecha` date DEFAULT NULL COMMENT 'Fecha en que uso el antiviral',
  `sintoma_fiebre` int(1) DEFAULT NULL COMMENT 'Hallazgo clinico fiebre > 38 grados 1:si, 0:no, 2:desconocido',
  `sintoma_tos` int(1) DEFAULT NULL COMMENT 'Hallazgo clinico tos 1:si, 0:no, 2:desconocido',
  `sintoma_garganta` int(1) DEFAULT NULL COMMENT 'Hallazgo clinico garganta 1:si, 0:no, 2:desconocido',
  `sintoma_rinorrea` int(1) DEFAULT NULL COMMENT 'Hallazgo clinico rinorrea 1:si, 0:no, 2:desconocido',
  `sintoma_respiratoria` int(1) DEFAULT NULL COMMENT 'Hallazgo clinico respiratoria 1:si, 0:no, 2:desconocido',
  `sintoma_otro` int(1) DEFAULT NULL COMMENT 'Hallazgo clinico otro 1:si, 0:no, 2:desconocido',
  `sintoma_nombre_otro` varchar(150) DEFAULT NULL COMMENT 'Nombre del otro hallazgo clinico',
  `fecha_fiebre` date DEFAULT NULL COMMENT 'Fecha de inicio de sintoma de fiebre',
  `fecha_tos` date DEFAULT NULL COMMENT 'Fecha de inicio de sintoma de tos',
  `fecha_garganta` date DEFAULT NULL COMMENT 'Fecha de inicio de sintoma de dolor de garganta',
  `fecha_rinorrea` date DEFAULT NULL COMMENT 'Fecha de inicio de sintoma de rinorrea',
  `fecha_respiratoria` date DEFAULT NULL COMMENT 'Fecha de inicio de sintoma de dificultad respiratoria',
  `fecha_otro` date DEFAULT NULL COMMENT 'Fecha de inicio de otros sintomas',
  `torax_condensacion` int(1) DEFAULT NULL COMMENT 'Resultado de radiografia de torax - condensacion 1:si, 0:no, 2:desconocido',
  `torax_derrame` int(1) DEFAULT NULL COMMENT 'Resultado de radiografia de torax - condensacion 1:si, 0:no, 2:desconocido',
  `torax_broncograma` int(1) DEFAULT NULL COMMENT 'Resultado de radiografia de torax - condensacion 1:si, 0:no, 2:desconocido',
  `torax_infiltrado` int(1) DEFAULT NULL COMMENT 'Resultado de radiografia de torax - condensacion 1:si, 0:no, 2:desconocido',
  `torax_otro` int(1) DEFAULT NULL COMMENT 'Resultado de radiografia de torax - condensacion 1:si, 0:no, 2:desconocido',
  `torax_nombre_otro` varchar(150) DEFAULT NULL COMMENT 'nombre o descripcion de otro resultado de radiografia de torax',
  `semana_epi` int(3) DEFAULT NULL COMMENT 'Semana epidemiologica del reporte, basada en la fecha inicio de sintomas',
  `anio` int(4) DEFAULT NULL COMMENT 'anio del inicio de sintomas',
  `nombre_toma_muestra` varchar(150) DEFAULT NULL COMMENT 'Nombre la persona quien toma la muestra',
  `pendiente_uceti` int(1) NOT NULL DEFAULT '0' COMMENT 'Esta pendiente de llenado de datos de la ficha - 0:si, 1:no',
  `pendiente_silab` int(1) NOT NULL DEFAULT '0' COMMENT 'Esta pendiente de llenado de datos de silab - 0:si, 1:no',
  `actualizacion_silab` timestamp NULL DEFAULT NULL COMMENT 'Ultima actualizacion con silab',
  `source_entry` int(1) NOT NULL COMMENT 'Fuente de entrada de datos 0:web, 1:tablet',
  PRIMARY KEY (`id_flureg`),
  KEY `fk_flureg_form_cat_antibiotico` (`id_cat_antibiotico`),
  KEY `fk_flureg_form_cat_antiviral` (`id_cat_antiviral`),
  KEY `index_numero_identifica` (`numero_identificacion`),
  KEY `index_identificacion` (`tipo_identificacion`,`numero_identificacion`),
  KEY `index_evento` (`id_evento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla del formulario de influenza registro' AUTO_INCREMENT=3002658 ;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_muestra_count`
--

DROP TABLE IF EXISTS `flureg_muestra_count`;
CREATE TABLE IF NOT EXISTS `flureg_muestra_count` (
  `no_muestra` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`no_muestra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_muestra_laboratorio`
--

DROP TABLE IF EXISTS `flureg_muestra_laboratorio`;
CREATE TABLE IF NOT EXISTS `flureg_muestra_laboratorio` (
  `id_flureg_muestra_laboratorio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `id_flureg` int(11) NOT NULL COMMENT 'Identificador del formulario flu',
  `id_cat_muestra_laboratorio` int(11) NOT NULL COMMENT 'Identificador del antecedente vacunal',
  `fecha_toma` date DEFAULT NULL COMMENT 'Fecha toma',
  `fecha_envio` date DEFAULT NULL COMMENT 'Fecha envio',
  `fecha_recibo_laboratorio` date DEFAULT NULL COMMENT 'Fecha recibo laboratorio',
  PRIMARY KEY (`id_flureg_muestra_laboratorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre el flureg y las muestras de laborato' AUTO_INCREMENT=3003740 ;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_muestra_prueba_silab`
--

DROP TABLE IF EXISTS `flureg_muestra_prueba_silab`;
CREATE TABLE IF NOT EXISTS `flureg_muestra_prueba_silab` (
  `id_flureg_muestra_prueba_silab` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `id_muestra` int(11) NOT NULL COMMENT 'Identificador de la muestra',
  `id_flureg` int(11) NOT NULL COMMENT 'Identificador del formulario flu',
  `nombre_prueba` varchar(100) DEFAULT NULL COMMENT 'Nombre de la prueba',
  `resultado_prueba` varchar(45) DEFAULT NULL COMMENT 'Resultado de la prueba',
  `fecha_prueba` varchar(45) DEFAULT NULL COMMENT 'Fecha de la prueba',
  `presencia_prueba` int(1) DEFAULT NULL COMMENT 'Presencia de celulas 1:si, 2:no, 3:desconocido',
  `ag_prueba` int(1) DEFAULT NULL COMMENT 'Ag 1:si, 2:no, 3:desconocido',
  `if_prueba` int(1) DEFAULT NULL COMMENT 'If 1:si, 2:no, 3:desconocido',
  `Comentario_prueba` varchar(200) DEFAULT NULL COMMENT 'Comentarios de la prueba',
  PRIMARY KEY (`id_flureg_muestra_prueba_silab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre las muestras de laboratorio de silab' AUTO_INCREMENT=3003086 ;

-- --------------------------------------------------------

--
-- Table structure for table `flureg_muestra_silab`
--

DROP TABLE IF EXISTS `flureg_muestra_silab`;
CREATE TABLE IF NOT EXISTS `flureg_muestra_silab` (
  `id_flureg_muestra_silab` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `id_flureg` int(11) NOT NULL COMMENT 'Identificador del formulario flu',
  `id_muestra` int(11) NOT NULL COMMENT 'Identificador de la muestra',
  `local_remoto` int(1) NOT NULL DEFAULT '0' COMMENT 'Silab local o remoto - 1:Remoto, 0:Local, 2: Ingreso Manual',
  `codigo_global` varchar(45) DEFAULT NULL COMMENT 'Codigo Global de la muestra',
  `codigo_correlativo` varchar(45) DEFAULT NULL COMMENT 'Codigo Correlativo de la muestra',
  `tipo_muestra` varchar(45) DEFAULT NULL COMMENT 'Tipo de muestra',
  `fecha_inicio_sintoma` varchar(45) DEFAULT NULL COMMENT 'Fecha inicio de sintomas',
  `fecha_toma` varchar(45) DEFAULT NULL COMMENT 'Fecha toma',
  `fecha_recepcion` varchar(45) DEFAULT NULL COMMENT 'Fecha recepcion',
  `unidad_notificadora` varchar(45) DEFAULT NULL COMMENT 'Unidad notificadora',
  `estado_muestra` varchar(45) DEFAULT NULL COMMENT 'Estado Muestra',
  `resultado` varchar(45) DEFAULT NULL COMMENT 'Resultado Muestra',
  `tipo1` varchar(45) DEFAULT NULL COMMENT 'Tipo1 Muestra',
  `subtipo1` varchar(45) DEFAULT NULL COMMENT 'Subtipo1 Muestra',
  `tipo2` varchar(45) DEFAULT NULL COMMENT 'Tipo2 Muestra',
  `subtipo2` varchar(45) DEFAULT NULL COMMENT 'Subtipo2 Muestra',
  `tipo3` varchar(45) DEFAULT NULL COMMENT 'Tipo3 Muestra',
  `subtipo3` varchar(45) DEFAULT NULL COMMENT 'Subtipo3 Muestra',
  `comentario_resultado` varchar(45) DEFAULT NULL COMMENT 'Comentarios del resultado',
  `lab_proceso` varchar(150) DEFAULT NULL COMMENT 'Laboratorio que proceso la muestra tomada',
  PRIMARY KEY (`id_flureg_muestra_silab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre el flureg y las muestras de laborato' AUTO_INCREMENT=3002923 ;

-- --------------------------------------------------------

--
-- Table structure for table `mal_evento`
--

DROP TABLE IF EXISTS `mal_evento`;
CREATE TABLE IF NOT EXISTS `mal_evento` (
  `id_mal` int(11) NOT NULL COMMENT 'Identificador del formulario',
  `id_evento` int(11) NOT NULL COMMENT 'Identificador del evento de malformacion congenita',
  `estado` varchar(35) NOT NULL COMMENT 'Probable o confirmado',
  `descripcion` varchar(200) DEFAULT NULL COMMENT 'Descripcion de la malformacion',
  PRIMARY KEY (`id_mal`,`id_evento`),
  KEY `fk_mal_evento_evento` (`id_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla para crear la relación entre el formulario MAL y los E';

-- --------------------------------------------------------

--
-- Table structure for table `mal_form`
--

DROP TABLE IF EXISTS `mal_form`;
CREATE TABLE IF NOT EXISTS `mal_form` (
  `id_mal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `codigo_correlativo` varchar(20) NOT NULL COMMENT 'Codigo correlativo del formulario',
  `id_un` int(11) NOT NULL COMMENT 'Identificador de la instalacion de salud',
  `id_servicio` int(4) NOT NULL COMMENT 'Identificador del servicio y/o centro de produccion',
  `nombre_funcionario` varchar(150) DEFAULT NULL COMMENT 'Nombre del funcionario de registros y estadisticas',
  `id_cargo_funcionario` int(3) DEFAULT NULL COMMENT '1:medico, 2:enfermera, 3: aux de registro',
  `nombre_registra` varchar(60) NOT NULL COMMENT 'Nombre de la persona que llena el formulario',
  `institucion_registra` varchar(45) NOT NULL COMMENT 'institucion de la persona que llena el formulario',
  `fecha_reporte` date DEFAULT NULL COMMENT 'fecha en que llenan el formulario en papel',
  `fecha_formulario` date DEFAULT NULL COMMENT 'fecha en que llenan el formulario vía web',
  `tipo_identificacion` int(11) NOT NULL COMMENT 'Id del tipo de identificacion del paciente',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Numero de identificacion del paciente',
  `per_apellido_casada` varchar(100) DEFAULT NULL COMMENT 'Apellido de casada de la madre',
  `per_edad` int(11) NOT NULL COMMENT 'edad de la persona a la hora de llenar el formulario',
  `per_id_etnia` int(11) DEFAULT NULL COMMENT 'Identificador de la etnia',
  `per_id_estudio` int(11) DEFAULT NULL COMMENT 'Identificador del nivel de estudio de la persona',
  `per_anios_nivel` int(2) DEFAULT NULL COMMENT 'anios de estudios en el nivel mayor',
  `per_id_pais` int(11) NOT NULL COMMENT 'Identificador del pais donde vive el paciente',
  `per_id_corregimiento` int(11) DEFAULT NULL COMMENT 'Identificador del corregimiento, si el pais es diferente de panama no se pide',
  `per_dir_referencia` varchar(150) DEFAULT NULL COMMENT 'Referencia para localizar la casa',
  `per_dir_referencia_pais` varchar(150) DEFAULT NULL COMMENT 'Referencia para localizar la casa si vive en otro pais',
  `per_id_ocupacion_padre` int(11) DEFAULT NULL COMMENT 'Identificacion de la ocupacion del padre',
  `per_no_hay_dir` int(1) DEFAULT NULL COMMENT 'no se tienen satos de la direccion de la madre 1:si 0:no',
  `ant_acido_folico` int(1) DEFAULT NULL COMMENT 'Antecedentes de si tomo acido folico 1, 2 o 3',
  `ant_vitaminas` int(1) DEFAULT NULL COMMENT 'Antecedentes de si tomo vitaminas 1, 2 o 3',
  `ant_anomalias_madre` int(1) DEFAULT '0' COMMENT 'madre con anomalias 1:check, 0:No check',
  `ant_anomalias_padre` int(1) DEFAULT '0' COMMENT 'padre con anomalias 1:check, 0:No check',
  `ant_anomalias_hermanos` int(1) DEFAULT '0' COMMENT 'hermanos con anomalias 1:check, 0:No check',
  `ant_anomalias_abuelos` int(1) DEFAULT '0' COMMENT 'abuelos con anomalias 1:check, 0:No check',
  `ant_anomalias_tios` int(1) DEFAULT '0' COMMENT 'tios con anomalias 1:check, 0:No check',
  `ant_anomalias_medio_hermano` int(1) DEFAULT '0' COMMENT 'medio hermano con anomalias 1:check, 0:No check',
  `ant_anomalias_primo_hermano` int(1) DEFAULT '0' COMMENT 'primo hermano con anomalias 1:check, 0:No check',
  `ant_anomalias_ninguno` int(1) DEFAULT '0' COMMENT 'Ningun pariente con anomalias 1:chek 0: no check',
  `ant_consanguinidad` int(1) DEFAULT NULL COMMENT '1: si, 2: no',
  `ant_id_tipo_anomalia` int(1) DEFAULT NULL COMMENT '1: La misma, 2: Otra, 3: La misma y otra',
  `ant_otro_tipo_anomalia` varchar(250) DEFAULT NULL COMMENT 'Descripcion de otro tipo de anomalia',
  `ant_id_enfermedad_madre` int(11) DEFAULT NULL COMMENT 'Identificador de la enfermedad de la madre',
  `ant_otra_enfermedad_madre` varchar(100) DEFAULT NULL COMMENT 'Descripcion de otra enfermedad',
  `ant_id_infeccion_madre` int(11) DEFAULT NULL COMMENT 'Identificador de la infeccion madre',
  `ant_otra_infeccion_madre` varchar(100) DEFAULT NULL COMMENT 'Descripcion de otra infeccion de la madre',
  `ant_embarzos_multiples` int(1) DEFAULT NULL COMMENT '1: si, 2:no',
  `ant_id_embarazo_multiples` int(11) DEFAULT NULL COMMENT 'Identificador del embarazo multiple',
  `ant_abortos` int(11) DEFAULT NULL COMMENT 'Num. de antecedentes de abortos',
  `ant_tres_abortos` int(1) DEFAULT NULL COMMENT '3 abortos consecutivos 1:check, 0:No check',
  `ant_embarzos_previos` int(2) DEFAULT NULL COMMENT 'Num. de embarazos previos',
  `ant_anomalia_embarazos_previos` int(2) DEFAULT NULL COMMENT 'Num. de embarazos revios con anomalias',
  `ant_mortinatos` int(2) DEFAULT NULL COMMENT 'Num. de mortinatos previos',
  `ant_mes_fin_embarazo_anterior` int(2) DEFAULT NULL COMMENT 'mes del fin del embarazo anterior',
  `ant_anio_fin_embarazo_anterior` int(2) DEFAULT NULL COMMENT 'anio del fin del embarazo anterior',
  `ant_drogas_acido_valproico` int(1) DEFAULT NULL COMMENT 'consumio acido valproico en el embarazo 1: si, 2: no',
  `ant_drogas_alcohol` int(1) DEFAULT NULL COMMENT 'consumio alcohol en el embarazo 1: si, 2: no',
  `ant_drogas_carbamazepina` int(1) DEFAULT NULL COMMENT 'consumio Carbamazepina en el embarazo 1: si, 2: no',
  `ant_drogas_acido_retinoico` int(1) DEFAULT NULL COMMENT 'consumio acido retinoico en el embarazo 1: si, 2: no',
  `ant_drogas_fenitoina` int(1) DEFAULT NULL COMMENT 'consumio Fenitoina en el embarazo 1: si, 2: no',
  `ant_drogas_tabaquismo` int(1) DEFAULT NULL COMMENT 'Tabaquismo en el embarazo 1: si, 2: no',
  `ant_drogas_cocaina` int(1) DEFAULT NULL COMMENT 'consumio Cocaina en el embarazo 1: si, 2: no',
  `ant_drogas_aspirina` int(1) DEFAULT NULL COMMENT 'consumio aspirina en el embarazo 1: si, 2: no',
  `ant_drogas_ibuprofeno` int(1) DEFAULT NULL COMMENT 'consumio Ibuprofeno en el embarazo 1: si, 2: no',
  `ant_drogas_seudoefedrina` int(1) DEFAULT NULL COMMENT 'consumio seudoefedrina en el embarazo 1: si, 2: no',
  `ant_drogas_esteroides` int(1) DEFAULT NULL COMMENT 'consumio esteroides en el embarazo 1: si, 2: no',
  `ant_drogas_otra` int(1) DEFAULT NULL COMMENT 'consumio otras drogas 1:check, 0:No check',
  `ant_drogas_otra_descripcion` varchar(100) DEFAULT NULL COMMENT 'Nombre de la otra droga que consumio en el embarazo',
  `bebe_fecha_nacimiento` date NOT NULL COMMENT 'fecha de nacimiento del bebe con anomalias',
  `bebe_peso_nacimiento` int(11) DEFAULT NULL COMMENT 'Peso en gramos del bebe al nacer',
  `bebe_sexo` int(1) NOT NULL COMMENT '1:Hombre, 2:Mujer, 3:No determinado',
  `bebe_talla` int(11) DEFAULT NULL COMMENT 'Altura del bebe en cms al nacer',
  `bebe_cefalico` int(5) DEFAULT NULL COMMENT 'Perimetro cefalico del bebe al nacer',
  `bebe_rciu` int(1) DEFAULT NULL COMMENT 'retraso crecimiento intrauterino 1: si, 2: no',
  `bebe_edad_gestacional` int(3) DEFAULT NULL COMMENT 'Edad gestacional en semanas del bebe al nacer',
  `bebe_id_lugar_nacimiento` int(1) DEFAULT NULL COMMENT 'Identificador del lugar de nacimiento del bebe',
  `bebe_id_condicion_nacimiento` int(1) DEFAULT NULL COMMENT 'Identificador de la condicion de nacimiento',
  `bebe_emb_actual_multiple` int(1) DEFAULT NULL COMMENT 'Embarazo actual es multiple 1: si, 2:no',
  `bebe_cant_defecto` int(11) DEFAULT NULL,
  `bebe_vivo_una_semana` int(1) DEFAULT NULL COMMENT 'Sobrevivio una semana 1: si, 2:no',
  `bebe_egreso_vivo` int(1) DEFAULT NULL COMMENT 'Egreso vivo el bebe 1:check, 0:No check',
  `bebe_fecha_muerte` date DEFAULT NULL COMMENT 'Fecha de muerte del bebe',
  `bebe_descripcion_general` varchar(1000) DEFAULT NULL COMMENT 'Comentario generales del formulario',
  `bebe_otro_nacimiento` varchar(200) DEFAULT NULL COMMENT 'Descripcion del otro lugar de nacimiento del bebe ',
  `per_vivir_ultimo` int(1) DEFAULT NULL COMMENT 'vivio en el lugar el ultimo anio 1:si 2:no',
  `per_fecha_empezo_vivir` date DEFAULT NULL COMMENT 'Fecha aprox. en la que empezo a residir en la direccion ',
  `bebe_aislado` int(1) DEFAULT NULL COMMENT 'aislado 1:si, 2:no',
  `bebe_sindrome` varchar(150) DEFAULT NULL COMMENT 'Sindrome del bebe',
  PRIMARY KEY (`id_mal`),
  UNIQUE KEY `mal_codigo` (`codigo_correlativo`),
  KEY `fk_mal_persona` (`tipo_identificacion`,`numero_identificacion`),
  KEY `fk_mal_un` (`id_un`),
  KEY `fk_mal_servicio` (`id_servicio`),
  KEY `fk_mal_persona_pais` (`per_id_pais`),
  KEY `fk_mal_persona_corr` (`per_id_corregimiento`),
  KEY `fk_mal_etnia` (`per_id_etnia`),
  KEY `fk_mal_estudio` (`per_id_estudio`),
  KEY `fk_mal_enfermedad_madre` (`ant_id_enfermedad_madre`),
  KEY `fk_mal_infeccion_madre` (`ant_id_infeccion_madre`),
  KEY `fk_mal_embarazo_multiple` (`ant_id_embarazo_multiples`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla para el formulario de malformacion congenita' AUTO_INCREMENT=555 ;

-- --------------------------------------------------------

--
-- Table structure for table `mal_nacidos`
--

DROP TABLE IF EXISTS `mal_nacidos`;
CREATE TABLE IF NOT EXISTS `mal_nacidos` (
  `id_mal_nacidos` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del registro de nacidos vivos y muertos',
  `id_un` int(11) NOT NULL COMMENT 'Código de unidad notificadora',
  `mes` int(2) NOT NULL COMMENT 'mes del año en que se guarda el dato',
  `anio` int(4) NOT NULL,
  `nacidos_vivos` int(4) NOT NULL DEFAULT '0' COMMENT 'Numero de ninos nacidos vivos en el mes',
  `nacidos_muertos` int(4) NOT NULL DEFAULT '0' COMMENT 'Numero de ninos nacidos muertos en el mes',
  `nombre_registra` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `institucion_registra` varchar(45) DEFAULT NULL COMMENT 'Institucion a la pertenece la persona que registra',
  PRIMARY KEY (`id_mal_nacidos`),
  UNIQUE KEY `uk_encabezado` (`id_un`,`mes`,`anio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con los datos del formulario de nacidos para malformac' AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `mat_contacto`
--

DROP TABLE IF EXISTS `mat_contacto`;
CREATE TABLE IF NOT EXISTS `mat_contacto` (
  `id_contacto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del contacto',
  `nombres` varchar(60) DEFAULT NULL COMMENT 'Nombres del contacto',
  `apellidos` varchar(60) DEFAULT NULL COMMENT 'Apellidos del contacto',
  `email` varchar(60) NOT NULL COMMENT 'Email del contacto',
  `telefono` varchar(15) DEFAULT NULL COMMENT 'Telefono del contacto',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'Status del contacto, 1 = Habilitado y 0 = inhabilitado',
  `sexo` int(1) DEFAULT '1' COMMENT 'sexo del contacto 1:hombre 2:mujer',
  PRIMARY KEY (`id_contacto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Datos generales del contacto para el MAT' AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `mat_contacto_grupo_contacto`
--

DROP TABLE IF EXISTS `mat_contacto_grupo_contacto`;
CREATE TABLE IF NOT EXISTS `mat_contacto_grupo_contacto` (
  `id_contacto` int(11) NOT NULL COMMENT 'Identificador unico del contacto',
  `id_grupo_contacto` int(11) NOT NULL COMMENT 'Identificador del grupo',
  PRIMARY KEY (`id_contacto`,`id_grupo_contacto`),
  KEY `fk_mat_rel_grupo_contacto` (`id_grupo_contacto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion contacto con grupo contacto para el MAT';

-- --------------------------------------------------------

--
-- Table structure for table `mat_escenario`
--

DROP TABLE IF EXISTS `mat_escenario`;
CREATE TABLE IF NOT EXISTS `mat_escenario` (
  `id_escenario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del escenario del modulo de alerta temprana - mat',
  `id_n1` int(11) NOT NULL COMMENT 'Identificador del nivel 1 de los Eventos',
  `nivel_geo` int(1) NOT NULL COMMENT 'Nivel geo 1:nacional 2:provincia 3:distrito 4:corregimiento 5:unidad Notificadora',
  `id_nivel_geo` int(11) DEFAULT NULL COMMENT 'Identificador del nivel geografico',
  `tipo_algoritmo` int(1) NOT NULL COMMENT '1: individual, 2: canal endemico',
  `tipo_alerta` int(1) DEFAULT NULL COMMENT '1: inmediato, 2: semanal',
  `dia_alerta` int(1) DEFAULT NULL COMMENT 'Dia habil para enviar la alerta 1: lunes, 2: martes, 3: miercoles, 4: jueves, 5: viernes',
  `mensaje` text COMMENT 'Mensaje personalizado del correo electronico',
  `nombre_crear` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que crea el escenario',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1: habilitado, o: inhabilitado',
  `fecha_crear` date DEFAULT NULL COMMENT 'Fecha en que se crea el escenario',
  `semana_lanzada` int(2) NOT NULL DEFAULT '0' COMMENT 'Es el numero de la ultima semana en que se envio la alerta, si es 0 nunca se ha lanzado',
  PRIMARY KEY (`id_escenario`),
  KEY `fk_mat_escenario_evento` (`id_n1`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla principal de los escenarios para MAT' AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `mat_escenario_grupo_contacto`
--

DROP TABLE IF EXISTS `mat_escenario_grupo_contacto`;
CREATE TABLE IF NOT EXISTS `mat_escenario_grupo_contacto` (
  `id_escenario` int(11) NOT NULL COMMENT 'Identificador del escenario del modulo de alerta temprana - mat',
  `id_grupo_contacto` int(11) NOT NULL COMMENT 'Identificador del grupo',
  PRIMARY KEY (`id_escenario`,`id_grupo_contacto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion contacto con grupo contacto para el MAT';

-- --------------------------------------------------------

--
-- Table structure for table `mat_grupo_contacto`
--

DROP TABLE IF EXISTS `mat_grupo_contacto`;
CREATE TABLE IF NOT EXISTS `mat_grupo_contacto` (
  `id_grupo_contacto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del grupo',
  `nombre_grupo_contacto` varchar(60) NOT NULL COMMENT 'Nombre del grupo de contacto',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'Estado del grupo de contacto 1:habilitado 0:inhabilitado',
  PRIMARY KEY (`id_grupo_contacto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Grupos de contactos para el MAT' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `mat_poblacion`
--

DROP TABLE IF EXISTS `mat_poblacion`;
CREATE TABLE IF NOT EXISTS `mat_poblacion` (
  `id_poblacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `anio` int(4) NOT NULL COMMENT 'Identificador del formulario',
  `num_poblacion` int(10) NOT NULL COMMENT 'Identificador del formulario VIH',
  `id_corregimiento` int(11) NOT NULL COMMENT 'Identificador del corregimiento al que pertenece la poblacion',
  PRIMARY KEY (`id_poblacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla con la poblacion de Panama por corregimiento' AUTO_INCREMENT=632 ;

-- --------------------------------------------------------

--
-- Table structure for table `notic_form`
--

DROP TABLE IF EXISTS `notic_form`;
CREATE TABLE IF NOT EXISTS `notic_form` (
  `id_notic` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario, autoincrementado',
  `per_asegurado` int(1) NOT NULL COMMENT '1:asegurado, 2:no asegurado',
  `tipo_identificacion` int(11) NOT NULL COMMENT 'Identificador del tipo de identidad del paciente',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Numero de identificacion del paciente',
  `per_edad` int(11) NOT NULL COMMENT 'edad de la persona a la hora de llenar el formulario',
  `per_tipo_edad` int(11) NOT NULL COMMENT 'tipo edad de la persona a la hora de llenar el formulario',
  `per_id_pais` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais de residencia de la persona',
  `per_id_corregimiento` int(11) DEFAULT NULL COMMENT 'Identificador del corregimiento de la persona a la hora de llenar el formulario',
  `per_direccion` varchar(100) DEFAULT NULL COMMENT 'direccion de la persona al momento de llenar el formulario',
  `per_dir_referencia` varchar(150) DEFAULT NULL COMMENT 'Referencia para localizar la casa',
  `per_contagio` int(1) NOT NULL COMMENT 'Lugar de contagio 1:casa, 2:Trabajo, 3:otro',
  `per_nombre_contagio` varchar(150) DEFAULT NULL COMMENT 'Nombre del lugar de contagio',
  `id_pais_contagio` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del país donde se presume se produjo el contagio',
  `id_corregimiento_contagio` int(11) NOT NULL COMMENT 'Corregimiento en donde se presume se produjo el contagio',
  `punto_referencia_contagio` varchar(250) DEFAULT NULL COMMENT 'Punto de referencia del contagio',
  `dir_descripcion_contagio` varchar(250) DEFAULT NULL COMMENT 'Si el contagio es un otro pais diferente a panama se activa este campo',
  `id_diagnostico1` int(11) NOT NULL COMMENT 'Diagnóstico de la primera casua de muerte de la persona fallecida',
  `estado_diag1` int(1) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `id_diagnostico2` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la segunda casua de muerte de la persona fallecida',
  `estado_diag2` int(1) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `id_diagnostico3` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la tercera casua de muerte de la persona fallecida',
  `estado_diag3` int(1) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `condicion` int(1) DEFAULT NULL COMMENT 'Condicion del paciente 1:leve, 2:moderada, 3:grave',
  `fecha_inicio_sintomas` date NOT NULL COMMENT 'Fecha aproximada del inicio de los sintomas',
  `semana_epi` int(3) NOT NULL COMMENT 'Semana epidemiologica del reporte, basada en la fecha inicio de sintomas',
  `anio` int(4) NOT NULL COMMENT 'anio del inicio de sintomas',
  `fecha_hospitalizacion` date DEFAULT NULL COMMENT 'Fecha de hospitalización de la persona',
  `fecha_defuncion` date DEFAULT NULL COMMENT 'Fecha defunción de la persona fallecida',
  `fecha_toma_muestra` date DEFAULT NULL COMMENT 'Fecha de cuando se toma la muestra',
  `id_tipo_muestra` int(4) DEFAULT NULL COMMENT 'identificador del tipo de muestra',
  `tipo_caso` int(1) DEFAULT NULL COMMENT '1:sospechoso, 2:probable, 3: confirmado',
  `criterio_caso_confirmado` int(1) DEFAULT NULL COMMENT '1:clinico, 2:lab, 3: nexo, 4:no confirmado',
  `id_un` int(11) NOT NULL COMMENT 'Identificador de la unidad que reporta el caso',
  `id_servicio` int(4) DEFAULT NULL COMMENT 'identificador del servicio',
  `nombre_reporta` varchar(45) NOT NULL COMMENT 'Nombre de la persona que reporta ',
  `id_cargo` int(11) NOT NULL COMMENT 'identificador del Cargo de la persona que reporta',
  `nombre_registra` varchar(45) NOT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `institucion_registra` varchar(45) NOT NULL COMMENT 'Institucion a la pertenece la persona que registra',
  `fecha_formulario` date NOT NULL COMMENT 'Fecha de cuando se creo el formulario',
  `telefono` varchar(20) DEFAULT NULL COMMENT 'Telefono de quien reporta',
  `fecha_notificacion` date NOT NULL COMMENT 'Fecha de notificacion',
  `fecha_regional` date DEFAULT NULL COMMENT 'Fecha de recibido a nivel regional',
  `comentario` varchar(1000) DEFAULT NULL COMMENT 'campo para los comentarios extra, campo abierto',
  `hora_formulario` time DEFAULT NULL COMMENT 'Hora exacta en que se llena el formulario',
  PRIMARY KEY (`id_notic`),
  UNIQUE KEY `validacion_formulario` (`tipo_identificacion`,`numero_identificacion`,`id_diagnostico1`,`semana_epi`,`anio`),
  KEY `fk_notic_un` (`id_un`),
  KEY `fk_notic_servicio` (`id_servicio`),
  KEY `fk_notic_persona_pais` (`per_id_pais`),
  KEY `fk_notic_persona_corr` (`per_id_corregimiento`),
  KEY `fk_notic_evento` (`id_diagnostico1`),
  KEY `index_evento2` (`id_diagnostico2`),
  KEY `index_evento3` (`id_diagnostico3`),
  KEY `index_numero_identifica` (`numero_identificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla del formulario notificacion individual - NOTIC' AUTO_INCREMENT=43551 ;

-- --------------------------------------------------------

--
-- Table structure for table `notic_sintoma`
--

DROP TABLE IF EXISTS `notic_sintoma`;
CREATE TABLE IF NOT EXISTS `notic_sintoma` (
  `id_notic` int(11) NOT NULL COMMENT 'Identificador del formulario',
  `id_sintoma` int(11) NOT NULL COMMENT 'Identificador del sintoma',
  `fecha_sintoma` date NOT NULL COMMENT 'Fecha del sintoma o signo',
  PRIMARY KEY (`id_notic`,`id_sintoma`),
  KEY `fk_rel_sintoma` (`id_sintoma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de relación entre el NOTIC y los sintomas y signos';

-- --------------------------------------------------------

--
-- Table structure for table `rae_egreso`
--

DROP TABLE IF EXISTS `rae_egreso`;
CREATE TABLE IF NOT EXISTS `rae_egreso` (
  `id_rae` int(11) NOT NULL COMMENT 'Identificador del formulario',
  `id_evento` int(4) NOT NULL COMMENT 'Identificador del servicio y/o centro de produccion',
  `nuevo_subsecuente` varchar(1) NOT NULL COMMENT '1. nuevo, 2. subsecuente',
  `nosocomial` varchar(1) NOT NULL COMMENT '1. si, 2. no',
  `id_evento_externo` int(11) DEFAULT NULL COMMENT 'Identificador del evento de causa externa',
  PRIMARY KEY (`id_rae`,`id_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla para crear la relación entre el formulario RAE y los d';

-- --------------------------------------------------------

--
-- Table structure for table `rae_form`
--

DROP TABLE IF EXISTS `rae_form`;
CREATE TABLE IF NOT EXISTS `rae_form` (
  `id_rae` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `id_un` int(11) NOT NULL COMMENT 'Identificador de la instalacion de salud',
  `referido_de` int(1) NOT NULL COMMENT 'Referido de: 1. Consulta ext, 2. Urgencia, 3. otra institucion ',
  `referido_otro_id_un` int(11) DEFAULT NULL COMMENT 'Si la opcion referido es otro, es el id de la otra instalación',
  `id_servicio` int(4) NOT NULL COMMENT 'Identificador del servicio y/o centro de produccion',
  `id_personal_medico` int(11) DEFAULT NULL COMMENT 'Identificador del personal medico que atendio el caso',
  `nombre_funcionario` varchar(150) DEFAULT NULL COMMENT 'Nombre del funcionario de registros y estadisticas',
  `nombre_registra` varchar(60) NOT NULL COMMENT 'Nombre de la persona que llena el formulario',
  `institucion_registra` varchar(45) NOT NULL COMMENT 'institucion de la persona que llena el formulario',
  `fecha_cierre` date DEFAULT NULL COMMENT 'fecha de cierre del egreso',
  `fecha_admision` date DEFAULT NULL COMMENT 'fecha de admisión del paciente',
  `fecha_egreso` date DEFAULT NULL COMMENT 'fecha del egreso del paciente',
  `tipo_identificacion` int(11) NOT NULL COMMENT 'Id del tipo de identificacion del paciente',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Numero de identificacion del paciente',
  `per_edad` int(11) NOT NULL COMMENT 'edad de la persona a la hora de llenar el formulario',
  `per_tipo_edad` int(11) NOT NULL COMMENT 'tipo edad de la persona a la hora de llenar el formulario',
  `per_id_pais` int(11) NOT NULL COMMENT 'Identificador del pais donde vive el paciente',
  `per_id_corregimiento` int(11) DEFAULT NULL COMMENT 'Identificador del corregimiento, si el pais es diferente de panama no se pide',
  `per_direccion` varchar(100) DEFAULT NULL COMMENT 'direccion de la persona al momento de llenar el formulario',
  `per_dir_referencia` varchar(150) DEFAULT NULL COMMENT 'Referencia para localizar la casa',
  `per_id_corregimiento_transitoria` int(11) DEFAULT NULL COMMENT 'identificador de la localizacion de la persona',
  `per_no_hay_dir_transitoria` int(11) DEFAULT '0' COMMENT '1: check 0:No check',
  `id_tipo_paciente` int(11) NOT NULL COMMENT 'identificador del tipo de paciente',
  `id_diagnostico1` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la primera casua de muerte de la persona fallecida',
  `estado_diag1` int(1) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `id_diagnostico2` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la segunda casua de muerte de la persona fallecida',
  `estado_diag2` int(1) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `id_diagnostico3` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la tercera casua de muerte de la persona fallecida',
  `estado_diag3` int(1) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `hospitalizacion` int(1) NOT NULL DEFAULT '1' COMMENT '1:nueva o 2:subsecuente',
  `id_condicion_salida` int(4) DEFAULT NULL COMMENT 'Identificador de la condicion de salida',
  `motivo_salida` int(1) DEFAULT NULL COMMENT '1. autorizada, 2. no autorizada',
  `muerte_sop` int(1) DEFAULT NULL COMMENT '1.si, 2.no',
  `autopsia` int(1) DEFAULT NULL COMMENT 'si la muerte es si, la autopsia es obligatoria 1. si, 2.no',
  `fecha_autopsia` date DEFAULT NULL COMMENT 'si la autopsia es si, la fecha es obligatoria',
  `referido_a` int(1) DEFAULT NULL COMMENT '1. consulta ext, 2. hosp. minsa, 3. caja SS, 4. centro de salud, 5. sub centro, 6. otro',
  `referido_a_otro` varchar(60) DEFAULT NULL COMMENT 'si la variable anterior es otro, aqui se especifica',
  PRIMARY KEY (`id_rae`),
  KEY `fk_rae_condicion_salida` (`id_condicion_salida`),
  KEY `fk_rae_evento` (`id_diagnostico1`),
  KEY `fk_rae_persona` (`tipo_identificacion`,`numero_identificacion`),
  KEY `fk_rae_personal_medico` (`id_personal_medico`),
  KEY `fk_rae_persona_corr` (`per_id_corregimiento`),
  KEY `fk_rae_persona_corr_transitoria` (`per_id_corregimiento_transitoria`),
  KEY `fk_rae_persona_pais` (`per_id_pais`),
  KEY `fk_rae_servicio` (`id_servicio`),
  KEY `fk_rae_tipo_paciente` (`id_tipo_paciente`),
  KEY `fk_rae_un` (`id_un`),
  KEY `index_numero_identifica` (`numero_identificacion`),
  KEY `index_evento2` (`id_diagnostico2`),
  KEY `index_evento3` (`id_diagnostico3`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla para el formulario registro medico y estadistico de ad' AUTO_INCREMENT=87761 ;

--
-- Triggers `rae_form`
--
DROP TRIGGER IF EXISTS `auditoria_rae`;
DELIMITER //
CREATE TRIGGER `auditoria_rae` AFTER INSERT ON `rae_form`
 FOR EACH ROW insert into auditoria_rae(id_rae,usuario,id_un,fecha)
values(new.id_rae,new.nombre_registra,new.id_un,now())
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rae_movimiento`
--

DROP TABLE IF EXISTS `rae_movimiento`;
CREATE TABLE IF NOT EXISTS `rae_movimiento` (
  `id_rae` int(11) NOT NULL COMMENT 'Identificador del formulario',
  `id_servicio` int(4) NOT NULL COMMENT 'Identificador del servicio y/o centro de produccion',
  `codigo_admision` varchar(11) DEFAULT NULL COMMENT 'codigo unico de la admision',
  `fecha_admision` date NOT NULL COMMENT 'fecha de la admision del paciente en ese servicio',
  `hora_admision` varchar(5) DEFAULT NULL COMMENT 'Hora de admision del paciente en ese servicio',
  `hora_admision_am_pm` varchar(1) DEFAULT NULL COMMENT '1. am, 2. pm',
  `fecha_egreso` date NOT NULL COMMENT 'fecha de la egreso del paciente en ese servicio',
  `hora_egreso` varchar(5) DEFAULT NULL COMMENT 'Hora de egreso del paciente en ese servicio',
  `hora_egreso_am_pm` varchar(1) DEFAULT NULL COMMENT '1. am, 2. pm',
  `dias_estancia` int(4) DEFAULT NULL COMMENT 'Diferencia de días entre la fecha de admision y la de traslado o egreso',
  PRIMARY KEY (`id_rae`,`id_servicio`,`fecha_admision`),
  KEY `fk_rae_movimiento_servicio` (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla para crear la relación entre el formulario RAE y los p';

-- --------------------------------------------------------

--
-- Table structure for table `rae_procedimiento`
--

DROP TABLE IF EXISTS `rae_procedimiento`;
CREATE TABLE IF NOT EXISTS `rae_procedimiento` (
  `id_rae` int(11) NOT NULL COMMENT 'Identificador del formulario',
  `id_procedimiento` int(4) NOT NULL COMMENT 'Identificador del procedimiento',
  `codigo_procedimiento` varchar(11) DEFAULT NULL COMMENT 'codigo unico del procedimiento',
  `tipo_procedimiento` int(1) NOT NULL COMMENT '1.Quirurgico, 2. Medico, 3.Tratamiento',
  PRIMARY KEY (`id_rae`,`id_procedimiento`),
  KEY `fk_rae_procedimiento_procedimiento` (`id_procedimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla para crear la relación entre el formulario RAE y los p';

-- --------------------------------------------------------

--
-- Table structure for table `rel_examen_tipo_muestra`
--

DROP TABLE IF EXISTS `rel_examen_tipo_muestra`;
CREATE TABLE IF NOT EXISTS `rel_examen_tipo_muestra` (
  `id_examen` int(11) NOT NULL COMMENT 'Identificador del examen',
  `id_tipo_muestra` int(11) NOT NULL COMMENT 'Identificador del tipo de muestra',
  PRIMARY KEY (`id_examen`,`id_tipo_muestra`),
  KEY `fk_rel_tipo_muestra` (`id_tipo_muestra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla para crear la relación entre examenes y tipo de muestr';

-- --------------------------------------------------------

--
-- Table structure for table `semana_epi`
--

DROP TABLE IF EXISTS `semana_epi`;
CREATE TABLE IF NOT EXISTS `semana_epi` (
  `semana` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  PRIMARY KEY (`semana`,`anio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_control`
--

DROP TABLE IF EXISTS `tb_control`;
CREATE TABLE IF NOT EXISTS `tb_control` (
  `id_tb_control` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tb_form` int(10) unsigned NOT NULL,
  `fecha_control` date NOT NULL,
  `peso` int(10) unsigned DEFAULT NULL,
  `no_dosis_control` int(10) unsigned DEFAULT NULL,
  `fecha_BK_control` date DEFAULT NULL,
  `res_BK_control` int(1) unsigned DEFAULT NULL,
  `id_clasificacion_BK` int(10) unsigned DEFAULT NULL,
  `fecha_cultivo_control` date DEFAULT NULL,
  `res_cultivo_control` int(1) unsigned DEFAULT NULL,
  `control_H` int(1) unsigned DEFAULT NULL,
  `control_R` int(1) unsigned DEFAULT NULL,
  `control_Z` int(1) unsigned DEFAULT NULL,
  `control_E` int(1) unsigned DEFAULT NULL,
  `control_S` int(1) unsigned DEFAULT NULL,
  `control_Otros` int(11) DEFAULT NULL,
  `fluoroquinolonas` int(1) unsigned DEFAULT NULL,
  `inyec_2_linea` int(1) unsigned DEFAULT NULL,
  `reac_adv` int(1) unsigned DEFAULT NULL,
  `fecha_reac_adv` date DEFAULT NULL,
  `id_clasi_reac_adv` int(10) unsigned DEFAULT NULL,
  `id_conducta` int(10) unsigned DEFAULT NULL,
  `hospitalizado` int(1) unsigned DEFAULT NULL,
  `preso` int(1) unsigned DEFAULT NULL,
  `fecha_preso` date DEFAULT NULL,
  `usr_drogas` int(1) unsigned DEFAULT NULL,
  `alcoholismo` int(1) unsigned DEFAULT NULL,
  `tabaquismo` int(1) unsigned DEFAULT NULL,
  `mineria` int(1) unsigned DEFAULT NULL,
  `hacinamiento` int(1) unsigned DEFAULT NULL,
  `empleado` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_tb_control`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2398 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_form`
--

DROP TABLE IF EXISTS `tb_form`;
CREATE TABLE IF NOT EXISTS `tb_form` (
  `id_tb` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario, autoincrementado',
  `id_corregimiento` int(11) DEFAULT NULL COMMENT 'Identificador del corregimiento de la persona a la hora de llenar el formulario',
  `id_un` int(11) DEFAULT NULL COMMENT 'Identificador de la unidad que reporta el caso',
  `unidad_disponible` int(11) DEFAULT NULL COMMENT 'Unidad notificadora disponible 1:si 0:no',
  `nombre_investigador` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que investiga',
  `fecha_formulario` date DEFAULT NULL COMMENT 'Fecha de cuando se creo el formulario',
  `fecha_notificacion` date DEFAULT NULL COMMENT 'Fecha de notificación del caso',
  `nombre_registra` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `per_asegurado` int(1) DEFAULT NULL COMMENT '1:asegurado, 0:no asegurado',
  `tipo_identificacion` int(11) DEFAULT NULL COMMENT 'Identificador del tipo de identidad del paciente',
  `numero_identificacion` varchar(30) DEFAULT NULL COMMENT 'Numero de identificacion del paciente',
  `per_edad` int(11) DEFAULT NULL COMMENT 'edad de la persona a la hora de llenar el formulario',
  `per_tipo_edad` int(11) DEFAULT NULL COMMENT 'tipo edad de la persona a la hora de llenar el formulario',
  `id_pais` int(11) DEFAULT '174',
  `riesgo_embarazo` int(1) DEFAULT NULL COMMENT 'Esta en embarazo 1:si, 0:no, 2:desconocido',
  `riesgo_semana` int(1) DEFAULT NULL COMMENT 'Semana gestacional si esta embarazada hasta 42',
  `per_empleado` int(1) DEFAULT NULL COMMENT 'Si la persona se encuentra empleado 1:Si, 0:No',
  `id_profesion` int(11) DEFAULT NULL COMMENT 'Tipo de profesion del paciente al momento de llenar el formulario',
  `otrosprofesion` varchar(100) DEFAULT NULL COMMENT 'Otra ocupación',
  `per_direccion` varchar(100) DEFAULT NULL COMMENT 'direccion de la persona al momento de llenar el formulario',
  `per_direccion_otra` varchar(150) DEFAULT NULL COMMENT 'Referencia para localizar la casa',
  `per_telefono` int(20) DEFAULT NULL COMMENT 'Telefono de quien reporta',
  `per_nombre_referencia` varchar(150) DEFAULT NULL COMMENT 'Nombre de la persona de referencia',
  `per_parentesco` varchar(75) DEFAULT NULL COMMENT 'Parentesco de la persona de referencia',
  `per_telefono_referencia` int(20) DEFAULT NULL COMMENT 'telefono de la persona de referencia',
  `ant_diabetes` int(1) DEFAULT NULL COMMENT 'Antecedente diabetico 1:si, 0:no, 2:No sabe',
  `ant_preso` int(1) DEFAULT NULL COMMENT 'Persona privada de libertad 1:si, 0:no, 2:No sabe',
  `ant_fecha_preso` date DEFAULT NULL COMMENT 'Fecha de privación de libertad',
  `ant_tiempo_preso` int(11) DEFAULT NULL,
  `ant_drug` int(1) DEFAULT NULL COMMENT 'Usuario de droga 1:si, 0:no, 2:No sabe',
  `ant_alcoholism` int(1) DEFAULT NULL COMMENT 'Alcoholismo 1:si, 0:no, 2:No sabe',
  `ant_smoking` int(1) DEFAULT NULL COMMENT 'Tabaquismo 1:si, 0:no, 2:No sabe',
  `ant_mining` int(1) DEFAULT NULL COMMENT 'Mineria 1:si, 0:no, 2:No sabe',
  `ant_overcrowding` int(1) DEFAULT NULL COMMENT 'Hacimiento 1:si, 0:no, 2:No sabe',
  `ant_indigence` int(1) DEFAULT NULL COMMENT 'Indigencia 1:si, 0:no, 2:No sabe',
  `ant_drinkable` int(1) DEFAULT NULL COMMENT 'Acceso a agua potable 1:si, 0:no, 2:No sabe',
  `ant_sanitation` int(1) DEFAULT NULL COMMENT 'Acceso a saneamiento basico 1:si, 0:no, 2:No sabe',
  `ant_contactposi` int(1) DEFAULT NULL COMMENT 'Contacto de caso positivo 1:si, 0:no, 2:No sabe',
  `ant_BCG` int(1) DEFAULT NULL COMMENT 'Cicatriz de BCG 1:si, 0:no, 2:No sabe',
  `ant_weight` int(11) DEFAULT NULL COMMENT 'Peso en kilogramos, tiene que ser entre 0 - 200',
  `ant_height` double DEFAULT NULL COMMENT 'Talla en metros, tiene que ser entre 0 - 2.3',
  `mat_diag_fecha_BK1` date DEFAULT NULL COMMENT 'Fecha de Metodo de diagnostico BK1',
  `mat_diag_resultado_BK1` int(1) DEFAULT NULL COMMENT 'Resultado de Metodo de diagnostico BK1 1:si, 0:no',
  `id_clasificacion_BK1` int(11) DEFAULT NULL COMMENT 'Clasificación del resultado de metodo de diagnostico BK1',
  `mat_diag_fecha_BK2` date DEFAULT NULL COMMENT 'Fecha de Metodo de diagnostico BK1',
  `mat_diag_resultado_BK2` int(1) DEFAULT NULL COMMENT 'Resultado de Metodo de diagnostico BK1 1:si, 0:no',
  `id_clasificacion_BK2` int(11) DEFAULT NULL COMMENT 'Clasificación del resultado de metodo de diagnostico BK1',
  `mat_diag_fecha_BK3` date DEFAULT NULL COMMENT 'Fecha de Metodo de diagnostico BK1',
  `mat_diag_resultado_BK3` int(1) DEFAULT NULL COMMENT 'Resultado de Metodo de diagnostico BK1 1:si, 0:no',
  `id_clasificacion_BK3` int(11) DEFAULT NULL COMMENT 'Clasificación del resultado de metodo de diagnostico BK1',
  `mat_diag_res_cultivo` int(1) DEFAULT NULL COMMENT 'Resultado de cultivo de metodo de diagnostico',
  `mat_diag_fecha_res_cultivo` date DEFAULT NULL COMMENT 'Fecha de resultado de cultivo',
  `mat_diag_metodo_WRD` int(1) DEFAULT NULL COMMENT 'Otro método de diagnostico WRD',
  `mat_diag_res_metodo_WRD` int(1) DEFAULT NULL COMMENT 'Resultado de otro metodo de diagnostico WRD',
  `mat_diag_fecha_res_WRD` date DEFAULT NULL COMMENT 'Fecha de resultado de otro metodo de diagnostico WRD',
  `mat_diag_res_clinico` int(1) DEFAULT NULL COMMENT 'Resultado clinico',
  `mat_diag_fecha_clinico` date DEFAULT NULL COMMENT 'Fecha de resultado clinico',
  `mat_diag_res_R_X` int(1) DEFAULT NULL COMMENT 'Resultado de R-X',
  `mat_diag_fecha_R_X` date DEFAULT NULL COMMENT 'Fecha de resultado R-X',
  `mat_diag_res_histopa` int(1) DEFAULT NULL COMMENT 'Resultado de histopatologia',
  `mat_diag_fecha_histopa` date DEFAULT NULL COMMENT 'Fecha de resultado de otro metodo de diagnostico',
  `clasificacion_tb` int(11) DEFAULT NULL COMMENT 'bacteriologicamente= 0, clinicamente=1 ALGORITMO: TB bacteriológicamente confirmada = Cualquier baciloscopía ó cultivo ó prueba rápida con resultado positvo. TB clínicamente diagnosticada = No es TB bacteriológicamente confirmada y (clínica ó R-X ó histop',
  `clas_pulmonar_EP` int(11) DEFAULT NULL,
  `clas_lugar_EP` int(11) DEFAULT NULL,
  `clas_trat_previo` int(11) DEFAULT NULL,
  `clas_recaida` int(11) DEFAULT NULL,
  `clas_postfracaso` int(11) DEFAULT NULL,
  `clas_perdsegui` int(11) DEFAULT NULL,
  `clas_otros_antestratado` int(11) DEFAULT NULL,
  `clas_diag_VIH` int(11) DEFAULT NULL,
  `clas_fecha_diag_VIH` date DEFAULT NULL,
  `clas_met_diag` int(11) DEFAULT NULL,
  `clas_esp_MonoR` int(11) DEFAULT NULL,
  `clas_PoliR_H` int(11) DEFAULT NULL,
  `clas_PoliR_R` int(11) DEFAULT NULL,
  `clas_PoliR_Z` int(11) DEFAULT NULL,
  `clas_PoliR_E` int(11) DEFAULT NULL,
  `clas_PoliR_S` int(11) DEFAULT NULL,
  `clas_PoliR_fluoroquinolonas` int(11) DEFAULT NULL,
  `clas_PoliR_2linea` int(11) DEFAULT NULL,
  `clas_id_fluoroquinolonas` int(11) DEFAULT NULL,
  `clas_id_2linea` int(11) DEFAULT NULL,
  `mat_diag_resis_ninguna` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamentos, Ninguna',
  `mat_diag_mono_r` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamentos MonoR',
  `mat_diag_esp_MonoR` int(1) DEFAULT NULL COMMENT 'Especificación de MonoR',
  `mat_diag_PoliR_H` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamenteos PoliR, H',
  `mat_diag_PoliR_R` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamenteos PoliR, R',
  `mat_diag_PoliR_Z` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamenteos PoliR, Z',
  `mat_diag_PoliR_E` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamenteos PoliR, E',
  `mat_diag_PoliR_S` int(1) DEFAULT NULL COMMENT 'Resistencia a medicamenteos PoliR, S',
  `id_un_mat` int(11) DEFAULT NULL COMMENT 'Identificador de la unidad que reporta el caso',
  `id_inyect_2linea` int(11) DEFAULT NULL COMMENT 'Identificador de Inyectables de 2a línea',
  `mat_diag_MDR` int(11) DEFAULT NULL COMMENT 'Resistencia a los medicamentos MDR',
  `mat_diag_XDR` int(11) DEFAULT NULL COMMENT 'Resistencia a los medicamentos XDR',
  `mat_diag_TB-RR` int(11) DEFAULT NULL COMMENT 'Resistencia a los medicamentos TB-RR',
  `mat_diag_desconocida` int(11) DEFAULT NULL COMMENT 'Resistencia a los medicamentos Desconocida',
  `trat_referido` int(11) DEFAULT NULL COMMENT 'Referencia del paciente a otra institución',
  `trat_inst_salud_ref` int(11) DEFAULT NULL COMMENT 'Instalación a la que fue referido el paciente',
  `trat_fecha_inicio_tratF1` date DEFAULT NULL COMMENT 'Fecha de inicio de tratamiento Fase 1',
  `trat_med_H_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, H',
  `trat_med_R_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, R',
  `trat_med_Z_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, Z',
  `trat_med_E_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, E',
  `trat_med_S_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, S',
  `trat_med_otros_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, Otros',
  `trat_fecha_fin_tratF1` date DEFAULT NULL COMMENT 'Fecha de fin de tratamiento Fase 1',
  `id_adm_tratamiento_F1` int(1) DEFAULT NULL COMMENT 'Tratamiento modo de administración Fase 1',
  `trat_fecha_inicio_tratF2` date DEFAULT NULL COMMENT 'Fecha de inicio de tratamiento Fase 1',
  `trat_med_H_F2` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, H',
  `trat_med_R_F2` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, R',
  `trat_med_E_F2` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, E',
  `trat_med_otros_F2` int(1) DEFAULT NULL COMMENT 'Tratamiento medicamento indicado Fase 1, Otros',
  `trat_fecha_fin_tratF2` date DEFAULT NULL COMMENT 'Fecha de fin de tratamiento Fase 1',
  `id_adm_tratamiento_F2` int(1) DEFAULT NULL COMMENT 'Tratamiento modo de administración Fase 1',
  `TB_VIH_solicitud_VIH` int(1) DEFAULT NULL COMMENT 'Se solicitó la prueba VIH 1:si, 0:no',
  `TB_VIH_acepto_VIH` int(1) DEFAULT NULL COMMENT 'Paciente aceptó hacerse la prueba 1:si, 0:no',
  `TB_VIH_realizada_VIH` int(1) DEFAULT NULL COMMENT 'Paciente aceptó hacerse la prueba 1:si, 0:no',
  `TB_VIH_fecha_muestra_VIH` date DEFAULT NULL COMMENT 'Fecha de toma de muestra',
  `TB_VIH_res_VIH` int(1) DEFAULT NULL COMMENT 'Resultado prueba VIH',
  `TB_VIH_ref_TARV` int(1) DEFAULT NULL COMMENT 'Referido a TARV',
  `TB_VIH_fecha_ref_TARV` date DEFAULT NULL COMMENT 'Fecha de referencia a TARV',
  `TB_VIH_inicio_TARV` int(1) DEFAULT NULL COMMENT 'Inicio TARV?',
  `TB_VIH_aseso_VIH` int(1) DEFAULT NULL COMMENT 'Asesoría pos prueba VIH',
  `TB_VIH_cotrimoxazol` int(1) DEFAULT NULL COMMENT 'Terapia cotrimoxazol',
  `TB_VIH_fecha_cotrimoxazol` date DEFAULT NULL COMMENT 'Fecha inicio terapia cotrimoxazol',
  `TB_VIH_fecha_inicio_TARV` date DEFAULT NULL COMMENT 'Fecha de inicio TARV',
  `TB_VIH_lug_adm_TARV` varchar(150) DEFAULT NULL COMMENT 'Lugar de administración de TARV',
  `TB_VIH_esq_ARV` int(1) DEFAULT NULL COMMENT 'Esquema ARV',
  `TB_VIH_fecha_prueba_VIH` date DEFAULT NULL COMMENT 'Fecha de prueba VIH',
  `TB_VIH_res_previa_VIH` int(1) DEFAULT NULL COMMENT 'Resultado de VIH prevía',
  `TB_VIH_act_TARV` int(1) DEFAULT NULL COMMENT 'Resultado de VIH prevía',
  `TB_VIH_isoniacida` int(1) DEFAULT NULL COMMENT 'Resultado de VIH prevía',
  `contacto_identificados_5min` int(11) DEFAULT NULL COMMENT 'Total de contactos identificados',
  `contacto_sinto_resp_5min` int(11) DEFAULT NULL COMMENT 'Total de contactos sintomáticos respiratorios',
  `contacto_evaluados_5min` int(11) DEFAULT NULL COMMENT 'Total de contactos evaluados',
  `contacto_quimioprofilaxis_5min` int(11) DEFAULT NULL COMMENT 'Total de contactos con Quimioprofilaxis',
  `contacto_TB_5min` int(11) DEFAULT NULL COMMENT 'Total de contactos con TB',
  `contacto_identificados_5pl` int(11) DEFAULT NULL,
  `contacto_sinto_resp_5pl` int(11) DEFAULT NULL,
  `contacto_evaluados_5pl` int(11) DEFAULT NULL,
  `contacto_quimioprofilaxis_5pl` int(11) DEFAULT NULL,
  `contacto_TB_5pl` int(11) DEFAULT NULL,
  `apoyo_social` int(11) DEFAULT NULL,
  `apoyo_nutricional` int(11) DEFAULT NULL,
  `apoyo_economico` int(11) DEFAULT NULL,
  `egreso_fecha_egreso` date DEFAULT NULL COMMENT 'Fecha de egreso',
  `egreso_cond_egreso` int(11) DEFAULT NULL,
  `egreso_motivo_exclusion` int(11) DEFAULT NULL,
  `semana_epi` int(3) DEFAULT NULL COMMENT 'Semana epidemiologica del reporte, basada en la fecha inicio de sintomas',
  `anio` int(4) DEFAULT NULL COMMENT 'anio del inicio de sintomas',
  `nombre_toma_muestra` varchar(150) DEFAULT NULL COMMENT 'Nombre la persona quien toma la muestra',
  `pendiente_uceti` int(1) DEFAULT '0' COMMENT 'Esta pendiente de llenado de datos de la ficha - 0:si, 1:no',
  `pendiente_silab` int(1) DEFAULT '0' COMMENT 'Esta pendiente de llenado de datos de silab - 0:si, 1:no',
  `actualizacion_silab` timestamp NULL DEFAULT NULL COMMENT 'Ultima actualizacion con silab',
  `source_entry` int(1) NOT NULL COMMENT 'Fuente de entrada de datos 0:web, 1:tablet',
  `per_antes_preso` int(11) DEFAULT NULL,
  `per_fecha_antespreso` date DEFAULT NULL,
  PRIMARY KEY (`id_tb`),
  KEY `index_patient` (`tipo_identificacion`,`numero_identificacion`),
  KEY `index_fh_caso` (`mat_diag_fecha_BK1`),
  KEY `index_edad` (`per_edad`,`per_tipo_edad`),
  KEY `INDEX_NUM_IDENTIFICA` (`numero_identificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla del formulario de tuberculosis registro' AUTO_INCREMENT=1233 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_grupo_riesgo_mdr`
--

DROP TABLE IF EXISTS `tb_grupo_riesgo_mdr`;
CREATE TABLE IF NOT EXISTS `tb_grupo_riesgo_mdr` (
  `id_tb` int(11) NOT NULL,
  `id_grupo_riesgo_MDR` int(11) NOT NULL,
  PRIMARY KEY (`id_tb`,`id_grupo_riesgo_MDR`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_inmunodepresor`
--

DROP TABLE IF EXISTS `tb_inmunodepresor`;
CREATE TABLE IF NOT EXISTS `tb_inmunodepresor` (
  `id_tb` int(11) NOT NULL,
  `id_inmunodepresor` int(11) NOT NULL,
  PRIMARY KEY (`id_tb`,`id_inmunodepresor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_visitas`
--

DROP TABLE IF EXISTS `tb_visitas`;
CREATE TABLE IF NOT EXISTS `tb_visitas` (
  `id_tb_visita` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tipo_visita` int(10) unsigned NOT NULL,
  `fecha_visita` date NOT NULL,
  `id_tb_form` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_tb_visita`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1076 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mat_diagnostico`
--

DROP TABLE IF EXISTS `tbl_mat_diagnostico`;
CREATE TABLE IF NOT EXISTS `tbl_mat_diagnostico` (
  `semana_epi` int(2) NOT NULL COMMENT 'Semana epidemiológica',
  `anio` int(4) NOT NULL,
  `DiaToma` int(2) DEFAULT NULL,
  `MesToma` int(2) DEFAULT NULL,
  `AnioToma` int(4) DEFAULT NULL,
  `id_diagnostico` int(11) NOT NULL DEFAULT '0',
  `nombre_diagnostico` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del Evento de Notificación Obligatoria',
  `id_nivel_geo1` int(11) NOT NULL DEFAULT '0',
  `nombre_nivel_geo1` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de las provincias de Panamá',
  `id_nivel_geo2` int(11) NOT NULL DEFAULT '0' COMMENT 'Código correlativo númerico y autoincremental de las regiones de salud del MINSA',
  `nombre_nivel_geo2` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la Región de Salud',
  `id_nivel_geo3` int(11) NOT NULL DEFAULT '0',
  `nombre_nivel_geo3` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del distrito de Panamá',
  `id_nivel_geo4` int(11) NOT NULL DEFAULT '0',
  `nombre_nivel_geo4` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre del corregimiento de Panamá',
  `id_establecimiento` int(11) NOT NULL DEFAULT '0',
  `nombre_establecimiento` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la Unidad Notificadora',
  `numero_casos` decimal(32,0) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nivel_instalacion`
--

DROP TABLE IF EXISTS `tbl_nivel_instalacion`;
CREATE TABLE IF NOT EXISTS `tbl_nivel_instalacion` (
  `idnivel_instalacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del nivel de instalacion',
  `nombre` varchar(45) NOT NULL COMMENT 'Nombre del nivel de instalación de salud',
  PRIMARY KEY (`idnivel_instalacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Niveles de las instalaciones de salud' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_persona`
--

DROP TABLE IF EXISTS `tbl_persona`;
CREATE TABLE IF NOT EXISTS `tbl_persona` (
  `tipo_identificacion` int(11) NOT NULL COMMENT 'identificador del tipo de identificacion',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Número de identificacion del paciente',
  `primer_nombre` varchar(45) DEFAULT NULL COMMENT 'Primer nombre de la persona fallecida',
  `segundo_nombre` varchar(45) DEFAULT NULL COMMENT 'Segundo nombre de la persona fallecida',
  `primer_apellido` varchar(45) DEFAULT NULL COMMENT 'Primer apellido de la persona fallecida',
  `segundo_apellido` varchar(45) DEFAULT NULL COMMENT 'Segundo apellido de la persona fallecida',
  `casada_apellido` varchar(45) DEFAULT NULL,
  `sin_nombre` int(1) DEFAULT '0' COMMENT 'Almacena si tiene o no nombre registrada la persona fallecida:\n1 = No tiene nombre\n0 = Si tiene nombre\n',
  `fecha_nacimiento` datetime DEFAULT NULL,
  `edad` int(3) DEFAULT '0' COMMENT 'Edad de la persona fallecida',
  `tipo_edad` int(1) NOT NULL COMMENT 'Tipo de dato de edad:\n0 = No Dato\n1 = Años\n2 = Meses\n3 = Días\n',
  `sexo` varchar(1) NOT NULL COMMENT 'Sexo de la persona fallecida:\n\nM = Masculino\nF = Femenino',
  `id_region` int(11) DEFAULT NULL,
  `id_corregimiento` int(11) DEFAULT NULL COMMENT 'Corregimiento en donde vive la persona',
  `localidad` varchar(100) DEFAULT NULL COMMENT 'Localidad en donde vive la persona',
  `dir_referencia` varchar(150) DEFAULT NULL COMMENT 'Referencia para hallar la casa',
  `id_pais` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais',
  `id_ocupacion` int(11) DEFAULT NULL COMMENT 'Identificador de la ocupación de la persona',
  `nombre_responsable` varchar(60) DEFAULT NULL COMMENT 'Nombre de la persona responsable si es menor de edad',
  `tel_residencial` varchar(20) DEFAULT NULL COMMENT 'Telefono de la casa',
  `dir_trabajo` varchar(100) DEFAULT NULL COMMENT 'Direccion del lugar de trabajo',
  `tel_trabajo` varchar(20) DEFAULT NULL COMMENT 'Telefono del lugar de trabajo',
  `id_pais_nacimiento` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais de nacimiento',
  `id_corregimiento_nacimiento` int(11) NOT NULL DEFAULT '630' COMMENT 'Identificador del corregimiento de nacimiento',
  `id_estado_civil` int(1) DEFAULT '5' COMMENT '1:soltero 2:casado 3:unido 4:divorciado 5:NE',
  `id_escolaridad` int(1) DEFAULT '8' COMMENT '1y2:primaria 2y3:secundaria 4y5:universidad 6:vocacional 7:ninguna 8:NE',
  `id_etnia` int(11) DEFAULT NULL COMMENT 'identificador de la etnia',
  `id_genero` int(11) DEFAULT NULL COMMENT 'identificador del genero',
  `id_gpopoblacional` int(11) DEFAULT NULL COMMENT 'Id del grupo poblacional',
  PRIMARY KEY (`tipo_identificacion`,`numero_identificacion`),
  KEY `fk_mortalidad_corregimiento` (`id_corregimiento`),
  KEY `fk_mortalidad_tipo_identificacion` (`tipo_identificacion`),
  KEY `fk_persona_ocupacion` (`id_ocupacion`),
  KEY `fk_persona_pais` (`id_pais`),
  KEY `fk_persona_tipo_edad` (`tipo_edad`),
  KEY `fk_per_nacimiento_corregimiento` (`id_corregimiento_nacimiento`),
  KEY `fk_per_nacimiento_pais` (`id_pais_nacimiento`),
  KEY `fk_persona_etnia` (`id_etnia`),
  KEY `fk_persona_genero` (`id_genero`),
  KEY `index_numero_identifica` (`numero_identificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de persona para VIGMOR y NOTIC';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_persona_HDN`
--

DROP TABLE IF EXISTS `tbl_persona_HDN`;
CREATE TABLE IF NOT EXISTS `tbl_persona_HDN` (
  `tipo_identificacion` int(11) NOT NULL COMMENT 'identificador del tipo de identificacion',
  `numero_identificacion` varchar(30) NOT NULL COMMENT 'Número de identificacion del paciente',
  `primer_nombre` varchar(45) DEFAULT NULL COMMENT 'Primer nombre de la persona fallecida',
  `segundo_nombre` varchar(45) DEFAULT NULL COMMENT 'Segundo nombre de la persona fallecida',
  `primer_apellido` varchar(45) DEFAULT NULL COMMENT 'Primer apellido de la persona fallecida',
  `segundo_apellido` varchar(45) DEFAULT NULL COMMENT 'Segundo apellido de la persona fallecida',
  `sin_nombre` int(1) DEFAULT '0' COMMENT 'Almacena si tiene o no nombre registrada la persona fallecida:\n1 = No tiene nombre\n0 = Si tiene nombre\n',
  `fecha_nacimiento` datetime DEFAULT NULL,
  `edad` int(3) DEFAULT '0' COMMENT 'Edad de la persona fallecida',
  `tipo_edad` int(1) NOT NULL COMMENT 'Tipo de dato de edad:\n0 = No Dato\n1 = Años\n2 = Meses\n3 = Días\n',
  `sexo` varchar(1) NOT NULL COMMENT 'Sexo de la persona fallecida:\n\nM = Masculino\nF = Femenino',
  `id_region` int(11) DEFAULT NULL,
  `id_corregimiento` int(11) DEFAULT NULL COMMENT 'Corregimiento en donde vive la persona',
  `localidad` varchar(100) DEFAULT NULL COMMENT 'Localidad en donde vive la persona',
  `dir_referencia` varchar(150) DEFAULT NULL COMMENT 'Referencia para hallar la casa',
  `id_pais` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais',
  `id_ocupacion` int(11) DEFAULT NULL COMMENT 'Identificador de la ocupación de la persona',
  `nombre_responsable` varchar(60) DEFAULT NULL COMMENT 'Nombre de la persona responsable si es menor de edad',
  `tel_residencial` varchar(20) DEFAULT NULL COMMENT 'Telefono de la casa',
  `dir_trabajo` varchar(100) DEFAULT NULL COMMENT 'Direccion del lugar de trabajo',
  `tel_trabajo` varchar(20) DEFAULT NULL COMMENT 'Telefono del lugar de trabajo',
  `id_pais_nacimiento` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais de nacimiento',
  `id_corregimiento_nacimiento` int(11) NOT NULL DEFAULT '630' COMMENT 'Identificador del corregimiento de nacimiento',
  PRIMARY KEY (`tipo_identificacion`,`numero_identificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de persona para VIGMOR y NOTIC';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_persona_temp`
--

DROP TABLE IF EXISTS `tbl_persona_temp`;
CREATE TABLE IF NOT EXISTS `tbl_persona_temp` (
  `tipo_identificacion` int(11) NOT NULL COMMENT 'identificador del tipo de identificacion',
  `numero_identificacion` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Número de identificacion del paciente',
  `primer_nombre` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Primer nombre de la persona fallecida',
  `segundo_nombre` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Segundo nombre de la persona fallecida',
  `primer_apellido` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Primer apellido de la persona fallecida',
  `segundo_apellido` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Segundo apellido de la persona fallecida',
  `sin_nombre` int(1) DEFAULT '0' COMMENT 'Almacena si tiene o no nombre registrada la persona fallecida:\n1 = No tiene nombre\n0 = Si tiene nombre\n',
  `fecha_nacimiento` datetime DEFAULT NULL,
  `edad` int(3) DEFAULT '0' COMMENT 'Edad de la persona fallecida',
  `tipo_edad` int(1) NOT NULL COMMENT 'Tipo de dato de edad:\n0 = No Dato\n1 = Años\n2 = Meses\n3 = Días\n',
  `sexo` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT 'Sexo de la persona fallecida:\n\nM = Masculino\nF = Femenino',
  `id_region` int(11) DEFAULT NULL,
  `id_corregimiento` int(11) DEFAULT NULL COMMENT 'Corregimiento en donde vive la persona',
  `localidad` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Localidad en donde vive la persona',
  `dir_referencia` varchar(150) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Referencia para hallar la casa',
  `id_pais` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais',
  `id_ocupacion` int(11) DEFAULT NULL COMMENT 'Identificador de la ocupación de la persona',
  `nombre_responsable` varchar(60) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Nombre de la persona responsable si es menor de edad',
  `tel_residencial` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Telefono de la casa',
  `dir_trabajo` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Direccion del lugar de trabajo',
  `tel_trabajo` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Telefono del lugar de trabajo',
  `id_pais_nacimiento` int(11) NOT NULL DEFAULT '174' COMMENT 'Identificador del pais de nacimiento',
  `id_corregimiento_nacimiento` int(11) NOT NULL DEFAULT '630' COMMENT 'Identificador del corregimiento de nacimiento',
  `id_estado_civil` int(1) DEFAULT '5' COMMENT '1:soltero 2:casado 3:unido 4:divorciado 5:NE',
  `id_escolaridad` int(1) DEFAULT '8' COMMENT '1y2:primaria 2y3:secundaria 4y5:universidad 6:vocacional 7:ninguna 8:NE',
  `id_etnia` int(11) DEFAULT NULL COMMENT 'identificador de la etnia',
  `id_genero` int(11) DEFAULT NULL COMMENT 'identificador del genero'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_personal_medico`
--

DROP TABLE IF EXISTS `tbl_personal_medico`;
CREATE TABLE IF NOT EXISTS `tbl_personal_medico` (
  `id_personal_medico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del personal medico dentro de la BD',
  `nombre_personal_medico` varchar(60) NOT NULL COMMENT 'Nombre del personal medico',
  `registro_medico` varchar(11) NOT NULL COMMENT 'Codigo del registro medico',
  `id_cargo` int(11) NOT NULL COMMENT 'Identificador del cargo del personal medico',
  PRIMARY KEY (`id_personal_medico`),
  KEY `fk_personal_cargo` (`id_cargo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Listado del personal medico' AUTO_INCREMENT=1712 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tipo_instalacion`
--

DROP TABLE IF EXISTS `tbl_tipo_instalacion`;
CREATE TABLE IF NOT EXISTS `tbl_tipo_instalacion` (
  `idtipo_instalacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico del tipo de instalacion',
  `idnivel_instalacion` int(11) NOT NULL COMMENT 'Identificador del nivel del tipo de la instalacion',
  `nombre` varchar(60) NOT NULL COMMENT 'Nombre del tipo de instalacion',
  `codigo` varchar(2) NOT NULL COMMENT 'Codigo de referencia del tipo de instalacion',
  PRIMARY KEY (`idtipo_instalacion`),
  KEY `FK_tipo_instalacion_nivel` (`idnivel_instalacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Codigos de instalaciones segun nivel de complejidad' AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_inst`
--

DROP TABLE IF EXISTS `tmp_inst`;
CREATE TABLE IF NOT EXISTS `tmp_inst` (
  `codigo` varchar(10) NOT NULL,
  `region` varchar(5) NOT NULL,
  `distrito` varchar(10) NOT NULL,
  `corr` varchar(10) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_antibiotico`
--

DROP TABLE IF EXISTS `vicits_antibiotico`;
CREATE TABLE IF NOT EXISTS `vicits_antibiotico` (
  `id_vicits_antibiotico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de VICITS con los antibioticos',
  `id_vicits_form` int(11) NOT NULL COMMENT 'Identificador del formulario de VICITS',
  `nombre` varchar(255) DEFAULT NULL COMMENT 'nombre del antibiotico',
  `motivo` varchar(255) DEFAULT NULL COMMENT 'motivo del antibiotico',
  `fecha` date DEFAULT NULL COMMENT 'Fecha en que se tomo el antibiotico',
  PRIMARY KEY (`id_vicits_antibiotico`),
  KEY `fk_vicits_antibiotico_form` (`id_vicits_form`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS con los antibioti' AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_droga`
--

DROP TABLE IF EXISTS `vicits_droga`;
CREATE TABLE IF NOT EXISTS `vicits_droga` (
  `id_vicits_droga` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de VICITS con las drogas',
  `id_vicits_form` int(11) NOT NULL COMMENT 'Identificador del formulario de VICITS',
  `id_droga` int(11) NOT NULL COMMENT 'Identificador unico de la droga',
  `fecha_consumo` int(1) DEFAULT NULL COMMENT 'Fecha de cuando utilizo la droga 1:12 meses 2:30 dias',
  PRIMARY KEY (`id_vicits_droga`),
  UNIQUE KEY `uk_vicits_form_droga` (`id_vicits_form`,`id_droga`,`fecha_consumo`),
  KEY `fk_vicits_droga` (`id_droga`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS con las Drogas' AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_form`
--

DROP TABLE IF EXISTS `vicits_form`;
CREATE TABLE IF NOT EXISTS `vicits_form` (
  `id_vicits_form` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario de VICITS',
  `semana_epi` int(11) NOT NULL COMMENT 'Semana epidemiológica',
  `anio` int(6) NOT NULL COMMENT 'Año de la semana epidemiológica',
  `nombre_registra` varchar(45) NOT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `noti_nombre_medico` varchar(200) DEFAULT NULL COMMENT 'Nombre medico',
  `fecha_consulta` date DEFAULT NULL COMMENT 'Fecha consulta',
  `fecha_formulario` date NOT NULL COMMENT 'Fecha en que se llena el formulario',
  `consulta` int(1) DEFAULT NULL COMMENT 'Tipo de consulta 1:nueva 2:reconsulta',
  `id_un` int(11) DEFAULT NULL COMMENT 'Código de la unidad notificadora',
  `unidad_disponible` int(1) DEFAULT NULL COMMENT '1:marcado no dispoible la unidad notificadora',
  `id_tipo_identidad` int(11) DEFAULT NULL,
  `numero_identificacion` varchar(30) DEFAULT NULL,
  `per_nombre_trans` varchar(100) DEFAULT NULL COMMENT '(solo hombres)Nombre con identidad de genero',
  `per_edad` int(3) DEFAULT '0' COMMENT 'Edad de la persona fallecida',
  `per_tipo_edad` int(1) NOT NULL COMMENT 'Tipo de dato de edad:n0 = No Daton1 = Añosn2 = Mesesn3 = Díasn',
  `per_id_pais` int(11) NOT NULL DEFAULT '174' COMMENT 'Corregimiento en donde vive la persona',
  `per_id_corregimiento` int(11) NOT NULL DEFAULT '630' COMMENT 'Corregimiento en donde vive la persona',
  `per_localidad` varchar(100) DEFAULT NULL COMMENT 'Localidad en donde vive la persona',
  `per_estado_civil` int(1) DEFAULT NULL COMMENT '1:soltero 2:casado 3:unido 4:divorciado 5:NE',
  `per_sabe_leer` int(1) DEFAULT NULL COMMENT 'Sabe leer 1:Si 2:No 0:desconocido',
  `antec_abuso_sexual` int(1) DEFAULT NULL COMMENT 'Sufrio abuso sexual 1:si 2:no 0:desconocido',
  `antec_edad_abuso_sexual` int(3) DEFAULT NULL COMMENT 'Edad de cuando ocurrio el abuso sexual',
  `antec_abuso_ultimo` int(1) DEFAULT NULL COMMENT '1:si 2:no 3:no sabe Ha sufrido abuso sexual en los ultimos 12 meses',
  `antec_edad_inicio_sexual` int(3) DEFAULT NULL COMMENT 'Edad de inicio de vida sexual activa',
  `antec_ts_alguna_vez` int(1) DEFAULT NULL COMMENT 'Alguna vez ha ejercido trabajo sexual 1:si 2:no 0:desconocido',
  `antec_ts_actual` int(1) DEFAULT NULL COMMENT 'Actualmente es trabajador sexual 1:si 2:no 0:desconocido',
  `antec_ts_tiempo` int(1) DEFAULT NULL COMMENT 'Tiempo del trabajo sexual 1:un mes, 2:de1a6 meses, 3:de7a12 meses, 4:mas del anio',
  `antec_ts_tiempo_anios` int(3) DEFAULT NULL COMMENT 'Cuantos anios lleva como trabajador sexual',
  `antec_ts_otro_pais` int(2) DEFAULT NULL COMMENT 'ha sido trabajador sexual en otro pais 1:si 2:no 0:desconocido',
  `antec_ts_id_pais` int(11) DEFAULT NULL COMMENT 'Identificador del pais donde ejercio como trabajador sexual',
  `antec_relacion` int(1) DEFAULT NULL COMMENT 'Alguna vez ha tenido relaicones con 1:Hombres 2:Mujeres 3:Ambos 0:desconocido',
  `antec_consumo_alcohol` int(1) DEFAULT NULL COMMENT 'Consumio alcohol en los ultimos 30 dias 1:si 2:no 0:desconocido',
  `antec_consumo_alcohol_semana` int(1) DEFAULT NULL COMMENT '1:dia, 2:2a3 dias, 3:4a5 dias, 4:diario, 5:no consumio, 0:desconocido',
  `antec_its_ultimo` int(1) DEFAULT NULL COMMENT 'Ha tenido ITS en el ultimo anio 1:si 2:no 0:desconocido',
  `antec_otra_its` varchar(50) DEFAULT NULL COMMENT 'Nombre de otra ITS',
  `antec_vih` int(1) DEFAULT NULL COMMENT 'Tiene diagnostico de VIH 1:si 2:no 0:desconocido',
  `antec_fecha_vih` date DEFAULT NULL COMMENT 'Fecha de diagnostico del caso de VIH',
  `antec_referido_TARV` int(1) DEFAULT NULL COMMENT 'Referido a clinica TARV 1:si 2:no',
  `id_clinica_tarv` int(11) DEFAULT NULL COMMENT 'id de la clinica TARV a la que fue referido',
  `antec_cambio_comportamiento` int(1) DEFAULT NULL COMMENT 'Ha recibido una intervencion de cambio de comportamiento en los ultimos 12 meses Si:1 no:2',
  `antec_consejeria_pre` int(1) DEFAULT NULL COMMENT 'Consejeria antes de la prueba de VIH 1:si 2:no',
  `antec_consejeria_post` int(1) DEFAULT NULL COMMENT 'Consejeria despues de la prueba de VIH 1:si 2:no',
  `antec_ts_nombre_lugar` varchar(100) DEFAULT NULL COMMENT 'Nombre del lugar donde ejerce como TS',
  `antec_ts_tipo_lugar` int(1) DEFAULT NULL COMMENT 'Tipo de lugar donde ejerce como TS 1:club, 2:privado, 3:burdel, 4:masaje, 5:calle, 6:otro, 0:desconocido',
  `antec_motivo_consulta` int(1) DEFAULT NULL COMMENT '1:nuevo 2:Molestias 3:C. trimestral, 4:C. semestral',
  `antec_ts_anio_otro_pais` int(4) DEFAULT NULL COMMENT '(solo mujeres) Ultimo anio en el que trabajo como TS en otro pais',
  `antec_consumo_droga` int(1) DEFAULT NULL COMMENT '(solo mujeres) Consumio alguna vez drogas 1:si 2:no 0:desconocido',
  `antec_anticonceptivo` int(1) DEFAULT NULL COMMENT '(solo mujeres) En los ultimos 3 meses uso algun metodo anticonceptivo 1:si 2:no 0:desconocido',
  `antec_anticonceptivo_diu` int(1) DEFAULT NULL COMMENT '(solo mujeres) Uso DIU 1:si 2:no',
  `antec_anticonceptivo_pildora` int(1) DEFAULT NULL COMMENT '(solo mujeres) Uso de la pildora 1:si 2:no',
  `antec_anticonceptivo_condon` int(1) DEFAULT NULL COMMENT '(solo mujeres) Uso de condon 1:si 2:no',
  `antec_anticonceptivo_inyeccion` int(1) DEFAULT NULL COMMENT '(solo mujeres) Inyeccion 1:si 2:no',
  `antec_anticonceptivo_esteriliza` int(1) DEFAULT NULL COMMENT '(solo mujeres) Esterilizacion 1:si 2:no',
  `antec_anticonceptivo_otro` int(1) DEFAULT NULL COMMENT '(solo mujeres) Otro metodo anticonceptivo 1:si 2:no',
  `antec_anticonceptivo_nombre_otro` varchar(100) DEFAULT NULL COMMENT '(solo mujeres) Nombre del otro metodo anticonceptivo',
  `antec_obstetrico_menarquia` int(2) DEFAULT NULL COMMENT 'cuantos anios',
  `antec_obstetrico_abortos` int(2) DEFAULT NULL COMMENT 'Numero de abortos',
  `antec_obstetrico_muertos` int(2) DEFAULT NULL COMMENT 'Ninos nacidos muertos',
  `antec_obstetrico_vivos` int(2) DEFAULT NULL COMMENT 'Ninos nacidos vivos',
  `antec_obstetrico_total` int(2) DEFAULT NULL COMMENT 'Total de embarazos la suma de los abortos, vivos y muertos',
  `otro_antibiotico` int(1) DEFAULT NULL COMMENT 'Recibio antibioticos en las dos ultimas semanas 1:Si, 2:no, 0:no sabe',
  `otro_ovulos_vagina` int(1) DEFAULT NULL COMMENT '(solo mujeres) Uso uvolos vaginales en los ultimos 30 dias 1:si 2:no',
  `otro_ducha_vagina` int(1) DEFAULT NULL COMMENT '(solo mujeres) Uso duchas vaginales en los ultimos 30 dias 1:si 2:no',
  `otro_fecha_citologia` varchar(7) DEFAULT NULL COMMENT '(solo mujeres) Fecha de ultima citologia solo mes y anio (mm/yyyy)',
  `otro_citologia_resultado` varchar(100) DEFAULT NULL COMMENT '(solo mujeres) Resultado de la ultima citologia',
  `condon_rel_sexual` int(1) DEFAULT NULL COMMENT 'Tuvo relaciones sexuales en los ultimos 30 dias 1:Si 2:No',
  `condon_rel_anal` int(1) DEFAULT NULL COMMENT 'Tuvo relaciones anales en los ultimos 30 dias 1:si 2:no',
  `condon_tipo_rel_anal` int(1) DEFAULT NULL COMMENT '(solo hombres) Tipo de relacion anal 1:Penetra 2:Es penetrado 3:Ambos',
  `condon_sexo_oral` int(1) DEFAULT NULL COMMENT 'Practico sexo oral en los ultimos 30 dias 1:si 2:no',
  `condon_ult_rel_uso_condon` int(1) DEFAULT NULL COMMENT 'Uso condon en la ultima relacion sexual 1:si 2:no',
  `condon_rel_anal_otro` int(1) DEFAULT NULL COMMENT 'Ha tenido relaciones sexuales analaes con otros hombre 6 meses 1:si 2:no',
  `par_hombre_fija` int(1) DEFAULT NULL COMMENT 'Tiene pareja hombre estable o fija 1:si 2:no',
  `par_hombre_fija_uso_condon` int(1) DEFAULT NULL COMMENT 'Usa condon en los ultimos 30 dias 1:Nunca 2:A veces 3:Siempre',
  `par_hombre_fija_ult_usu_condon` int(1) DEFAULT NULL COMMENT 'Uso condon en su ultima relacion 1:si 2:no',
  `par_hombre_casual` int(1) DEFAULT NULL COMMENT 'Tiene pareja hombre casual 1:si 2:no',
  `par_hombre_casual_uso_condon` int(1) DEFAULT NULL COMMENT 'Usa condon en los ultimos 30 dias 1:Nunca 2:A veces 3:Siempre',
  `par_hombre_casual_ult_usu_condon` int(1) DEFAULT NULL COMMENT 'Uso condon en su ultima relacion 1:si 2:no',
  `par_mujer_fija` int(1) DEFAULT NULL COMMENT '(solo hombres) Tiene pareja mujer estable o fija 1:si 2:no',
  `par_mujer_fija_uso_condon` int(1) DEFAULT NULL COMMENT '(solo hombres) Usa condon en los ultimos 30 dias 1:Nunca 2:A veces 3:Siempre',
  `par_mujer_fija_ult_usu_condon` int(1) DEFAULT NULL COMMENT '(solo hombres) Uso condon en su ultima relacion 1:si 2:no',
  `par_mujer_casual` int(1) DEFAULT NULL COMMENT '(solo hombres) Tiene pareja mujer casual 1:si 2:no',
  `par_mujer_casual_uso_condon` int(1) DEFAULT NULL COMMENT '(solo hombres) Usa condon en los ultimos 30 dias 1:Nunca 2:A veces 3:Siempre',
  `par_mujer_casual_ult_usu_condon` int(1) DEFAULT NULL COMMENT '(solo hombres)Uso condon en su ultima relacion 1:si 2:no',
  `ts_cliente_semana` int(11) DEFAULT NULL COMMENT 'Numero de clientes en la ultima semana',
  `ts_cliente_quincena` int(11) DEFAULT NULL COMMENT 'Numero de clientes en la ultima quincena',
  `ts_uso_condon` int(1) DEFAULT NULL COMMENT 'Uso condon con sus clientes en los ultimos 30 dias 1:nunca 2:a veces 3:siempre',
  `ts_ultimo_usu_condon` int(1) DEFAULT NULL COMMENT 'Uso condon con su ultimo cliente 1:si 2:no',
  `exa_realizado` int(1) DEFAULT NULL COMMENT 'Se realizo examen general 1:realizado 2:no realizado',
  `exa_bimanual_realizado` int(1) DEFAULT NULL COMMENT 'Examen bimanual realizado 1:si 2:no',
  `exa_especulo_realizado` int(1) DEFAULT NULL COMMENT 'Examen especulo realizado 1:si 2:no',
  `exa_temperatura` int(3) DEFAULT NULL COMMENT 'Temperatura en grados celcius',
  `exa_libras` int(3) DEFAULT NULL COMMENT 'Peso de la persona en libras',
  `exa_PA` varchar(20) DEFAULT NULL COMMENT 'P/A en mmHg',
  `exa_ganglio` int(1) DEFAULT NULL COMMENT 'Examen de ganglio 1:normal 2:anormal',
  `exa_ganglio_cuello` int(1) DEFAULT NULL COMMENT 'Ganglios anormales en cuello 1:si 2:no',
  `exa_ganglio_axilar` int(1) DEFAULT NULL COMMENT 'Ganglios anormales en axilas 1:si 2:no',
  `exa_ganglio_inguinal` int(1) DEFAULT NULL COMMENT 'Ganglios anormales en la ingle 1:si 2:no',
  `exa_rash` int(1) DEFAULT NULL COMMENT 'Examen de rash 1:ausecia 2:presencia',
  `exa_rash_opcion` int(1) DEFAULT NULL COMMENT 'Presencia de Rash opcion',
  `exa_boca` int(1) DEFAULT NULL COMMENT 'Examen de boca 1:normal 2:anormal',
  `exa_boca_monilia` int(1) DEFAULT NULL COMMENT 'Monilia en boca 1:si 2:no',
  `exa_boca_ulcera` int(1) DEFAULT NULL COMMENT 'Ulceras en la boca 1:si 2:no',
  `exa_boca_amigdalas` int(1) DEFAULT NULL COMMENT 'Secrecion en las amigdalas 1:si 2:no',
  `exa_boca_irritacion_faringea` int(1) DEFAULT NULL COMMENT 'Irritacion faringea en boca 1:si 2:no',
  `exa_boca_otro` int(1) DEFAULT NULL COMMENT 'Otra anomalia en la boca 1:si 2:no',
  `exa_meato` int(1) DEFAULT NULL COMMENT 'Examen de meato uretral 1:normal 2:anormal',
  `exa_meato_hiperemia` int(1) DEFAULT NULL COMMENT 'Presencia de hiperemia en el meato uretral 1:si 2:no',
  `exa_meato_verruga` int(1) DEFAULT NULL COMMENT 'Verrugas en el meato uretral 1:si 2:no',
  `exa_meato_secrecion` int(1) DEFAULT NULL COMMENT 'Secrecion en el meato uretral 1:si 2:no',
  `exa_ano` int(1) DEFAULT NULL COMMENT 'Examen de ano 1:normal 2:anormal',
  `exa_ano_ulcera` int(1) DEFAULT NULL COMMENT 'Ulcera en el ano 1:si 2:no',
  `exa_ano_verruga` int(1) DEFAULT NULL COMMENT 'Verrugas en el ano 1:si 2:no',
  `exa_ano_secrecion` int(1) DEFAULT NULL COMMENT 'Secrecion en el ano 1:si 2:no',
  `exa_ano_vesicula` int(1) DEFAULT NULL COMMENT 'Ano vesicula 1:si 2:no',
  `exa_ano_otro` int(1) DEFAULT NULL COMMENT 'Otra anomalia en el ano 1:si 2:no',
  `exa_pene` int(1) DEFAULT NULL COMMENT '(solo hombres) Examen de pene 1:normal 2:anormal',
  `exa_pene_ulcera` int(1) DEFAULT NULL COMMENT '(solo hombres) Ulceras en el pene 1:si 2:no',
  `exa_pene_verruga` int(1) DEFAULT NULL COMMENT '(solo hombres) Verrugas en el pene 1:si 2:no',
  `exa_pene_ampolla` int(1) DEFAULT NULL COMMENT '(solo hombres) Ampolla o vesicula en el pene 1:si 2:no',
  `exa_pene_otro` int(1) DEFAULT NULL COMMENT '(solo hombres) Otra anomalia en el pene 1:si 2:no',
  `exa_testiculo` int(1) DEFAULT NULL COMMENT '(solo hombres) Examen de testiculo 1:normal 2:anormal',
  `exa_testiculo_ulcera` int(1) DEFAULT NULL COMMENT '(solo hombres) Ulceras en el testiculo 1:si 2:no',
  `exa_testiculo_verruga` int(1) DEFAULT NULL COMMENT '(solo hombres) Verrugas en el testiculo 1:si 2:no',
  `exa_testiculo_ampolla` int(1) DEFAULT NULL COMMENT '(solo hombres) Ampolla o vesicula en el testiculo 1:si 2:no',
  `exa_testiculo_otro` int(1) DEFAULT NULL COMMENT '(solo hombres) Otra anomalia en el testiculo 1:si 2:no',
  `exa_abdomen` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen de abdomen 1:normal 2:anormal',
  `exa_abdomen_fosa_izq` int(1) DEFAULT NULL COMMENT '(solo mujeres) Fosa iliaca izquierda 1:si 2:no',
  `exa_abdomen_fosa_der` int(1) DEFAULT NULL COMMENT '(solo mujeres) Fosa iliaca derecha 1:si 2:no',
  `exa_abdomen_hipogastrico` int(1) DEFAULT NULL COMMENT '(solo mujeres) Hipogastrico 1:si 2:no',
  `exa_vulva` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen de vulva 1:normal 2:anormal',
  `exa_vulva_ulcera` int(1) DEFAULT NULL COMMENT '(solo mujeres) Ulceras en la vulva 1:si 2:no',
  `exa_vulva_verruga` int(1) DEFAULT NULL COMMENT '(solo mujeres) Verrugas en la vulva 1:si 2:no',
  `exa_vulva_vesicula` int(1) DEFAULT NULL COMMENT '(solo mujeres) Ampolla o vesicula en la vulva 1:si 2:no',
  `exa_vulva_otro` int(1) DEFAULT NULL COMMENT '(solo mujeres) Otra anomalia en la vulva 1:si 2:no',
  `exa_vagina` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen de vagina 1:normal 2:anormal',
  `exa_vagina_ulcera` int(1) DEFAULT NULL COMMENT '(solo mujeres) Ulceras en la vagina 1:si 2:no',
  `exa_vagina_hiperamia` int(1) DEFAULT NULL COMMENT '(solo mujeres) Hiperamia en la vagina 1:si 2:no',
  `exa_vagina_menstruacion` int(1) DEFAULT NULL COMMENT '(solo mujeres) Menstruacion presente 1:si 2:no',
  `exa_vagina_atrofia` int(1) DEFAULT NULL COMMENT '(solo mujeres) Atrofia en la vulva 1:si 2:no',
  `exa_vagina_otro` int(1) DEFAULT NULL COMMENT '(solo mujeres) Otra anomalia en la vagina 1:si 2:no',
  `exa_flujo` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen de cantidad de flujo 1:normal 2:anormal',
  `exa_flujo_cantidad` int(1) DEFAULT NULL COMMENT 'Cantidad Flujo 1:normal 2:anormal 3:no sabe',
  `exa_flujo_color` int(1) DEFAULT NULL COMMENT '(solo mujeres) Color de flujo 1:blanco 2:verde/amarillo 3:amarillo 4:cafe',
  `exa_flujo_asp_sanguinolento` int(1) DEFAULT NULL COMMENT '(solo mujeres) Flujo de aspecto sanguinolento 1:si 2:no',
  `exa_flujo_asp_grumoso` int(1) DEFAULT NULL COMMENT '(solo mujeres) Flujo de aspecto grumoso 1:si 2:no',
  `exa_flujo_asp_espumoso` int(1) DEFAULT NULL COMMENT '(solo mujeres) Flujo de aspecto espumoso 1:si 2:no',
  `exa_flujo_asp_mucoso` int(1) DEFAULT NULL COMMENT '(solo mujeres) Flujo de aspecto mucoso 1:si 2:no',
  `exa_flujo_olor` int(1) DEFAULT NULL COMMENT '(solo mujeres) Flujo con olor fetido 1:si 2:no',
  `exa_cervix` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen de cervix 1:normal 2:anormal',
  `exa_cervix_ulcera` int(1) DEFAULT NULL COMMENT '(solo mujeres) Ulceras en la cervix 1:si 2:no',
  `exa_cervix_hiperamia` int(1) DEFAULT NULL COMMENT '(solo mujeres) Hiperamia en la cervix 1:si 2:no',
  `exa_cervix_friable` int(1) DEFAULT NULL COMMENT '(solo mujeres) Cervix friable 1:si 2:no',
  `exa_cervix_pus` int(1) DEFAULT NULL COMMENT '(solo mujeres) Pus en la cervix 1:si 2:no',
  `exa_cervix_tumor` int(1) DEFAULT NULL COMMENT '(solo mujeres) Tumor en la cervix 1:si 2:no',
  `exa_bi_anexo` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen bimanual de anexos 1:normal 2:anormal',
  `exa_bi_anexo_sangrado` int(1) DEFAULT NULL COMMENT '(solo mujeres) Anexos sangrado anormal 1:si 2:no',
  `exa_bi_anexo_dolor` int(1) DEFAULT NULL COMMENT '(solo mujeres) Anexos dolor 1:derecho 2:izquierdo',
  `exa_bi_anexo_tumor` int(1) DEFAULT NULL COMMENT '(solo mujeres) Anexos tumor 1:derecho 2:izquierdo',
  `exa_bi_hipogastrico` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen bimanual hipogastrico 1:normal 2:dolor a la palpitacion',
  `exa_bi_cervix` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen bimanual de cervix 1:normal 2:anormal',
  `exa_bi_cervix_ausente` int(1) DEFAULT NULL COMMENT 'Cervix Bi ausente 1:si 2:no',
  `exa_bi_cervix_dolor` int(1) DEFAULT NULL COMMENT 'Cervix Bi dolor 1:si 2:no',
  `exa_bi_utero` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen bimanual de utero 1:si 2:no',
  `exa_bi_utero_anormal` int(1) DEFAULT NULL COMMENT '(solo mujeres) Examen utero anormal 1:ausente 2:aumento de volumen',
  `muestra_ninguna` int(1) DEFAULT NULL COMMENT 'Ninguna muestra 1:si 2:no',
  `muestra_sangre_ts` int(1) DEFAULT NULL COMMENT 'Muestra sangre TS 1:si 2:no',
  `muestra_flujo_vaginal` int(1) DEFAULT NULL COMMENT 'Muestra flujo_vaginal 1:si 2:no',
  `muestra_endocervix` int(1) DEFAULT NULL COMMENT 'Muestra endocervix 1:si 2:no',
  `muestra_citologia` int(1) DEFAULT NULL COMMENT 'Muestra citologia 1:si 2:no',
  `muestra_ulcera_ts` int(1) DEFAULT NULL COMMENT 'Muestra ulcera TS 1:si 2:no',
  `muestra_sangre_hsh` int(1) DEFAULT NULL COMMENT 'Muestra sangre HSH 1:si 2:no',
  `muestra_ulcera` int(1) DEFAULT NULL COMMENT 'Muestra Ulcera 1:si 2:no',
  `muestra_secrecion_uretral` int(1) DEFAULT NULL COMMENT 'Muestra Secrecion uretral 1:si 2:no',
  `muestra_secrecion_anal` int(1) DEFAULT NULL COMMENT 'Muestra Secrecion anal 1:si 2:no',
  `usuario_sano` int(1) DEFAULT NULL COMMENT 'Usuario sano 1:si 2:no marcado',
  `fecha_menstruacion` date DEFAULT NULL COMMENT 'Fecha de menstruacion',
  `embarazo` int(1) DEFAULT NULL COMMENT 'Embarazo 1:si 2:no',
  `embarazo_semanas` int(3) DEFAULT NULL COMMENT 'Numero semanas embarazo',
  `otro_tratamiento` int(1) DEFAULT NULL COMMENT 'otro tratamiento 1:si 2:no 3:no sabe',
  `tx_sulfato` int(1) DEFAULT NULL COMMENT 'tx sulfato 1:si 2:no',
  `tx_acido_folico` int(1) DEFAULT NULL COMMENT 'tx acido folico 1:si 2:no',
  `tx_prenatales` int(1) DEFAULT NULL COMMENT 'tx prenatales 1:si 2:no',
  `tx_toxoide` int(1) DEFAULT NULL COMMENT 'tx toxoide 1:si 2:no',
  `intervencion` int(1) DEFAULT NULL COMMENT 'tx intervencion 1:si 2:no 3:no sabe',
  `diag_otro` varchar(100) DEFAULT NULL COMMENT 'Nombre del otro diagnostico',
  `diag_otro_medicamento` varchar(100) DEFAULT NULL COMMENT 'Nombre del otro medicamento',
  `noti_referencia_pareja` int(1) DEFAULT NULL COMMENT 'Referencia de pareja/contacto para tratamiento 1:si 2:no',
  `noti_medicamento1` varchar(100) DEFAULT NULL COMMENT 'Nombre del medicamento 1',
  `noti_medicamento2` varchar(100) DEFAULT NULL COMMENT 'Nombre del medicamento 2',
  `noti_medicamento3` varchar(100) DEFAULT NULL COMMENT 'Nombre del medicamento 3',
  `noti_preservativos` int(1) DEFAULT NULL COMMENT 'Preservativo entregados 1:si 2:no',
  `noti_preservativos_cuantos` int(10) DEFAULT NULL COMMENT 'Cantidad Preservativos',
  PRIMARY KEY (`id_vicits_form`),
  UNIQUE KEY `uk_vicits_form` (`id_tipo_identidad`,`numero_identificacion`,`semana_epi`,`anio`),
  KEY `fk_vicits_un` (`id_un`),
  KEY `fk_vicits_corregimiento` (`per_id_corregimiento`),
  KEY `fk_vicits_pais` (`per_id_pais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla principal del formulario de VICITS' AUTO_INCREMENT=1534 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_its`
--

DROP TABLE IF EXISTS `vicits_its`;
CREATE TABLE IF NOT EXISTS `vicits_its` (
  `id_vicits_ITS` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de VICITS con las ITS',
  `id_vicits_form` int(11) NOT NULL COMMENT 'Identificador del formulario de VICITS',
  `id_ITS` int(11) NOT NULL COMMENT 'Identificador unico de la ITS',
  PRIMARY KEY (`id_vicits_ITS`),
  UNIQUE KEY `uk_vicits_form_its` (`id_vicits_form`,`id_ITS`),
  KEY `fk_vicits_its` (`id_ITS`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS con las ITS' AUTO_INCREMENT=141 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_lab_muestra`
--

DROP TABLE IF EXISTS `vicits_lab_muestra`;
CREATE TABLE IF NOT EXISTS `vicits_lab_muestra` (
  `id_vicits_lab_muestra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de lab de VICITS con el tipo de muestra',
  `id_vicits_laboratorio` int(11) NOT NULL COMMENT 'Identificador del formulario de laboratorio de VICITS',
  `id_tipos_muestras` int(11) NOT NULL COMMENT 'Identificador unico del tipo de muestra',
  PRIMARY KEY (`id_vicits_lab_muestra`),
  UNIQUE KEY `uk_vicits_lab_muestra` (`id_vicits_laboratorio`,`id_tipos_muestras`),
  KEY `fk_vicits_lab_muestra` (`id_tipos_muestras`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS de laboratorio co' AUTO_INCREMENT=308 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_lab_prueba`
--

DROP TABLE IF EXISTS `vicits_lab_prueba`;
CREATE TABLE IF NOT EXISTS `vicits_lab_prueba` (
  `id_vicits_lab_prueba` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de lab de VICITS con la prueba solicitada',
  `id_vicits_laboratorio` int(11) NOT NULL COMMENT 'Identificador del formulario de laboratorio de VICITS',
  `id_prueba` int(11) NOT NULL COMMENT 'Identificador unico del tipo de muestra',
  PRIMARY KEY (`id_vicits_lab_prueba`),
  UNIQUE KEY `uk_vicits_lab_muestra` (`id_vicits_laboratorio`,`id_prueba`),
  KEY `fk_vicits_lab_prueba` (`id_prueba`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS de laboratorio co' AUTO_INCREMENT=310 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_laboratorio`
--

DROP TABLE IF EXISTS `vicits_laboratorio`;
CREATE TABLE IF NOT EXISTS `vicits_laboratorio` (
  `id_vicits_laboratorio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de las pruebas solicitadas para VICITS',
  `formulario_tipo_consulta` int(1) DEFAULT NULL COMMENT '1:nueva, 2: reconsulta, 3:desconoce',
  `formulario_fecha_consulta` date DEFAULT NULL COMMENT 'Fecha de consulta para el formulario de laboratorio VICITS',
  `id_tipo_identidad` int(11) DEFAULT NULL COMMENT 'Tipo de identificacion de la persona',
  `numero_identificacion` varchar(30) DEFAULT NULL COMMENT 'Numero de identificacion de la persona',
  `id_un` int(11) DEFAULT NULL COMMENT 'Código de la unidad notificadora',
  `formulario_pre_prueba` int(1) DEFAULT NULL COMMENT 'Recibio consejeria pre prueba 1:si, 2:no, 3:no sabe',
  `id_grupo_poblacion` int(11) NOT NULL COMMENT 'Identificador unico del grupo de poblacion para VICITS',
  `formulario_nombre_medico` varchar(100) NOT NULL COMMENT 'Nombre de la persona que solicito la prueba VICITS',
  `resultado_poliformos` int(1) DEFAULT NULL COMMENT 'Resultados poliformos nucleares',
  `resultados_celulas` int(1) DEFAULT NULL COMMENT 'Resultados celulas Epifeliales',
  `resultados_diplocco` int(1) DEFAULT NULL COMMENT 'Observacion de Diplocco Gram Neg Intracel',
  `resultados_levaduras` int(1) DEFAULT NULL COMMENT 'Observacion de Levaduras/Pseudohifas',
  `resultados_otros` int(1) DEFAULT NULL COMMENT 'Otros Resultados',
  `resultados_flora` int(1) DEFAULT NULL COMMENT 'Resultados flora vaginal',
  `resultados_exa_levaduras` int(1) DEFAULT NULL COMMENT 'Examen en fresco Observacion de levaduras',
  `resultados_exa_trichomonas` int(1) DEFAULT NULL COMMENT 'Examen en fresco Observacion de trichomonas',
  `resultados_exa_esperma` int(1) DEFAULT NULL COMMENT 'Examen en fresco Observacion de espermatozoides',
  `resultados_pcr_neisseria` int(1) DEFAULT NULL COMMENT 'Res. PCR/cultivo Neisseria Gonorrhoeae',
  `resultados_pcr_chlamydia` int(1) DEFAULT NULL COMMENT 'Res. PCR/cultivo Chlamydia Trachomatis',
  `resultados_pcr_lactamasa` int(1) DEFAULT NULL COMMENT 'Res. PCR/cultivo beta-lactamasa',
  `resultados_vdrl_titulacion` varchar(4) NOT NULL COMMENT 'Titulacion para el resultado de VDRL',
  `resultados_vdrl` int(1) DEFAULT NULL COMMENT 'Resultado para VDRL',
  `resultados_rpr_titulacion` varchar(4) NOT NULL COMMENT 'Titulacion para el resultado de RPR',
  `resultados_rpr` int(1) DEFAULT NULL COMMENT 'Resultado para RPR',
  `resultados_tp_titulacion` varchar(4) NOT NULL COMMENT 'Titulacion para el resultado de MHATP / TP-PA',
  `resultados_tp` int(1) DEFAULT NULL COMMENT 'Resultado para MHATP / TP-PA',
  `resultados_vih` int(1) DEFAULT NULL COMMENT 'Resultado para VIH',
  `resultados_pos_prueba` int(1) DEFAULT NULL COMMENT 'Recibio consejeria pos prueba VIH 1:si, 2:no, 3:no sabe',
  `resultados_referido_tarv` int(1) DEFAULT NULL COMMENT 'Referido a clinica TARV 1:si, 2:no, 3:no sabe',
  `status` int(1) DEFAULT NULL COMMENT '1:activo, 0: no activo',
  PRIMARY KEY (`id_vicits_laboratorio`),
  KEY `fk_vicits_lab_un` (`id_un`),
  KEY `fk_vicits_lab_persona` (`id_tipo_identidad`,`numero_identificacion`),
  KEY `fk_vicits_lab_grupo_poblacion` (`id_grupo_poblacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Formulario de VICITS para la solicitud de examenes' AUTO_INCREMENT=164 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_sintoma`
--

DROP TABLE IF EXISTS `vicits_sintoma`;
CREATE TABLE IF NOT EXISTS `vicits_sintoma` (
  `id_vicits_sintoma` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de VICITS con el signo y sintoma',
  `id_vicits_form` int(11) NOT NULL COMMENT 'Identificador del formulario de VICITS',
  `id_signo_sintoma` int(11) NOT NULL COMMENT 'Identificador unico del signo y sintoma',
  `dias` int(3) DEFAULT NULL COMMENT 'Numero de dias que lleva con el sintoma',
  PRIMARY KEY (`id_vicits_sintoma`),
  UNIQUE KEY `uk_vicits_sintoma` (`id_vicits_form`,`id_signo_sintoma`),
  KEY `fk_vicits_sintoma` (`id_signo_sintoma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS con los signos y ' AUTO_INCREMENT=195 ;

-- --------------------------------------------------------

--
-- Table structure for table `vicits_tratamiento`
--

DROP TABLE IF EXISTS `vicits_tratamiento`;
CREATE TABLE IF NOT EXISTS `vicits_tratamiento` (
  `id_vicits_tratamiento` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la union del formulario de VICITS con el diagnostico y tratamiento',
  `id_vicits_form` int(11) NOT NULL COMMENT 'Identificador del formulario de VICITS',
  `id_diag_sindromico` int(11) NOT NULL COMMENT 'Identificador unico dx sindromico',
  `id_diag_etiologico` int(11) NOT NULL COMMENT 'Identificador unico dx etiologico',
  `id_tratamiento` int(11) NOT NULL COMMENT 'Identificador unico del tratamiento',
  PRIMARY KEY (`id_vicits_tratamiento`),
  KEY `fk_vicits_diag_etiologico` (`id_diag_etiologico`),
  KEY `fk_vicits_diag_sindromico` (`id_diag_sindromico`),
  KEY `fk_vicits_tratamiento` (`id_tratamiento`),
  KEY `fk_vicits_tratamiento_form` (`id_vicits_form`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion del formulario de VICITS con los tratamien' AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_alerta_temprana`
--
DROP VIEW IF EXISTS `view_alerta_temprana`;
CREATE TABLE IF NOT EXISTS `view_alerta_temprana` (
`semana_epi` int(2)
,`anio` int(4)
,`DiaToma` int(2)
,`MesToma` int(2)
,`AnioToma` int(4)
,`id_diagnostico` int(11)
,`nombre_diagnostico` varchar(250)
,`id_nivel_geo1` int(11)
,`nombre_nivel_geo1` varchar(100)
,`id_nivel_geo2` int(11)
,`nombre_nivel_geo2` varchar(100)
,`id_nivel_geo3` int(11)
,`nombre_nivel_geo3` varchar(100)
,`id_nivel_geo4` int(11)
,`nombre_nivel_geo4` varchar(100)
,`id_establecimiento` int(11)
,`nombre_establecimiento` varchar(100)
,`numero_casos` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_eno`
--
DROP VIEW IF EXISTS `view_eno`;
CREATE TABLE IF NOT EXISTS `view_eno` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_un` varchar(100)
,`semana_epi` int(2)
,`anio` int(4)
,`servicio` int(4)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`sexo` varchar(1)
,`id_rango` int(10) unsigned
,`nombre_rango` varchar(45)
,`numero_casos` decimal(32,0)
,`id_region` int(11)
,`id_distrito` int(11)
,`id_corregimiento` int(11)
,`id_un` int(11)
,`sector_un` int(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_eno_matriz`
--
DROP VIEW IF EXISTS `view_eno_matriz`;
CREATE TABLE IF NOT EXISTS `view_eno_matriz` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_un` varchar(100)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`semana_epi` int(2)
,`anio` int(4)
,`servicio` int(4)
,`sexo` varchar(1)
,`id_rango` int(10) unsigned
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`id_un` int(11)
,`nombre_rango` varchar(45)
,`numero_casos` decimal(32,0)
,`sector_un` int(1)
,`menor_uno` varbinary(55)
,`uno_cuatro` varbinary(55)
,`cinco_nueve` varbinary(55)
,`nueve_catorce` varbinary(55)
,`quince_diecinueve` varbinary(55)
,`veinte_veinticuatro` varbinary(55)
,`veinticinco_treinticuatro` varbinary(55)
,`treinticinco_cuarentinueve` varbinary(55)
,`cincuenta_cincuentinueve` varbinary(55)
,`sesenta_sesenticuatro` varbinary(55)
,`mayor_sesenticinco` varbinary(55)
,`N_E` varbinary(55)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_eno_reporte`
--
DROP VIEW IF EXISTS `view_eno_reporte`;
CREATE TABLE IF NOT EXISTS `view_eno_reporte` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_un` varchar(100)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`semana_epi` int(2)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`id_un` int(11)
,`servicio` int(4)
,`anio` int(4)
,`sexo` varchar(1)
,`sector_un` int(1)
,`M<1` double
,`F<1` double
,`M1-4` double
,`F1-4` double
,`M5-9` double
,`F5-9` double
,`M9-14` double
,`F9-14` double
,`M15-19` double
,`F15-19` double
,`M20-24` double
,`F20-24` double
,`M25-34` double
,`F25-34` double
,`M35-49` double
,`F35-49` double
,`M50-59` double
,`F50-59` double
,`M60-64` double
,`F60-64` double
,`M>65` double
,`F>65` double
,`MNE` double
,`FNE` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_eno_reporte_MF`
--
DROP VIEW IF EXISTS `view_eno_reporte_MF`;
CREATE TABLE IF NOT EXISTS `view_eno_reporte_MF` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_un` varchar(100)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`semana_epi` int(2)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`id_un` int(11)
,`servicio` int(4)
,`anio` int(4)
,`sexo` varchar(1)
,`sector_un` int(1)
,`<1M` varbinary(23)
,`<1F` varbinary(23)
,`1-4M` varbinary(23)
,`1-4F` varbinary(23)
,`5-9M` varbinary(23)
,`5-9F` varbinary(23)
,`9-14M` varbinary(23)
,`9-14F` varbinary(23)
,`15-19M` varbinary(23)
,`15-19F` varbinary(23)
,`20-24M` varbinary(23)
,`20-24F` varbinary(23)
,`25-34M` varbinary(23)
,`25-34F` varbinary(23)
,`35-49M` varbinary(23)
,`35-49F` varbinary(23)
,`50-59M` varbinary(23)
,`50-59F` varbinary(23)
,`60-64M` varbinary(23)
,`60-64F` varbinary(23)
,`>65M` varbinary(23)
,`>65F` varbinary(23)
,`NEM` varbinary(23)
,`NEF` varbinary(23)
);
-- --------------------------------------------------------

--
-- Table structure for table `view_epivigila_export_cat_uni_notificadoras`
--

DROP TABLE IF EXISTS `view_epivigila_export_cat_uni_notificadoras`;
CREATE TABLE IF NOT EXISTS `view_epivigila_export_cat_uni_notificadoras` (
  `Cero` int(1) DEFAULT NULL,
  `id_un` int(11) DEFAULT NULL,
  `nombre_un` varchar(100) DEFAULT NULL,
  `cod_ref_minsa` varchar(10) DEFAULT NULL,
  `id_corregimiento` int(11) DEFAULT NULL,
  `nombre_corregimiento` varchar(100) DEFAULT NULL,
  `id_distrito` int(11) DEFAULT NULL,
  `nombre_distrito` varchar(100) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `view_epivigila_export_eno`
--

DROP TABLE IF EXISTS `view_epivigila_export_eno`;
CREATE TABLE IF NOT EXISTS `view_epivigila_export_eno` (
  `Cero` int(1) DEFAULT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `semana_epi` int(2) DEFAULT NULL,
  `anio_epi` int(4) DEFAULT NULL,
  `Notificador` int(11) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `Grupo_edad` int(10) unsigned DEFAULT NULL,
  `Casos` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `view_epivigila_export_eno_cie`
--

DROP TABLE IF EXISTS `view_epivigila_export_eno_cie`;
CREATE TABLE IF NOT EXISTS `view_epivigila_export_eno_cie` (
  `Cero` int(1) DEFAULT NULL,
  `cie_10_1` varchar(10) DEFAULT NULL,
  `semana_epi` int(2) DEFAULT NULL,
  `anio_epi` int(4) DEFAULT NULL,
  `Notificador` int(11) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `Grupo_edad` int(10) unsigned DEFAULT NULL,
  `Casos` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `view_epivigila_export_eno_evento`
--

DROP TABLE IF EXISTS `view_epivigila_export_eno_evento`;
CREATE TABLE IF NOT EXISTS `view_epivigila_export_eno_evento` (
  `Cero` int(1) DEFAULT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `semana_epi` int(2) DEFAULT NULL,
  `anio_epi` int(4) DEFAULT NULL,
  `Notificador` int(11) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `Grupo_edad` int(10) unsigned DEFAULT NULL,
  `Casos` int(11) DEFAULT NULL,
  `nombre_evento` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_evento_egreso`
--
DROP VIEW IF EXISTS `view_evento_egreso`;
CREATE TABLE IF NOT EXISTS `view_evento_egreso` (
`id_rae` int(11)
,`id_evento` int(4)
,`ciex` varchar(10)
,`nombre_evento` varchar(250)
,`id_un` int(11)
,`cod_ref_minsa` varchar(10)
,`nombre_un` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`nombre_personal_medico` varchar(60)
,`nombre_funcionario` varchar(150)
,`nombre_registra` varchar(60)
,`fecha_cierre` date
,`fecha_admision` date
,`fecha_egreso` date
,`tipo_identificacion` int(11)
,`nombre_tipo` varchar(100)
,`numero_identificacion` varchar(30)
,`per_edad` int(11)
,`nombre` varchar(10)
,`per_tipo_edad` int(11)
,`sexo` varchar(1)
,`nombre_ocupacion` varchar(45)
,`per_id_pais` int(11)
,`nombre_pais` varchar(30)
,`per_id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`per_direccion` varchar(100)
,`per_dir_referencia` varchar(150)
,`per_id_corregimiento_transitoria` int(11)
,`id_corregimiento` int(11)
,`per_no_hay_dir_transitoria` int(11)
,`id_tipo_paciente` int(11)
,`nombre_tipo_paciente` varchar(60)
,`hospitalizacion` int(1)
,`id_condicion_salida` int(4)
,`nombre_condicion_salida` varchar(45)
,`motivo_salida` int(1)
,`muerte_sop` int(1)
,`autopsia` int(1)
,`fecha_autopsia` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_flu_analysis`
--
DROP VIEW IF EXISTS `view_flu_analysis`;
CREATE TABLE IF NOT EXISTS `view_flu_analysis` (
`id_formulario` int(11)
,`id_un` varbinary(11)
,`unidad_notificadora` varchar(100)
,`nombre_registra` varchar(45)
,`nombre_investigador` varchar(45)
,`fecha_formulario` varchar(10)
,`tipo_identificacion` int(11)
,`numero_identificacion` varchar(30)
,`per_tipo_paciente` int(1)
,`per_hospitalizado` int(1)
,`per_hospitalizado_lugar` varbinary(11)
,`per_asegurado` int(1)
,`primer_nombre` varchar(45)
,`segundo_nombre` varchar(45)
,`primer_apellido` varchar(45)
,`segundo_apellido` varchar(45)
,`fecha_nacimiento` varchar(10)
,`edad` int(3)
,`tipo_edad` int(1)
,`sexo` varchar(1)
,`nombre_responsable` varchar(60)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`dir_referencia` varchar(150)
,`dir_trabajo` varchar(100)
,`otra_direccion` varchar(20)
,`vac_tarjeta` int(1)
,`vac_segun_esquema` int(1)
,`vac_fecha_anio_previo` varchar(45)
,`vac_fecha_pen_ultima_dosis` varchar(45)
,`riesgo_embarazo` varbinary(11)
,`riesgo_trimestre` varbinary(11)
,`riesgo_enf_cronica` int(1)
,`riesgo_profesional` int(1)
,`riesgo_pro_cual` varbinary(11)
,`riesgo_viaje` int(1)
,`riesgo_viaje_donde` varchar(150)
,`riesgo_contacto_confirmado` int(1)
,`riesgo_contacto_tipo` varbinary(11)
,`riesgo_aislamiento` int(1)
,`riesgo_contacto_nombre` varchar(150)
,`id_tipo_evento` varchar(8)
,`tipo_evento` varchar(19)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`fecha_inicio_sintoma` varchar(10)
,`fecha_hospitalizacion` varchar(10)
,`fecha_notificacion` varchar(10)
,`fecha_egreso` varchar(10)
,`fecha_defuncion` varchar(10)
,`antibiotico` int(1)
,`antibiotico_cual` varchar(150)
,`antibiotico_fecha` varchar(10)
,`antiviral` int(1)
,`antiviral_cual` varchar(150)
,`antiviral_fecha` varchar(10)
,`sintoma_fiebre` int(1)
,`fecha_fiebre` varchar(10)
,`sintoma_tos` int(1)
,`fecha_tos` varchar(10)
,`sintoma_garganta` int(1)
,`fecha_garganta` varchar(10)
,`sintoma_rinorrea` int(1)
,`fecha_rinorrea` varchar(10)
,`sintoma_respiratoria` int(1)
,`fecha_respiratoria` varchar(10)
,`sintoma_otro` int(1)
,`fecha_otro` varchar(10)
,`sintoma_nombre_otro` varchar(150)
,`torax_condensacion` int(1)
,`torax_derrame` int(1)
,`torax_broncograma` int(1)
,`torax_infiltrado` int(1)
,`torax_otro` int(1)
,`torax_nombre_otro` varchar(150)
,`semana_epi` int(3)
,`anio` int(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_flureg`
--
DROP VIEW IF EXISTS `view_flureg`;
CREATE TABLE IF NOT EXISTS `view_flureg` (
`id_formulario` int(11)
,`id_un` varbinary(11)
,`unidad_notificadora` varchar(100)
,`nombre_registra` varchar(45)
,`nombre_investigador` varchar(45)
,`fecha_formulario` varchar(10)
,`tipo_identificacion` int(11)
,`numero_identificacion` varchar(30)
,`per_tipo_paciente` int(1)
,`per_hospitalizado` int(1)
,`per_hospitalizado_lugar` varbinary(11)
,`per_asegurado` int(1)
,`primer_nombre` varchar(45)
,`segundo_nombre` varchar(45)
,`primer_apellido` varchar(45)
,`segundo_apellido` varchar(45)
,`fecha_nacimiento` varchar(10)
,`edad` int(3)
,`tipo_edad` int(1)
,`sexo` varchar(1)
,`nombre_responsable` varchar(60)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`dir_referencia` varchar(150)
,`dir_trabajo` varchar(100)
,`otra_direccion` varchar(20)
,`vac_tarjeta` int(1)
,`vac_segun_esquema` int(1)
,`vac_fecha_anio_previo` varchar(45)
,`vac_fecha_pen_ultima_dosis` varchar(45)
,`riesgo_embarazo` varbinary(11)
,`riesgo_trimestre` varbinary(11)
,`riesgo_enf_cronica` int(1)
,`riesgo_profesional` int(1)
,`riesgo_pro_cual` varbinary(11)
,`riesgo_viaje` int(1)
,`riesgo_viaje_donde` varchar(150)
,`riesgo_contacto_confirmado` int(1)
,`riesgo_contacto_tipo` varbinary(11)
,`riesgo_aislamiento` int(1)
,`riesgo_contacto_nombre` varchar(150)
,`id_tipo_evento` varchar(8)
,`tipo_evento` varchar(19)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`fecha_inicio_sintoma` varchar(10)
,`fecha_hospitalizacion` varchar(10)
,`fecha_notificacion` varchar(10)
,`fecha_egreso` varchar(10)
,`fecha_defuncion` varchar(10)
,`antibiotico` int(1)
,`antibiotico_cual` varchar(150)
,`antibiotico_fecha` varchar(10)
,`antiviral` int(1)
,`antiviral_cual` varchar(150)
,`antiviral_fecha` varchar(10)
,`sintoma_fiebre` int(1)
,`fecha_fiebre` varchar(10)
,`sintoma_tos` int(1)
,`fecha_tos` varchar(10)
,`sintoma_garganta` int(1)
,`fecha_garganta` varchar(10)
,`sintoma_rinorrea` int(1)
,`fecha_rinorrea` varchar(10)
,`sintoma_respiratoria` int(1)
,`fecha_respiratoria` varchar(10)
,`sintoma_otro` int(1)
,`fecha_otro` varchar(10)
,`sintoma_nombre_otro` varchar(150)
,`torax_condensacion` int(1)
,`torax_derrame` int(1)
,`torax_broncograma` int(1)
,`torax_infiltrado` int(1)
,`torax_otro` int(1)
,`torax_nombre_otro` varchar(150)
,`semana_epi` int(3)
,`anio` int(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_notic`
--
DROP VIEW IF EXISTS `view_notic`;
CREATE TABLE IF NOT EXISTS `view_notic` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_unidad` varchar(100)
,`cie_10` varchar(10)
,`nombre_evento` varchar(250)
,`id_gevento` int(11)
,`id_region` int(11)
,`id_distrito` int(11)
,`id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`sexo` varchar(1)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`semana` int(3)
,`anio` varchar(4)
,`primer_nombre` varchar(45)
,`edad` int(11)
,`tipo_edad` int(11)
,`rango` varchar(6)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_notic_casos`
--
DROP VIEW IF EXISTS `view_notic_casos`;
CREATE TABLE IF NOT EXISTS `view_notic_casos` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_unidad` varchar(100)
,`cie_10` varchar(10)
,`id_gevento` int(11)
,`nombre_evento` varchar(250)
,`sexo` varchar(1)
,`semana` int(3)
,`anio` varchar(4)
,`rango` varchar(6)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`casos` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_notic_matriz`
--
DROP VIEW IF EXISTS `view_notic_matriz`;
CREATE TABLE IF NOT EXISTS `view_notic_matriz` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`sector_un` int(1)
,`id_evento` int(11)
,`cie_10` varchar(10)
,`nombre_evento` varchar(250)
,`id_gevento` int(11)
,`anio` int(4)
,`semana_epi` int(3)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`m_01` decimal(23,0)
,`m_14` decimal(23,0)
,`m_59` decimal(23,0)
,`m_1014` decimal(23,0)
,`m_1519` decimal(23,0)
,`m_2024` decimal(23,0)
,`m_2534` decimal(23,0)
,`m_3549` decimal(23,0)
,`m_5059` decimal(23,0)
,`m_6064` decimal(23,0)
,`m_65mas` decimal(23,0)
,`f_01` decimal(23,0)
,`f_14` decimal(23,0)
,`f_59` decimal(23,0)
,`f_1014` decimal(23,0)
,`f_1519` decimal(23,0)
,`f_2024` decimal(23,0)
,`f_2534` decimal(23,0)
,`f_3549` decimal(23,0)
,`f_5059` decimal(23,0)
,`f_6064` decimal(23,0)
,`f_65mas` decimal(23,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_notic_rangos`
--
DROP VIEW IF EXISTS `view_notic_rangos`;
CREATE TABLE IF NOT EXISTS `view_notic_rangos` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_unidad` varchar(100)
,`cie_10` varchar(10)
,`id_gevento` int(11)
,`nombre_evento` varchar(250)
,`sexo` varchar(1)
,`semana` int(3)
,`anio` varchar(4)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`numero_casos` decimal(41,0)
,`menor_uno` varbinary(42)
,`uno_cuatro` varbinary(42)
,`cinco_nueve` varbinary(42)
,`diez_catorce` varbinary(42)
,`quince_diecinueve` varbinary(42)
,`veinte_veinticuatro` varbinary(42)
,`veinticinco_treinticuatro` varbinary(42)
,`treinticinco_cuarentinueve` varbinary(42)
,`cincuenta_cincuentinueve` varbinary(42)
,`sesenta_sesenticuatro` varbinary(42)
,`mayor_sesenticinco` varbinary(42)
,`NE` varbinary(42)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_notic_reporte`
--
DROP VIEW IF EXISTS `view_notic_reporte`;
CREATE TABLE IF NOT EXISTS `view_notic_reporte` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_unidad` int(11)
,`nombre_unidad` varchar(100)
,`sector_un` int(1)
,`id_evento` int(11)
,`cie_10` varchar(10)
,`nombre_evento` varchar(250)
,`id_gevento` int(11)
,`anio` int(4)
,`semana` int(3)
,`per_id_corregimiento` int(11)
,`per_id_pais` int(11)
,`M<1` decimal(45,0)
,`M1-4` decimal(45,0)
,`M5-9` decimal(45,0)
,`M10-14` decimal(45,0)
,`M15-19` decimal(45,0)
,`M20-24` decimal(45,0)
,`M25-34` decimal(45,0)
,`M35-49` decimal(45,0)
,`M50-59` decimal(45,0)
,`M60-64` decimal(45,0)
,`M>65` decimal(45,0)
,`F<1` decimal(45,0)
,`F1-4` decimal(45,0)
,`F5-9` decimal(45,0)
,`F10-14` decimal(45,0)
,`F15-19` decimal(45,0)
,`F20-24` decimal(45,0)
,`F25-34` decimal(45,0)
,`F35-49` decimal(45,0)
,`F50-59` decimal(45,0)
,`F60-64` decimal(45,0)
,`F>65` decimal(45,0)
,`FNE` int(1)
,`MNE` int(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_notic_sexo`
--
DROP VIEW IF EXISTS `view_notic_sexo`;
CREATE TABLE IF NOT EXISTS `view_notic_sexo` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_evento` varchar(250)
,`id_gevento` int(11)
,`nombre_unidad` varchar(100)
,`cie_10` varchar(10)
,`semana` int(3)
,`anio` varchar(4)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`menor_unoM` varbinary(23)
,`menor_unoF` varbinary(23)
,`uno_cuatroM` varbinary(23)
,`uno_cuatroF` varbinary(23)
,`cinco_nueveM` varbinary(23)
,`cinco_nueveF` varbinary(23)
,`diez_catorceM` varbinary(23)
,`diez_catorceF` varbinary(23)
,`quince_diecinueveM` varbinary(23)
,`quince_diecinueveF` varbinary(23)
,`veinte_veinticuatroM` varbinary(23)
,`veinte_veinticuatroF` varbinary(23)
,`veinticinco_treinticuatroM` varbinary(23)
,`veinticinco_treinticuatroF` varbinary(23)
,`treinticinco_cuarentinueveM` varbinary(23)
,`treinticinco_cuarentinueveF` varbinary(23)
,`cincuenta_cincuentinueveM` varbinary(23)
,`cincuenta_cincuentinueveF` varbinary(23)
,`sesenta_sesenticuatroM` varbinary(23)
,`sesenta_sesenticuatroF` varbinary(23)
,`mayor_sesenticincoM` varbinary(23)
,`mayor_sesenticincoF` varbinary(23)
,`NEM` varbinary(23)
,`NEF` varbinary(23)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae`
--
DROP VIEW IF EXISTS `view_rae`;
CREATE TABLE IF NOT EXISTS `view_rae` (
`id_rae` int(11)
,`id_un` int(11)
,`cod_ref_minsa` varchar(10)
,`nombre_un` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`referido_de` int(1)
,`referido_otro_id_un` int(11)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`id_personal_medico` int(11)
,`nombre_personal_medico` varchar(60)
,`nombre_funcionario` varchar(150)
,`nombre_registra` varchar(60)
,`institucion_registra` varchar(45)
,`fecha_cierre` date
,`fecha_admision` date
,`fecha_egreso` date
,`tipo_identificacion` int(11)
,`nombre_tipo` varchar(100)
,`numero_identificacion` varchar(30)
,`per_edad` int(11)
,`nombre` varchar(10)
,`per_tipo_edad` int(11)
,`sexo` varchar(1)
,`per_id_pais` int(11)
,`nombre_pais` varchar(30)
,`per_id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`per_direccion` varchar(100)
,`per_dir_referencia` varchar(150)
,`per_id_corregimiento_transitoria` int(11)
,`id_corregimiento` int(11)
,`per_no_hay_dir_transitoria` int(11)
,`id_tipo_paciente` int(11)
,`nombre_tipo_paciente` varchar(60)
,`id_diagnostico1` int(11)
,`cod1` varchar(10)
,`nom1` varchar(250)
,`estado_diag1` int(1)
,`id_diagnostico2` int(11)
,`cod2` varchar(10)
,`nom2` varchar(250)
,`estado_diag2` int(1)
,`id_diagnostico3` int(11)
,`cod3` varchar(10)
,`nom3` varchar(250)
,`estado_diag3` int(1)
,`hospitalizacion` int(1)
,`id_condicion_salida` int(4)
,`nombre_condicion_salida` varchar(45)
,`motivo_salida` int(1)
,`muerte_sop` int(1)
,`autopsia` int(1)
,`fecha_autopsia` date
,`referido_a` int(1)
,`referido_a_otro` varchar(60)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_condicion_reporte`
--
DROP VIEW IF EXISTS `view_rae_condicion_reporte`;
CREATE TABLE IF NOT EXISTS `view_rae_condicion_reporte` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`curado` decimal(45,0)
,`mejorado` decimal(45,0)
,`igual` decimal(45,0)
,`peor` decimal(45,0)
,`En Estudio` decimal(45,0)
,`Muerto antes de 48 hrs.` decimal(45,0)
,`Muerto despues de 48 hrs.` decimal(45,0)
,`Fugado` decimal(45,0)
,`No Registrado` decimal(45,0)
,`Muerte en SOP` decimal(45,0)
,`Autopsia` decimal(45,0)
,`Procedimiento Qx` decimal(45,0)
,`total` decimal(53,0)
,`fecha_admision` varchar(10)
,`mes` varchar(2)
,`anio` varchar(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_eventos`
--
DROP VIEW IF EXISTS `view_rae_eventos`;
CREATE TABLE IF NOT EXISTS `view_rae_eventos` (
`id_rae` int(11)
,`id_evento` int(4)
,`ciex` varchar(10)
,`nombre_evento` varchar(250)
,`id_un` int(11)
,`cod_ref_minsa` varchar(10)
,`nombre_un` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`nombre_personal_medico` varchar(60)
,`nombre_funcionario` varchar(150)
,`nombre_registra` varchar(60)
,`fecha_cierre` date
,`fecha_admision` date
,`fecha_egreso` date
,`tipo_identificacion` int(11)
,`nombre_tipo` varchar(100)
,`numero_identificacion` varchar(30)
,`per_edad` int(11)
,`nombre` varchar(10)
,`per_tipo_edad` int(11)
,`sexo` varchar(1)
,`per_id_pais` int(11)
,`nombre_pais` varchar(30)
,`per_id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`per_direccion` varchar(100)
,`per_dir_referencia` varchar(150)
,`per_id_corregimiento_transitoria` int(11)
,`id_corregimiento` int(11)
,`per_no_hay_dir_transitoria` int(11)
,`id_tipo_paciente` int(11)
,`nombre_tipo_paciente` varchar(60)
,`hospitalizacion` int(1)
,`id_condicion_salida` int(4)
,`nombre_condicion_salida` varchar(45)
,`motivo_salida` int(1)
,`muerte_sop` int(1)
,`autopsia` int(1)
,`fecha_autopsia` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_eventos2`
--
DROP VIEW IF EXISTS `view_rae_eventos2`;
CREATE TABLE IF NOT EXISTS `view_rae_eventos2` (
`id_region` int(11)
,`nombre_region` varchar(100)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`per_dedad` int(11)
,`nombre_gevento` varchar(100)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`menor_uno_m` decimal(23,0)
,`uno_cuatro_m` decimal(23,0)
,`cinco_nueve_m` decimal(23,0)
,`diez_catorce_m` decimal(23,0)
,`quince_diecinueve_m` decimal(23,0)
,`veinte_veinticuatro_m` decimal(23,0)
,`veinticinco_treitaycuatro_m` decimal(23,0)
,`treintaycinco_cuarentaynueve_m` decimal(23,0)
,`cincuenta_cincuentaynueve_m` decimal(23,0)
,`sesenta_sesentaycuantro_m` decimal(23,0)
,`mas_sesentaycinco_m` decimal(23,0)
,`menor_uno_f` decimal(23,0)
,`uno_cuatro_f` decimal(23,0)
,`cinco_nueve_f` decimal(23,0)
,`diez_catorce_f` decimal(23,0)
,`quince_diecinueve_f` decimal(23,0)
,`veinte_veinticuatro_f` decimal(23,0)
,`veinticinco_treitaycuatro_f` decimal(23,0)
,`treintaycinco_cuarentaynueve_f` decimal(23,0)
,`cincuenta_cincuentaynueve_f` decimal(23,0)
,`sesenta_sesentaycuantro_f` decimal(23,0)
,`mas_sesentaycinco_f` decimal(23,0)
,`fecha_admision` varchar(10)
,`mes` varchar(2)
,`anio` varchar(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_eventos_reporte`
--
DROP VIEW IF EXISTS `view_rae_eventos_reporte`;
CREATE TABLE IF NOT EXISTS `view_rae_eventos_reporte` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`nombre_gevento` varchar(100)
,`cie_10_1` varchar(10)
,`nombre_evento` varchar(250)
,`menor_uno_m` decimal(45,0)
,`uno_cuatro_m` decimal(45,0)
,`cinco_nueve_m` decimal(45,0)
,`diez_catorce_m` decimal(45,0)
,`quince_diecinueve_m` decimal(45,0)
,`veinte_veinticuatro_m` decimal(45,0)
,`veinticinco_treitaycuatro_m` decimal(45,0)
,`treintaycinco_cuarentaynueve_m` decimal(45,0)
,`cincuenta_cincuentaynueve_m` decimal(45,0)
,`sesenta_sesentaycuantro_m` decimal(45,0)
,`mas_sesentaycinco_m` decimal(45,0)
,`menor_uno_f` decimal(45,0)
,`uno_cuatro_f` decimal(45,0)
,`cinco_nueve_f` decimal(45,0)
,`diez_catorce_f` decimal(45,0)
,`quince_diecinueve_f` decimal(45,0)
,`veinte_veinticuatro_f` decimal(45,0)
,`veinticinco_treitaycuatro_f` decimal(45,0)
,`treintaycinco_cuarentaynueve_f` decimal(45,0)
,`cincuenta_cincuentaynueve_f` decimal(45,0)
,`sesenta_sesentaycuantro_f` decimal(45,0)
,`mas_sesentaycinco_f` decimal(45,0)
,`fecha_admision` varchar(10)
,`mes` varchar(2)
,`anio` varchar(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_paciente_matriz`
--
DROP VIEW IF EXISTS `view_rae_paciente_matriz`;
CREATE TABLE IF NOT EXISTS `view_rae_paciente_matriz` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`total` int(7)
,`total_N` bigint(21)
,`uno` decimal(28,0)
,`uno_N` decimal(23,0)
,`dos` decimal(28,0)
,`dos_N` decimal(23,0)
,`tres` decimal(28,0)
,`tres_N` decimal(23,0)
,`cuatro` decimal(28,0)
,`cuatro_N` decimal(23,0)
,`cinco` decimal(28,0)
,`cinco_N` decimal(23,0)
,`seis` decimal(28,0)
,`seis_N` decimal(23,0)
,`siete` decimal(28,0)
,`siete_N` decimal(23,0)
,`ocho` decimal(28,0)
,`ocho_N` decimal(23,0)
,`nueve` decimal(28,0)
,`nueve_N` decimal(23,0)
,`diez` decimal(28,0)
,`diez_N` decimal(23,0)
,`once` decimal(28,0)
,`once_N` decimal(23,0)
,`doce` decimal(28,0)
,`doce_N` decimal(23,0)
,`total_condicion` bigint(21)
,`Curado` decimal(23,0)
,`Mejorado` decimal(23,0)
,`Igual` decimal(23,0)
,`Peor` decimal(23,0)
,`En Estudio` decimal(23,0)
,`Muerto antes de 48 hrs.` decimal(23,0)
,`Muerto despues de 48 hrs.` decimal(23,0)
,`Fugado` decimal(23,0)
,`No Registrado` decimal(23,0)
,`Muerte en SOP` decimal(23,0)
,`Autopsia` decimal(23,0)
,`Procedimiento Qx` decimal(23,0)
,`fecha_admision` varchar(10)
,`mes` varchar(2)
,`anio` varchar(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_paciente_reporte`
--
DROP VIEW IF EXISTS `view_rae_paciente_reporte`;
CREATE TABLE IF NOT EXISTS `view_rae_paciente_reporte` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`id_servicio` int(4)
,`codigo_servicio` varchar(45)
,`nombre_servicio` varchar(45)
,`uno` decimal(50,0)
,`uno_N` decimal(45,0)
,`dos` decimal(50,0)
,`dos_N` decimal(45,0)
,`tres` decimal(50,0)
,`tres_N` decimal(45,0)
,`cuatro` decimal(50,0)
,`cuatro_N` decimal(45,0)
,`cinco` decimal(50,0)
,`cinco_N` decimal(45,0)
,`seis` decimal(50,0)
,`seis_N` decimal(45,0)
,`siete` decimal(50,0)
,`siete_N` decimal(45,0)
,`ocho` decimal(50,0)
,`ocho_N` decimal(45,0)
,`nueve` decimal(50,0)
,`nueve_N` decimal(45,0)
,`diez` decimal(50,0)
,`diez_N` decimal(45,0)
,`once` decimal(50,0)
,`once_N` decimal(45,0)
,`doce` decimal(50,0)
,`doce_N` decimal(45,0)
,`total_asegurados` decimal(59,0)
,`total_asegurados_N` decimal(54,0)
,`fecha_admision` varchar(10)
,`mes` varchar(2)
,`anio` varchar(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rae_procedimiento`
--
DROP VIEW IF EXISTS `view_rae_procedimiento`;
CREATE TABLE IF NOT EXISTS `view_rae_procedimiento` (
`id_rae` int(11)
,`id_procedimiento` int(4)
,`codigo_procedimiento` varchar(11)
,`nombre_procedimiento` varchar(45)
,`tipo_procedimiento` int(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_tb_reporte`
--
DROP VIEW IF EXISTS `view_tb_reporte`;
CREATE TABLE IF NOT EXISTS `view_tb_reporte` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`fecha_notificacion` date
,`trat_fecha_inicio_tratF1` date
,`fem_rango_uno` int(0)
,`fem_rango_dos` int(0)
,`fem_rango_tres` int(0)
,`fem_rango_cuatro` int(0)
,`fem_rango_cinco` int(0)
,`fem_rango_seis` int(0)
,`fem_rango_siete` int(0)
,`fem_rango_ocho` int(0)
,`fem_rango_nueve` int(0)
,`fem_rango_diez` int(0)
,`fem_rango_once` int(0)
,`mas_rango_uno` int(0)
,`mas_rango_dos` int(0)
,`mas_rango_tres` int(0)
,`mas_rango_cuatro` int(0)
,`mas_rango_cinco` int(0)
,`mas_rango_seis` int(0)
,`mas_rango_siete` int(0)
,`mas_rango_ocho` int(0)
,`mas_rango_nueve` int(0)
,`mas_rango_diez` int(0)
,`mas_rango_once` int(0)
,`TBC_pulmonar_BKpos` int(0)
,`TBC_pulmonar_BKneg` int(0)
,`TBC_pulmonar_sin_BK` int(0)
,`TBC_extrapulmonar` int(0)
,`TBC_extrapulmonar_old` int(0)
,`BKpos_recaidas` int(0)
,`BKpos_fracaso` int(0)
,`BKpos_perd_sgto` int(0)
,`BKpos_otros` int(0)
,`BKneg_recaidas` int(0)
,`BKneg_fracaso` int(0)
,`BKneg_perd_sgto` int(0)
,`BKneg_otros` int(0)
,`sinBK_recaidas` int(0)
,`sinBK_fracaso` int(0)
,`sinBK_perd_sgto` int(0)
,`sinBK_otros` int(0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_tb_sabana`
--
DROP VIEW IF EXISTS `view_tb_sabana`;
CREATE TABLE IF NOT EXISTS `view_tb_sabana` (
`id_tb` int(11)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`unidad_disponible` varchar(1)
,`nombre_investigador` varchar(45)
,`fecha_formulario` varchar(10)
,`fechafor_notificacion` varchar(10)
,`fecha_notificacion` date
,`nombre_registra` varchar(45)
,`tipo_identificacion` int(11)
,`nombre_tipo` varchar(100)
,`numero_identificacion` varchar(30)
,`primer_nombre` varchar(45)
,`segundo_nombre` varchar(45)
,`primer_apellido` varchar(45)
,`segundo_apellido` varchar(45)
,`casada_apellido` varchar(45)
,`sexo` varchar(1)
,`per_sexo` varchar(6)
,`fecha_nacimiento` varchar(10)
,`per_edad` int(11)
,`per_id_tipo_edad` int(11)
,`per_tipo_edad` varchar(5)
,`riesgo_embarazo` int(1)
,`riesgo_semana` int(1)
,`id_profesion` int(11)
,`nombre_ocupacion` varchar(45)
,`per_id_pais` int(11)
,`per_nombre_pais` varchar(30)
,`per_id_provincia` int(11)
,`per_nombre_provincia` varchar(100)
,`per_id_distrito` int(11)
,`per_nombre_distrio` varchar(100)
,`per_id_corregimiento` int(11)
,`per_nombre_corregimiento` varchar(100)
,`per_direccion` varchar(100)
,`per_telefono` int(20)
,`id_grupo_poblacion` int(11)
,`nombre_grupo_poblacion` varchar(100)
,`per_empleado` int(1)
,`id_estado_civil` int(1)
,`id_escolaridad` int(1)
,`per_nombre_referencia` varchar(150)
,`per_parentesco` varchar(75)
,`per_telefono_referencia` int(20)
,`per_antes_preso` int(11)
,`ant_diabetes` int(1)
,`ant_preso` int(1)
,`ant_fecha_preso` varchar(8)
,`ant_drug` int(1)
,`ant_alcoholism` int(1)
,`ant_smoking` int(1)
,`ant_mining` int(1)
,`ant_overcrowding` int(1)
,`ant_indigence` int(1)
,`ant_drinkable` int(1)
,`ant_sanitation` int(1)
,`ant_contactposi` int(1)
,`ant_BCG` int(1)
,`ant_weight_kg` int(11)
,`ant_height` double
,`mat_diag_fecha_BK1` varchar(10)
,`mat_diag_resultado_BK1` int(1)
,`id_clasificacion_BK1` int(11)
,`mat_diag_fecha_BK2` varchar(10)
,`mat_diag_resultado_BK2` int(1)
,`id_clasificacion_BK2` int(11)
,`mat_diag_fecha_BK3` varchar(10)
,`mat_diag_resultado_BK3` int(1)
,`id_clasificacion_BK3` int(11)
,`mat_diag_res_cultivo` varchar(36)
,`mat_diag_fecha_res_cultivo` varchar(10)
,`mat_diag_metodo_WRD` int(1)
,`mat_diag_res_metodo_WRD` int(1)
,`mat_diag_fecha_res_WRD` varchar(10)
,`mat_diag_res_clinico` int(1)
,`mat_diag_fecha_clinico` varchar(10)
,`mat_diag_res_R_X` int(1)
,`mat_diag_fecha_R_X` varchar(10)
,`mat_diag_res_histopa` int(1)
,`mat_diag_fecha_histopa` varchar(10)
,`clasificacion_tb` int(11)
,`clas_pulmonar_EP` int(11)
,`clas_lugar_EP` int(11)
,`clas_trat_previo` int(11)
,`clas_recaida` int(11)
,`clas_postfracaso` int(11)
,`clas_perdsegui` int(11)
,`clas_otros_antestratado` int(11)
,`clas_diag_VIH` int(11)
,`clas_fecha_diag_VIH` varchar(10)
,`clas_met_diag` int(11)
,`clas_esp_MonoR` int(11)
,`clas_PoliR_H` int(11)
,`clas_PoliR_R` int(11)
,`clas_PoliR_Z` int(11)
,`clas_PoliR_E` int(11)
,`clas_PoliR_S` int(11)
,`clas_PoliR_fluoroquinolonas` int(11)
,`clas_PoliR_2linea` int(11)
,`clas_id_fluoroquinolonas` int(11)
,`clas_id_2linea` int(11)
,`trat_referido` int(11)
,`trat_inst_salud_ref` int(11)
,`trat_nombre_inst_ref` varchar(100)
,`trat_fechafor_inicio_tratF1` varchar(10)
,`trat_fecha_inicio_tratF1` date
,`trat_med_H_F1` int(1)
,`trat_med_R_F1` int(1)
,`trat_med_Z_F1` int(1)
,`trat_med_E_F1` int(1)
,`trat_med_S_F1` int(1)
,`trat_med_otros_F1` int(1)
,`trat_fecha_fin_tratF1` varchar(10)
,`id_adm_tratamiento_F1` int(1)
,`trat_fecha_inicio_tratF2` varchar(10)
,`trat_med_H_F2` int(1)
,`trat_med_R_F2` int(1)
,`trat_med_E_F2` int(1)
,`trat_med_otros_F2` int(1)
,`trat_fecha_fin_tratF2` varchar(10)
,`id_adm_tratamiento_F2` int(1)
,`TB_VIH_fecha_prueba_VIH` varchar(10)
,`TB_VIH_res_previa_VIH` int(1)
,`TB_VIH_aseso_VIH` int(1)
,`TB_VIH_cotrimoxazol` int(1)
,`TB_VIH_fecha_cotrimoxazol` varchar(10)
,`TB_VIH_act_TARV` int(1)
,`TB_VIH_fecha_inicio_TARV` varchar(10)
,`TB_VIH_lug_adm_TARV` varchar(150)
,`TB_VIH_nombre_un_TARV` varchar(100)
,`TB_VIH_isoniacida` int(1)
,`contacto_identificados_5min` int(11)
,`contacto_sinto_resp_5min` int(11)
,`contacto_evaluados_5min` int(11)
,`contacto_quimioprofilaxis_5min` int(11)
,`contacto_TB_5min` int(11)
,`contacto_identificados_5pl` int(11)
,`contacto_sinto_resp_5pl` int(11)
,`contacto_evaluados_5pl` int(11)
,`contacto_quimioprofilaxis_5pl` int(11)
,`contacto_TB_5pl` int(11)
,`apoyo_social` int(11)
,`apoyo_nutricional` int(11)
,`apoyo_economico` int(11)
,`egreso_fecha_egreso` date
,`egreso_cond_egreso` int(11)
,`egreso_motivo_exclusion` int(11)
,`anio` int(4)
,`semana_epi` int(3)
,`nombre_toma_muestra` varchar(150)
,`pendiente_uceti` int(1)
,`pendiente_silab` int(1)
,`actualizacion_silab` timestamp
,`source_entry` int(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_tb_sabana_excel`
--
DROP VIEW IF EXISTS `view_tb_sabana_excel`;
CREATE TABLE IF NOT EXISTS `view_tb_sabana_excel` (
`id_tb` int(11)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`anio` int(4)
,`semana_epi` int(3)
,`nombre_investigador` varchar(45)
,`fecha_formulario` varchar(10)
,`fecha_noti` date
,`fecha_notificacion` varchar(10)
,`nombre_registra` varchar(45)
,`nombre_tipo` varchar(100)
,`numero_identificacion` varchar(30)
,`primer_nombre` varchar(45)
,`segundo_nombre` varchar(45)
,`primer_apellido` varchar(45)
,`segundo_apellido` varchar(45)
,`casada_apellido` varchar(45)
,`fecha_nacimiento` varchar(10)
,`per_tipo_edad` varchar(5)
,`per_edad` int(11)
,`per_sexo` varchar(6)
,`riesgo_embarazo` varchar(7)
,`riesgo_semana` int(1)
,`nombre_gpopoblacional` varchar(45)
,`nombre_etnia` varchar(100)
,`per_empleado` varchar(7)
,`nombre_profesion` varchar(45)
,`id_estado_civil` varchar(10)
,`id_escolaridad` varchar(11)
,`per_direccion` varchar(100)
,`per_telefono` int(20)
,`per_nombre_referencia` varchar(150)
,`per_parentesco` varchar(75)
,`per_telefono_referencia` int(20)
,`per_antes_preso` varchar(7)
,`per_fecha_antespreso` varchar(8)
,`ant_diabetes` varchar(7)
,`ant_preso` varchar(7)
,`ant_fecha_preso` varchar(8)
,`ant_drug` varchar(7)
,`ant_alcoholism` varchar(7)
,`ant_smoking` varchar(7)
,`ant_mining` varchar(7)
,`ant_overcrowding` varchar(7)
,`ant_indigence` varchar(7)
,`ant_drinkable` varchar(7)
,`ant_sanitation` varchar(7)
,`ant_contactposi` varchar(7)
,`ant_BCG` varchar(7)
,`ant_weight_kg` int(11)
,`ant_height` double
,`mat_diag_fecha_BK1` varchar(10)
,`mat_diag_resultado_BK1` varchar(8)
,`nombre_clasificacion_BK1` varchar(100)
,`mat_diag_fecha_BK2` varchar(10)
,`mat_diag_resultado_BK2` varchar(8)
,`nombre_clasificacion_BK2` varchar(100)
,`mat_diag_fecha_BK3` varchar(10)
,`mat_diag_resultado_BK3` varchar(8)
,`nombre_clasificacion_BK3` varchar(100)
,`mat_diag_res_cultivo` varchar(36)
,`mat_diag_fecha_res_cultivo` varchar(10)
,`mat_diag_metodo_WRD` varchar(13)
,`mat_diag_res_metodo_WRD` varchar(8)
,`mat_diag_fecha_res_WRD` varchar(10)
,`mat_diag_res_clinico` varchar(8)
,`mat_diag_fecha_clinico` varchar(10)
,`mat_diag_res_R_X` varchar(8)
,`mat_diag_fecha_R_X` varchar(10)
,`mat_diag_res_histopa` varchar(8)
,`mat_diag_fecha_histopa` varchar(10)
,`clasificacion_tb` varchar(19)
,`clas_pulmonar_EP` varchar(17)
,`clas_lugar_EP` varchar(8)
,`clas_trat_previo` varchar(64)
,`clas_recaida` varchar(2)
,`clas_postfracaso` varchar(2)
,`clas_perdsegui` varchar(2)
,`clas_otros_antestratado` varchar(2)
,`clas_diag_VIH` varchar(11)
,`clas_fecha_diag_VIH` varchar(10)
,`clas_met_diag` varchar(11)
,`clas_esp_MonoR` varchar(1)
,`clas_PoliR_H` varchar(2)
,`clas_PoliR_R` varchar(2)
,`clas_PoliR_Z` varchar(2)
,`clas_PoliR_E` varchar(2)
,`clas_PoliR_S` varchar(2)
,`clas_PoliR_fluoroquinolonas` varchar(2)
,`nombre_fluoroquinolonas` varchar(45)
,`clas_PoliR_2linea` varchar(2)
,`nombre_inyect_2linea` varchar(45)
,`trat_referido` varchar(2)
,`trat_nombre_inst_ref` varchar(100)
,`fecha_fase1` date
,`trat_fecha_inicio_tratF1` varchar(10)
,`trat_med_H_F1` varchar(2)
,`trat_med_Z_F1` varchar(2)
,`trat_med_R_F1` varchar(2)
,`trat_med_E_F1` varchar(2)
,`trat_med_S_F1` varchar(2)
,`trat_med_otros_F1` varchar(2)
,`trat_fecha_fin_tratF1` varchar(10)
,`nombre_adm_tratamientoF1` varchar(45)
,`trat_fecha_inicio_tratF2` varchar(10)
,`trat_med_H_F2` varchar(2)
,`trat_med_R_F2` varchar(2)
,`trat_med_E_F2` varchar(2)
,`trat_med_otros_F2` varchar(2)
,`trat_fecha_fin_tratF2` varchar(10)
,`nombre_adm_tratamientoF2` varchar(45)
,`TB_VIH_solicitud_VIH` varchar(2)
,`TB_VIH_acepto_VIH` varchar(2)
,`TB_VIH_realizada_VIH` varchar(2)
,`TB_VIH_fecha_muestra_VIH` varchar(10)
,`TB_VIH_res_VIH` varchar(8)
,`TB_VIH_fecha_prueba_VIH` varchar(10)
,`TB_VIH_res_previa_VIH` varchar(8)
,`TB_VIH_aseso_VIH` varchar(2)
,`TB_VIH_cotrimoxazol` varchar(2)
,`TB_VIH_fecha_cotrimoxazol` varchar(10)
,`TB_VIH_ref_TARV` varchar(2)
,`TB_VIH_inicio_TARV` varchar(2)
,`TB_VIH_act_TARV` varchar(2)
,`TB_VIH_fecha_inicio_TARV` varchar(10)
,`TB_VIH_nombre_un_TARV` varchar(100)
,`TB_VIH_isoniacida` varchar(2)
,`contacto_identificados_5min` int(11)
,`contacto_sinto_resp_5min` int(11)
,`contacto_evaluados_5min` int(11)
,`contacto_quimioprofilaxis_5min` int(11)
,`contacto_TB_5min` int(11)
,`contacto_identificados_5pl` int(11)
,`contacto_sinto_resp_5pl` int(11)
,`contacto_evaluados_5pl` int(11)
,`contacto_quimioprofilaxis_5pl` int(11)
,`contacto_TB_5pl` int(11)
,`apoyo_social` varchar(2)
,`apoyo_nutricional` varchar(2)
,`apoyo_economico` varchar(2)
,`egreso_fecha_egreso` varchar(10)
,`egreso_cond_egreso` varchar(19)
,`egreso_motivo_exclusion` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vigmor`
--
DROP VIEW IF EXISTS `view_vigmor`;
CREATE TABLE IF NOT EXISTS `view_vigmor` (
`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_unidad` int(11)
,`nombre_unidad` varchar(100)
,`sector_un` int(1)
,`id_evento` int(11)
,`cie_10` varchar(10)
,`id_gevento` int(11)
,`nombre_evento` varchar(250)
,`sexo` varchar(1)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`semana` int(11)
,`anio` int(6)
,`nombre_sala` varchar(100)
,`primer_nombre` varchar(45)
,`edad` int(3)
,`tipo_edad` int(1)
,`rango` varchar(7)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vigmor_casos`
--
DROP VIEW IF EXISTS `view_vigmor_casos`;
CREATE TABLE IF NOT EXISTS `view_vigmor_casos` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_unidad` varchar(100)
,`id_evento` int(11)
,`cie_10` varchar(10)
,`nombre_evento` varchar(250)
,`sexo` varchar(1)
,`semana` int(11)
,`anio` int(6)
,`rango` varchar(7)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`id_gevento` int(11)
,`casos` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vigmor_rangos`
--
DROP VIEW IF EXISTS `view_vigmor_rangos`;
CREATE TABLE IF NOT EXISTS `view_vigmor_rangos` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`nombre_unidad` varchar(100)
,`id_evento` int(11)
,`cie_10` varchar(10)
,`nombre_evento` varchar(250)
,`id_gevento` int(11)
,`sexo` varchar(1)
,`semana` int(11)
,`anio` int(6)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`numero_casos` decimal(41,0)
,`menor_uno` varbinary(42)
,`uno_cuatro` varbinary(42)
,`cinco_nueve` varbinary(42)
,`diez_catorce` varbinary(42)
,`quince_diecinueve` varbinary(42)
,`veinte_veinticuatro` varbinary(42)
,`veinticinco_treinticuatro` varbinary(42)
,`treinticinco_cuarentinueve` varbinary(42)
,`cincuenta_cincuentinueve` varbinary(42)
,`sesenta_sesenticuatro` varbinary(42)
,`mayor_sesenticinco` varbinary(42)
,`NE` varbinary(42)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vigmor_reporte`
--
DROP VIEW IF EXISTS `view_vigmor_reporte`;
CREATE TABLE IF NOT EXISTS `view_vigmor_reporte` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`id_evento` int(11)
,`nombre_evento` varchar(250)
,`id_un` int(11)
,`nombre_unidad` varchar(100)
,`cie_10` varchar(10)
,`id_gevento` int(11)
,`semana` int(11)
,`anio` int(6)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`M<1` double
,`F<1` double
,`M1-4` double
,`F1-4` double
,`M5-9` double
,`F5-9` double
,`M10-14` double
,`F10-14` double
,`M15-19` double
,`F15-19` double
,`M20-24` double
,`F20-24` double
,`M25-34` double
,`F25-34` double
,`M35-49` double
,`F35-49` double
,`M50-59` double
,`F50-59` double
,`M60-64` double
,`F60-64` double
,`M>65` double
,`F>65` double
,`MNE` double
,`FNE` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vigmor_sexo`
--
DROP VIEW IF EXISTS `view_vigmor_sexo`;
CREATE TABLE IF NOT EXISTS `view_vigmor_sexo` (
`nombre_region` varchar(100)
,`nombre_distrito` varchar(100)
,`nombre_corregimiento` varchar(100)
,`id_evento` int(11)
,`nombre_evento` varchar(250)
,`id_un` int(11)
,`nombre_unidad` varchar(100)
,`cie_10` varchar(10)
,`id_gevento` int(11)
,`semana` int(11)
,`anio` int(6)
,`per_id_pais` int(11)
,`per_id_corregimiento` int(11)
,`id_unidad` int(11)
,`sector_un` int(1)
,`id_corregimiento` int(11)
,`id_distrito` int(11)
,`id_region` int(11)
,`menor_unoM` varbinary(23)
,`menor_unoF` varbinary(23)
,`uno_cuatroM` varbinary(23)
,`uno_cuatroF` varbinary(23)
,`cinco_nueveM` varbinary(23)
,`cinco_nueveF` varbinary(23)
,`diez_catorceM` varbinary(23)
,`diez_catorceF` varbinary(23)
,`quince_diecinueveM` varbinary(23)
,`quince_diecinueveF` varbinary(23)
,`veinte_veinticuatroM` varbinary(23)
,`veinte_veinticuatroF` varbinary(23)
,`veinticinco_treinticuatroM` varbinary(23)
,`veinticinco_treinticuatroF` varbinary(23)
,`treinticinco_cuarentinueveM` varbinary(23)
,`treinticinco_cuarentinueveF` varbinary(23)
,`cincuenta_cincuentinueveM` varbinary(23)
,`cincuenta_cincuentinueveF` varbinary(23)
,`sesenta_sesenticuatroM` varbinary(23)
,`sesenta_sesenticuatroF` varbinary(23)
,`mayor_sesenticincoM` varbinary(23)
,`mayor_sesenticincoF` varbinary(23)
,`NEM` varbinary(23)
,`NEF` varbinary(23)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vih_enfermedad_matriz`
--
DROP VIEW IF EXISTS `view_vih_enfermedad_matriz`;
CREATE TABLE IF NOT EXISTS `view_vih_enfermedad_matriz` (
`anio` int(6)
,`semana_epi` int(11)
,`id_evento` int(11)
,`nombre_evento` varchar(250)
,`cond_vih` int(1)
,`cond_sida` int(1)
,`cond_condicion_paciente` int(1)
,`menor_uno_m` decimal(23,0)
,`uno_cuatro_m` decimal(23,0)
,`cinco_nueve_m` decimal(23,0)
,`diez_catorce_m` decimal(23,0)
,`quince_diecinueve_m` decimal(23,0)
,`veinte_veinticuatro_m` decimal(23,0)
,`veinticinco_veintinueve_m` decimal(23,0)
,`treinta_treitaycuatro_m` decimal(23,0)
,`treintaycinco_treintaynueve_m` decimal(23,0)
,`cuarenta_cuarentaycuatro_m` decimal(23,0)
,`cuarentaycinco_cuarentaynueve_m` decimal(23,0)
,`cincuenta_cincuentaycuatro_m` decimal(23,0)
,`cincuentaycinco_cincuentaynueve_m` decimal(23,0)
,`sesenta_sesentaycuantro_m` decimal(23,0)
,`mas_sesentaycinco_m` decimal(23,0)
,`menor_uno_f` decimal(23,0)
,`uno_cuatro_f` decimal(23,0)
,`cinco_nueve_f` decimal(23,0)
,`diez_catorce_f` decimal(23,0)
,`quince_diecinueve_f` decimal(23,0)
,`veinte_veinticuatro_f` decimal(23,0)
,`veinticinco_veintinueve_f` decimal(23,0)
,`treinta_treitaycuatro_f` decimal(23,0)
,`treintaycinco_treintaynueve_f` decimal(23,0)
,`cuarenta_cuarentaycuatro_f` decimal(23,0)
,`cuarentaycinco_cuarentaynueve_f` decimal(23,0)
,`cincuenta_cincuentaycuatro_f` decimal(23,0)
,`cincuentaycinco_cincuentaynueve_f` decimal(23,0)
,`sesenta_sesentaycuantro_f` decimal(23,0)
,`mas_sesentaycinco_f` decimal(23,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vih_factor_matriz`
--
DROP VIEW IF EXISTS `view_vih_factor_matriz`;
CREATE TABLE IF NOT EXISTS `view_vih_factor_matriz` (
`anio` int(6)
,`semana_epi` int(11)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_factor` int(11)
,`factor_nombre` varchar(100)
,`id_grupo_factor` int(11)
,`grupo_factor_nombre` varchar(100)
,`cond_vih` int(1)
,`cond_sida` int(1)
,`cond_condicion_paciente` int(1)
,`menor_uno_m` decimal(23,0)
,`uno_cuatro_m` decimal(23,0)
,`cinco_nueve_m` decimal(23,0)
,`diez_catorce_m` decimal(23,0)
,`quince_diecinueve_m` decimal(23,0)
,`veinte_veinticuatro_m` decimal(23,0)
,`veinticinco_veintinueve_m` decimal(23,0)
,`treinta_treitaycuatro_m` decimal(23,0)
,`treintaycinco_treintaynueve_m` decimal(23,0)
,`cuarenta_cuarentaycuatro_m` decimal(23,0)
,`cuarentaycinco_cuarentaynueve_m` decimal(23,0)
,`cincuenta_cincuentaycuatro_m` decimal(23,0)
,`cincuentaycinco_cincuentaynueve_m` decimal(23,0)
,`sesenta_sesentaycuantro_m` decimal(23,0)
,`mas_sesentaycinco_m` decimal(23,0)
,`menor_uno_f` decimal(23,0)
,`uno_cuatro_f` decimal(23,0)
,`cinco_nueve_f` decimal(23,0)
,`diez_catorce_f` decimal(23,0)
,`quince_diecinueve_f` decimal(23,0)
,`veinte_veinticuatro_f` decimal(23,0)
,`veinticinco_veintinueve_f` decimal(23,0)
,`treinta_treitaycuatro_f` decimal(23,0)
,`treintaycinco_treintaynueve_f` decimal(23,0)
,`cuarenta_cuarentaycuatro_f` decimal(23,0)
,`cuarentaycinco_cuarentaynueve_f` decimal(23,0)
,`cincuenta_cincuentaycuatro_f` decimal(23,0)
,`cincuentaycinco_cincuentaynueve_f` decimal(23,0)
,`sesenta_sesentaycuantro_f` decimal(23,0)
,`mas_sesentaycinco_f` decimal(23,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vih_factor_reporte`
--
DROP VIEW IF EXISTS `view_vih_factor_reporte`;
CREATE TABLE IF NOT EXISTS `view_vih_factor_reporte` (
`anio` int(6)
,`semana_epi` int(11)
,`cond_condicion_paciente` int(1)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_grupo_factor` int(11)
,`grupo_factor_nombre` varchar(100)
,`id_factor` int(11)
,`factor_nombre` varchar(100)
,`cond_vih` int(1)
,`cond_sida` int(1)
,`menor_uno_m` decimal(45,0)
,`uno_cuatro_m` decimal(45,0)
,`cinco_nueve_m` decimal(45,0)
,`diez_catorce_m` decimal(45,0)
,`quince_diecinueve_m` decimal(45,0)
,`veinte_veinticuatro_m` decimal(45,0)
,`veinticinco_veintinueve_m` decimal(45,0)
,`treinta_treitaycuatro_m` decimal(45,0)
,`treintaycinco_treintaynueve_m` decimal(45,0)
,`cuarenta_cuarentaycuatro_m` decimal(45,0)
,`cuarentaycinco_cuarentaynueve_m` decimal(45,0)
,`cincuenta_cincuentaycuatro_m` decimal(45,0)
,`cincuentaycinco_cincuentaynueve_m` decimal(45,0)
,`sesenta_sesentaycuantro_m` decimal(45,0)
,`mas_sesentaycinco_m` decimal(45,0)
,`menor_uno_f` decimal(45,0)
,`uno_cuatro_f` decimal(45,0)
,`cinco_nueve_f` decimal(45,0)
,`diez_catorce_f` decimal(45,0)
,`quince_diecinueve_f` decimal(45,0)
,`veinte_veinticuatro_f` decimal(45,0)
,`veinticinco_veintinueve_f` decimal(45,0)
,`treinta_treitaycuatro_f` decimal(45,0)
,`treintaycinco_treintaynueve_f` decimal(45,0)
,`cuarenta_cuarentaycuatro_f` decimal(45,0)
,`cuarentaycinco_cuarentaynueve_f` decimal(45,0)
,`cincuenta_cincuentaycuatro_f` decimal(45,0)
,`cincuentaycinco_cincuentaynueve_f` decimal(45,0)
,`sesenta_sesentaycuantro_f` decimal(45,0)
,`mas_sesentaycinco_f` decimal(45,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vih_matriz`
--
DROP VIEW IF EXISTS `view_vih_matriz`;
CREATE TABLE IF NOT EXISTS `view_vih_matriz` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`menor_uno_m` decimal(23,0)
,`uno_cuatro_m` decimal(23,0)
,`cinco_nueve_m` decimal(23,0)
,`diez_catorce_m` decimal(23,0)
,`quince_diecinueve_m` decimal(23,0)
,`veinte_veinticuatro_m` decimal(23,0)
,`veinticinco_veintinueve_m` decimal(23,0)
,`treinta_treitaycuatro_m` decimal(23,0)
,`treintaycinco_treintaynueve_m` decimal(23,0)
,`cuarenta_cuarentaycuatro_m` decimal(23,0)
,`cuarentaycinco_cuarentaynueve_m` decimal(23,0)
,`cincuenta_cincuentaycinco_m` decimal(23,0)
,`cincuentaycinco_cincuentaynueve_m` decimal(23,0)
,`sesenta_sesentaycuantro_m` decimal(23,0)
,`mas_sesentaycinco_m` decimal(23,0)
,`menor_uno_f` decimal(23,0)
,`uno_cuatro_f` decimal(23,0)
,`cinco_nueve_f` decimal(23,0)
,`diez_catorce_f` decimal(23,0)
,`quince_diecinueve_f` decimal(23,0)
,`veinte_veinticuatro_f` decimal(23,0)
,`veinticinco_veintinueve_f` decimal(23,0)
,`treinta_treitaycuatro_f` decimal(23,0)
,`treintaycinco_treintaynueve_f` decimal(23,0)
,`cuarenta_cuarentaycuatro_f` decimal(23,0)
,`cuarentaycinco_cuarentaynueve_f` decimal(23,0)
,`cincuenta_cincuentaycinco_f` decimal(23,0)
,`cincuentaycinco_cincuentaynueve_f` decimal(23,0)
,`sesenta_sesentaycuantro_f` decimal(23,0)
,`mas_sesentaycinco_f` decimal(23,0)
,`cond_vih` int(1)
,`cond_sida` int(1)
,`fecha_notificacion` varchar(10)
,`anio` int(6)
,`semana_epi` int(11)
,`cond_condicion_paciente` int(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vih_regiones`
--
DROP VIEW IF EXISTS `view_vih_regiones`;
CREATE TABLE IF NOT EXISTS `view_vih_regiones` (
`region_m1` decimal(23,0)
,`region_m2` decimal(23,0)
,`region_m3` decimal(23,0)
,`region_m4` decimal(23,0)
,`region_m5` decimal(23,0)
,`region_m6` decimal(23,0)
,`region_m7` decimal(23,0)
,`region_m8` decimal(23,0)
,`region_m9` decimal(23,0)
,`region_m10` decimal(23,0)
,`region_m11` decimal(23,0)
,`region_m12` decimal(23,0)
,`region_m13` decimal(23,0)
,`region_m14` decimal(23,0)
,`region_f1` decimal(23,0)
,`region_f2` decimal(23,0)
,`region_f3` decimal(23,0)
,`region_f4` decimal(23,0)
,`region_f5` decimal(23,0)
,`region_f6` decimal(23,0)
,`region_f7` decimal(23,0)
,`region_f8` decimal(23,0)
,`region_f9` decimal(23,0)
,`region_f10` decimal(23,0)
,`region_f11` decimal(23,0)
,`region_f12` decimal(23,0)
,`region_f13` decimal(23,0)
,`region_f14` decimal(23,0)
,`cond_vih` int(1)
,`cond_sida` int(1)
,`fecha_notificacion` varchar(10)
,`anio` int(6)
,`semana_epi` int(11)
,`cond_condicion_paciente` int(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vih_reporte`
--
DROP VIEW IF EXISTS `view_vih_reporte`;
CREATE TABLE IF NOT EXISTS `view_vih_reporte` (
`id_provincia` int(11)
,`nombre_provincia` varchar(100)
,`id_region` int(11)
,`nombre_region` varchar(100)
,`id_distrito` int(11)
,`nombre_distrito` varchar(100)
,`id_corregimiento` int(11)
,`nombre_corregimiento` varchar(100)
,`id_un` int(11)
,`nombre_un` varchar(100)
,`menor_uno_m` decimal(45,0)
,`uno_cuatro_m` decimal(45,0)
,`cinco_nueve_m` decimal(45,0)
,`diez_catorce_m` decimal(45,0)
,`quince_diecinueve_m` decimal(45,0)
,`veinte_veinticuatro_m` decimal(45,0)
,`veinticinco_veintinueve_m` decimal(45,0)
,`treinta_treitaycuatro_m` decimal(45,0)
,`treintaycinco_treintaynueve_m` decimal(45,0)
,`cuarenta_cuarentaycuatro_m` decimal(45,0)
,`cuarentaycinco_cuarentaynueve_m` decimal(45,0)
,`cincuenta_cincuentaycinco_m` decimal(45,0)
,`cincuentaycinco_cincuentaynueve_m` decimal(45,0)
,`sesenta_sesentaycuantro_m` decimal(45,0)
,`mas_sesentaycinco_m` decimal(45,0)
,`menor_uno_f` decimal(45,0)
,`uno_cuatro_f` decimal(45,0)
,`cinco_nueve_f` decimal(45,0)
,`diez_catorce_f` decimal(45,0)
,`quince_diecinueve_f` decimal(45,0)
,`veinte_veinticuatro_f` decimal(45,0)
,`veinticinco_veintinueve_f` decimal(45,0)
,`treinta_treitaycuatro_f` decimal(45,0)
,`treintaycinco_treintaynueve_f` decimal(45,0)
,`cuarenta_cuarentaycuatro_f` decimal(45,0)
,`cuarentaycinco_cuarentaynueve_f` decimal(45,0)
,`cincuenta_cincuentaycinco_f` decimal(45,0)
,`cincuentaycinco_cincuentaynueve_f` decimal(45,0)
,`sesenta_sesentaycuantro_f` decimal(45,0)
,`mas_sesentaycinco_f` decimal(45,0)
,`cond_vih` decimal(32,0)
,`cond_sida` decimal(32,0)
,`fecha_admision` varchar(10)
,`anio` int(6)
,`semana_epi` int(11)
,`cond_condicion_paciente` int(1)
);
-- --------------------------------------------------------

--
-- Table structure for table `vih_enfermedad_oportunista`
--

DROP TABLE IF EXISTS `vih_enfermedad_oportunista`;
CREATE TABLE IF NOT EXISTS `vih_enfermedad_oportunista` (
  `id_vih_form` int(11) NOT NULL DEFAULT '0' COMMENT 'Identificador del formulario de VIH/SIDA',
  `id_evento` int(11) NOT NULL DEFAULT '0' COMMENT 'Identificador del evento CIE10',
  PRIMARY KEY (`id_vih_form`,`id_evento`),
  UNIQUE KEY `uk_vih_enfermedad_oportunista` (`id_vih_form`,`id_evento`),
  KEY `fk_vih_enfemerdad_form` (`id_vih_form`),
  KEY `fk_vih_evento` (`id_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de rel del formnualario de VIH/SIDA con enfermedades o';

-- --------------------------------------------------------

--
-- Table structure for table `vih_factor_riesgo`
--

DROP TABLE IF EXISTS `vih_factor_riesgo`;
CREATE TABLE IF NOT EXISTS `vih_factor_riesgo` (
  `id_factor_riesgo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario de VIH/SIDA',
  `id_vih_form` int(11) DEFAULT NULL COMMENT 'Identificador del formulario de VIH/SIDA',
  `id_grupo_factor` int(11) DEFAULT NULL COMMENT 'Identificador del grupo de factor de reisgo',
  `id_factor` int(11) DEFAULT NULL COMMENT 'Identificador del factor de riesgo',
  PRIMARY KEY (`id_factor_riesgo`),
  UNIQUE KEY `uk_vih_factor_riesgo` (`id_vih_form`,`id_grupo_factor`,`id_factor`),
  KEY `fk_vih_form` (`id_vih_form`),
  KEY `fk_vih_grupo_factor` (`id_grupo_factor`),
  KEY `fk_vih_factor` (`id_factor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de rel del formnualario de VIH/SIDA con el factor de r' AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `vih_form`
--

DROP TABLE IF EXISTS `vih_form`;
CREATE TABLE IF NOT EXISTS `vih_form` (
  `id_vih_form` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario de VIH/SIDA',
  `id_tipo_identidad` int(11) DEFAULT NULL,
  `numero_identificacion` varchar(30) DEFAULT NULL,
  `per_asegurado` int(1) DEFAULT NULL COMMENT '1: asegurado, 2: no asegurado',
  `per_edad` int(3) DEFAULT '0' COMMENT 'Edad de la persona fallecida',
  `per_tipo_edad` int(1) NOT NULL COMMENT 'Tipo de dato de edad:n0 = No Daton1 = Añosn2 = Mesesn3 = Díasn',
  `per_id_corregimiento` int(11) NOT NULL COMMENT 'Corregimiento en donde vive la persona',
  `per_localidad` varchar(100) DEFAULT NULL COMMENT 'Localidad en donde vive la persona',
  `per_estado_civil` int(1) DEFAULT NULL COMMENT '1:soltero 2:casado 3:unido 4:divorciado 5:NE',
  `comp_its_ultimo` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desconocido',
  `comp_its_ulcerativa` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desconocido ITS ulcerativa',
  `comp_edad_inicio_sexual` int(3) DEFAULT NULL COMMENT 'Edad de inicio de vida sexual activa',
  `comp_uso_condon` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desconocido',
  `comp_trabajador_sexual` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desconocido',
  `comp_donante_sangre` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desconocido',
  `comp_donante_fecha` int(4) DEFAULT NULL COMMENT 'Anio de la ultima donacion de sangre',
  `comp_donante_instalacion` varchar(100) DEFAULT NULL COMMENT 'Nombre de la instalacion donde realizo la ultima donacion de sangre',
  `comp_per_preso` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desc persona privada de la libertad',
  `comp_embarazada` int(1) DEFAULT NULL COMMENT '1:si 2:no 0:desconocido',
  `comp_emb_3` int(4) DEFAULT NULL COMMENT 'Anio del embarazo 3',
  `comp_emb_2` int(4) DEFAULT NULL COMMENT 'Anio del embarazo 2',
  `comp_emb_1` int(4) DEFAULT NULL COMMENT 'Anio del embarazo 1',
  `comp_emb_captada` int(1) DEFAULT '4' COMMENT '1:>20 sem 2:<20 3:puerperino 4:desc',
  `comp_fecha_parto` date DEFAULT NULL COMMENT 'Fecha posible del parto',
  `cond_vih` int(1) DEFAULT NULL COMMENT '1:marcado caso de VIH',
  `cond_fecha_vih` date DEFAULT NULL COMMENT 'Fecha de diagnostico del caso de VIH',
  `cond_edad_vih` int(3) DEFAULT NULL COMMENT 'Edad del paciente en el momento que le diagnosticaron VIH: 999 Desconoce',
  `cond_sida` int(1) DEFAULT NULL COMMENT '1:marcado caso de SIDA',
  `cond_fecha_sida` date DEFAULT NULL COMMENT 'Fecha de diagnostico del caso de SIDA',
  `cond_edad_sida` int(3) DEFAULT NULL COMMENT 'Edad del paciente en el momento que le diagnosticaron SIDA: 999 Desconoce',
  `cond_sobrevida` varchar(50) DEFAULT NULL COMMENT 'La sobrevida se calcula con la resta de la fecha de diagnostico entre VIH y Sida',
  `cond_condicion_paciente` int(1) DEFAULT NULL COMMENT '1:vivo 2:muerto 3:desconoce, condicion actual del paciente',
  `cond_fecha_defuncion` date DEFAULT NULL COMMENT 'Fecha de la defuncion del paciente',
  `cond_sobrevida_sida` varchar(50) DEFAULT NULL COMMENT 'La sobrevida se calcula con la resta de la fecha de diagnostico Sida y la de defuncion',
  `cond_lugar_diagnostico` varchar(100) DEFAULT NULL COMMENT 'Nombre del lugar donde diagnosticaron el caso del paciente',
  `cond_lugar_diagnostico_sida` varchar(100) DEFAULT NULL COMMENT 'Nombre del lugar donde diagnosticaron el caso DE SIDA del paciente',
  `id_un` int(11) DEFAULT NULL COMMENT 'Código de la unidad notificadora',
  `unidad_disponible` int(1) DEFAULT NULL COMMENT '1:marcado no dispoible la unidad notificadora',
  `nombre_notifica` varchar(100) CHARACTER SET cp1250 NOT NULL COMMENT 'Nombre de la persona que notifica',
  `fecha_notificacion` date NOT NULL COMMENT 'Fecha en que se notifica el caso de VIH/SIDA',
  `semana_epi` int(11) NOT NULL COMMENT 'Semana epidemiológica',
  `anio` int(6) NOT NULL COMMENT 'Año de la semana epidemiológica',
  `nombre_registra` varchar(45) NOT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `fecha_formulario` date NOT NULL COMMENT 'Fecha en que se llena el formulario',
  `silab` int(1) DEFAULT NULL COMMENT 'Identificador de la muestra si biene de SILAB',
  `cond_id_un_defuncion` int(11) DEFAULT NULL COMMENT 'codigo global de la muestra que viene de SILAB',
  `cond_otro_defuncion` varchar(100) DEFAULT NULL COMMENT 'Otro nombre de la unidad notificadora, se usa mucho cuando vienen de SILAB',
  `mue_id` int(11) DEFAULT NULL COMMENT 'Identificador de la muestra si biene de SILAB',
  `codigo_global` varchar(30) DEFAULT NULL COMMENT 'codigo global de la muestra que viene de SILAB',
  `otro_nombre_un` varchar(100) DEFAULT NULL COMMENT 'Otro nombre de la unidad notificadora, se usa mucho cuando vienen de SILAB',
  `epiInfo` int(1) DEFAULT '0' COMMENT '1: viene de EpiInfo y esta incompleto 2:EpiInfo actualizado 0: esta completo',
  PRIMARY KEY (`id_vih_form`),
  UNIQUE KEY `uk_vih_persona` (`id_tipo_identidad`,`numero_identificacion`),
  KEY `fk_vih_un` (`id_un`),
  KEY `fk_vih_corregimiento` (`per_id_corregimiento`),
  KEY `fk_vih_tipo_identificacion` (`id_tipo_identidad`),
  KEY `index_numero_identifica` (`numero_identificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla principal del formnualario de VIH/SIDA' AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `vih_muestra_prueba_silab`
--

DROP TABLE IF EXISTS `vih_muestra_prueba_silab`;
CREATE TABLE IF NOT EXISTS `vih_muestra_prueba_silab` (
  `id_vih_muestra_prueba_silab` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `id_vih_form` int(11) NOT NULL COMMENT 'Identificador del formulario muestras de lab silab',
  `id_muestra` int(11) NOT NULL COMMENT 'Identificador de la muestra en SILAB',
  `nombre_prueba` varchar(100) DEFAULT NULL COMMENT 'Nombre de la prueba',
  `resultado_prueba` varchar(45) DEFAULT NULL COMMENT 'Resultado de la prueba',
  `fecha_prueba` varchar(45) DEFAULT NULL COMMENT 'Fecha de la prueba',
  `Comentario_prueba` varchar(45) DEFAULT NULL COMMENT 'Comentarios de la prueba',
  PRIMARY KEY (`id_vih_muestra_prueba_silab`),
  KEY `fk_vih_form` (`id_vih_form`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre las muestras de laboratorio de silab' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vih_muestra_silab`
--

DROP TABLE IF EXISTS `vih_muestra_silab`;
CREATE TABLE IF NOT EXISTS `vih_muestra_silab` (
  `id_vih_muestra_silab` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario',
  `id_vih_form` int(11) NOT NULL COMMENT 'Identificador del formulario VIH',
  `id_muestra` int(11) NOT NULL COMMENT 'Identificador de la muestra en SILAB',
  `codigo_global` varchar(45) DEFAULT NULL COMMENT 'Codigo Global de la muestra',
  `codigo_correlativo` varchar(45) DEFAULT NULL COMMENT 'Codigo Correlativo de la muestra',
  `tipo_muestra` varchar(45) DEFAULT NULL COMMENT 'Tipo de muestra',
  `fecha_inicio_sintoma` varchar(45) DEFAULT NULL COMMENT 'Fecha de inicio de sintomas',
  `fecha_toma` varchar(45) DEFAULT NULL COMMENT 'Fecha toma',
  `fecha_recepcion` varchar(45) DEFAULT NULL COMMENT 'Fecha recepcion',
  `unidad_notificadora` varchar(45) DEFAULT NULL COMMENT 'Unidad notificadora',
  `estado_muestra` varchar(45) DEFAULT NULL COMMENT 'Estado Muestra',
  `resultado` varchar(45) DEFAULT NULL COMMENT 'Resultado Muestra',
  `tipo1` varchar(45) DEFAULT NULL COMMENT 'Tipo1 Muestra',
  `subtipo1` varchar(45) DEFAULT NULL COMMENT 'Subtipo1 Muestra',
  `tipo2` varchar(45) DEFAULT NULL COMMENT 'Tipo2 Muestra',
  `subtipo2` varchar(45) DEFAULT NULL COMMENT 'Subtipo2 Muestra',
  `comentario_resultado` varchar(45) DEFAULT NULL COMMENT 'Comentarios del resultado',
  PRIMARY KEY (`id_vih_muestra_silab`),
  KEY `fk_vih_form` (`id_vih_form`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de relacion entre el form VIH y las muestras de labora' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vih_silab_temp`
--

DROP TABLE IF EXISTS `vih_silab_temp`;
CREATE TABLE IF NOT EXISTS `vih_silab_temp` (
  `id_vih_silab_temp` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la muestra en SISVIG',
  `MUE_ID` int(11) NOT NULL COMMENT 'Identificador de la muestra en SILAB',
  `MUE_CODIGO_GLOBAL_ANIO` int(11) NOT NULL COMMENT 'Anio de la toma de muestra',
  `MUE_CODIGO_GLOBAL_NUMERO` varchar(45) DEFAULT NULL COMMENT 'Codigo Global de la muestra',
  `IND_PRIMER_NOMBRE` varchar(45) DEFAULT NULL COMMENT 'Primer nombre de la persona',
  `IND_SEGUNDO_NOMBRE` varchar(45) DEFAULT NULL COMMENT 'Segundo nombre de la persona',
  `IND_PRIMER_APELLIDO` varchar(45) DEFAULT NULL COMMENT 'Primer apellido de la persona',
  `IND_SEGUNDO_APELLIDO` varchar(45) DEFAULT NULL COMMENT 'Segundo apellido de la persona',
  `IND_IDENTIFICADOR` varchar(45) DEFAULT NULL COMMENT 'Codigo de identificacion de la persona',
  `IND_IDENTIFICADOR_TIPO` int(11) DEFAULT NULL COMMENT 'Id del tipo de identificacion de la persona',
  `nombre_tipo` varchar(45) DEFAULT NULL COMMENT 'Nombre del tipo de identificacion de la persona',
  `IND_PROC_PROVINCIA` int(11) DEFAULT NULL COMMENT 'Id de la provincia de la persona',
  `IND_PROC_REGION` int(11) DEFAULT NULL COMMENT 'Id de la region de la persona',
  `IND_PROC_DISTRITO` int(11) DEFAULT NULL COMMENT 'Id del distrito de la persona',
  `IND_PROC_CORREGIMIENTO` int(11) DEFAULT NULL COMMENT 'Id del corregimiento de la persona',
  `IND_DIRECCION` varchar(45) DEFAULT NULL COMMENT 'Direccion o punto de referencia de la persona',
  `IND_TELEFONO` varchar(45) DEFAULT NULL COMMENT 'Telefono de la persona',
  `IND_FECHA_NACIMIENTO` varchar(45) DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `IND_EDAD` int(11) DEFAULT NULL COMMENT 'Edad de la persona',
  `IND_TIPO_EDAD` int(11) DEFAULT NULL COMMENT 'Tipo de edad de la persona',
  `IND_SEXO` varchar(1) DEFAULT NULL COMMENT 'Sexo de la persona M:Hombre y F:Mujer',
  `MUE_FECHA_INICIO` varchar(45) DEFAULT NULL COMMENT 'Fecha de inicio de sintomas',
  `condicion` int(1) DEFAULT NULL COMMENT 'Condicion de la perosna 1:Vivo 2:muerto',
  `MUE_PROC_INST_SALUD` int(11) DEFAULT NULL COMMENT 'Identificador en SILAB de la instalacion de salud',
  `cod_ref_minsa` varchar(45) DEFAULT NULL COMMENT 'Codigo de referencia del MINSA de la instalacion de salud',
  `MUE_OTRO_ESTABLECIMIENTO_NOMBRE` varchar(45) DEFAULT NULL COMMENT 'Nombre de otra instalacion de salud, sino esta en la BD',
  `MUE_REFERIDA_POR` varchar(45) DEFAULT NULL COMMENT 'Nombre del personal de salud que autorizo o solicito la muestra',
  `MUE_FECHA_INGRESO_SISTEMA` varchar(45) DEFAULT NULL COMMENT 'Fecha en que se ingreso la muestra a SILAB - notificacion ',
  `MUE_SEMANA_EPI` int(2) DEFAULT NULL COMMENT 'Semana Epidemiologica en que ingreso la muestra a SILAB',
  PRIMARY KEY (`id_vih_silab_temp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla temporal de las muestras de laborato SILAB' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vih_silab_temp_factor_riesgo`
--

DROP TABLE IF EXISTS `vih_silab_temp_factor_riesgo`;
CREATE TABLE IF NOT EXISTS `vih_silab_temp_factor_riesgo` (
  `id_vih_silab_temp_factor_riesgo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la muestra en SISVIG',
  `MUE_ID` int(11) NOT NULL COMMENT 'Identificador de la muestra en SILAB',
  `VP` int(11) DEFAULT NULL COMMENT 'SILAB:Varias Parejas - SISVIG:Hetero promiscuo',
  `TS` int(11) DEFAULT NULL COMMENT 'SILAB:Trabajador sexual - SISVIG:Trabajador sexual',
  `UDI` int(11) DEFAULT NULL COMMENT 'SILAB:Uso de drogas intravenosa - SISVIG:Uso de drogas IV',
  `UDO` int(11) DEFAULT NULL COMMENT 'SILAB:Uso de otras drogas - SISVIG:Uso de drogas no IV',
  `no_preservativo` int(11) DEFAULT NULL COMMENT 'No uso preservativo en su ultima relacion sexual',
  `exp_perinatal` int(11) DEFAULT NULL COMMENT 'SILAB:Exposicion perinatla - SISVIG:Grupo Perinatal - no disponible',
  `desconocido` int(11) DEFAULT NULL COMMENT 'SILAB:desconocido - SISVIG:No especificado',
  `embarazo` int(11) DEFAULT NULL COMMENT 'Si la persona esta en embarazo >1:Si 0:no',
  `donante` int(11) DEFAULT NULL COMMENT 'Si la persona es donante de sangre >1:si 0:no',
  `ITS` int(11) DEFAULT NULL COMMENT 'Si la persona tiene actualmente una ITS >1:si 0:no',
  `transfusion` int(11) DEFAULT NULL COMMENT 'Si la persona tuvo una Transfusion >1:si 0:no',
  `cont_vih` int(11) DEFAULT NULL COMMENT 'SILAB:Contacto VIH - SISVIG:Pareja VIH positiva',
  PRIMARY KEY (`id_vih_silab_temp_factor_riesgo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla temporal de los factores de riesgo de las muestras de ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vih_tarv`
--

DROP TABLE IF EXISTS `vih_tarv`;
CREATE TABLE IF NOT EXISTS `vih_tarv` (
  `id_vih_tarv` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del formulario de TARV',
  `id_vih_form` int(11) NOT NULL COMMENT 'Identificador del formulario de VIH/SIDA',
  `tarv_fec_ingreso` date DEFAULT NULL COMMENT 'Fecha de ingreso a la clinica TARV',
  `id_clinica_tarv` int(11) DEFAULT NULL COMMENT 'Identificador de la clinica TARV',
  `tarv_fec_inicio` date DEFAULT NULL COMMENT 'Fecha de inicio del TARV',
  `tarv_fec_cd4` date DEFAULT NULL COMMENT 'Fecha primera prueba cd4',
  `tarv_res_cd4` int(3) DEFAULT NULL COMMENT '3 digitos Resultado de la primera prueba cd4',
  `tarv_fec_cd4_350` date DEFAULT NULL COMMENT 'Fecha recuento de cd4 <350',
  `tarv_res_cd4_350` int(3) DEFAULT NULL COMMENT '3 digitos Resultado recuento de cd4 <350',
  `tarv_fec_cd4_200` date DEFAULT NULL COMMENT 'Fecha recuento de cd4 <200',
  `tarv_res_cd4_200` int(3) DEFAULT NULL COMMENT '3 digitos Resultado recuento de cd4 <200',
  `tarv_fec_carga_viral` date DEFAULT NULL COMMENT 'Fecha primera carga viral',
  `tarv_res_carga_viral` int(3) DEFAULT NULL COMMENT '3 digitos Resultado primera carga viral',
  PRIMARY KEY (`id_vih_tarv`),
  UNIQUE KEY `uk_vih_form_tarv_form` (`id_vih_tarv`,`id_vih_form`),
  KEY `fk_vih_tarv_vih_form` (`id_vih_form`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de rel del formnualario de VIH/SIDA con el formulario ' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `vm_eno_matriz`
--

DROP TABLE IF EXISTS `vm_eno_matriz`;
CREATE TABLE IF NOT EXISTS `vm_eno_matriz` (
  `nombre_region` varchar(100) DEFAULT NULL,
  `nombre_distrito` varchar(100) DEFAULT NULL,
  `nombre_corregimiento` varchar(100) DEFAULT NULL,
  `nombre_un` varchar(100) DEFAULT NULL,
  `cie_10_1` varchar(10) DEFAULT NULL,
  `nombre_evento` varchar(250) DEFAULT NULL,
  `semana_epi` int(2) DEFAULT NULL,
  `anio` int(4) DEFAULT NULL,
  `servicio` int(4) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `id_rango` int(10) unsigned DEFAULT NULL,
  `id_corregimiento` int(11) DEFAULT NULL,
  `id_distrito` int(11) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `id_un` int(11) DEFAULT NULL,
  `nombre_rango` varchar(45) DEFAULT NULL,
  `numero_casos` decimal(32,0) DEFAULT NULL,
  `sector_un` int(1) DEFAULT NULL,
  `menor_uno` varbinary(55) DEFAULT NULL,
  `uno_cuatro` varbinary(55) DEFAULT NULL,
  `cinco_nueve` varbinary(55) DEFAULT NULL,
  `nueve_catorce` varbinary(55) DEFAULT NULL,
  `quince_diecinueve` varbinary(55) DEFAULT NULL,
  `veinte_veinticuatro` varbinary(55) DEFAULT NULL,
  `veinticinco_treinticuatro` varbinary(55) DEFAULT NULL,
  `treinticinco_cuarentinueve` varbinary(55) DEFAULT NULL,
  `cincuenta_cincuentinueve` varbinary(55) DEFAULT NULL,
  `sesenta_sesenticuatro` varbinary(55) DEFAULT NULL,
  `mayor_sesenticinco` varbinary(55) DEFAULT NULL,
  `N_E` varbinary(55) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vm_form`
--

DROP TABLE IF EXISTS `vm_form`;
CREATE TABLE IF NOT EXISTS `vm_form` (
  `id_form` int(11) NOT NULL AUTO_INCREMENT,
  `id_un` int(11) NOT NULL COMMENT 'Código de la unidad notificadora',
  `id_servicio` int(4) NOT NULL DEFAULT '13' COMMENT 'Identificacion del servicio de salud',
  `nombre_servicio` varchar(100) NOT NULL COMMENT 'Nombre del servicio intrahospitalario',
  `nombre_sala` varchar(100) DEFAULT NULL COMMENT 'Nombre de la sala dentro del servicio intrahospitalario',
  `tipo_identificacion` int(11) DEFAULT NULL,
  `numero_identificacion` varchar(30) DEFAULT NULL,
  `per_edad` int(3) DEFAULT '0' COMMENT 'Edad de la persona fallecida',
  `per_tipo_edad` int(1) NOT NULL COMMENT 'Tipo de dato de edad:n0 = No Daton1 = Añosn2 = Mesesn3 = Díasn',
  `per_id_region` int(11) DEFAULT NULL COMMENT 'Región de salud en donde vive la persona',
  `per_id_corregimiento` int(11) NOT NULL COMMENT 'Corregimiento en donde vive la persona',
  `per_localidad` varchar(100) DEFAULT NULL COMMENT 'Localidad en donde vive la persona',
  `fecha_hospitalizacion` date DEFAULT NULL COMMENT 'Fecha de hospitalización de la persona fallecida',
  `fecha_defuncion` date NOT NULL COMMENT 'Fecha defunción de la persona fallecida',
  `semana_epi` int(11) DEFAULT NULL COMMENT 'Semana epidemiológica',
  `hora_defuncion` time DEFAULT NULL COMMENT 'Hora de defunción de la persona fallecida',
  `id_diagnostico1` int(11) NOT NULL COMMENT 'Diagnóstico de la primera casua de muerte de la persona fallecida',
  `id_diagnostico2` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la segunda casua de muerte de la persona fallecida',
  `id_diagnostico3` int(11) DEFAULT NULL COMMENT 'Diagnóstico de la tercera casua de muerte de la persona fallecida',
  `fecha_notificacion` date NOT NULL COMMENT 'Fecha en que se notifica la defunsión',
  `persona_notifica` varchar(100) NOT NULL COMMENT 'Nombre de la persona que notifica',
  `anio` int(6) DEFAULT NULL COMMENT 'Año de la semana epidemiológica',
  `estado_diag1` int(2) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `estado_diag2` int(2) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `estado_diag3` int(2) DEFAULT NULL COMMENT 'Estado del diagnostico 1:sospechoso, 2:confirmado, 3:probable',
  `fecha_morgue` date DEFAULT NULL,
  `id_diagnostico_final` int(11) DEFAULT '0' COMMENT 'Diagnóstico de cierre de la casua de muerte de la persona fallecida',
  `estado_diag_final` int(2) DEFAULT '0' COMMENT 'Estado del diagnostico final 2:confirmado, 3:probable',
  `org_codigo` text CHARACTER SET latin1 COMMENT 'codigo de la institucion que notifica',
  `per_id_pais` int(11) DEFAULT '174' COMMENT 'identificador del pais de procedencia de la persona',
  `nombre_registra` varchar(45) DEFAULT NULL COMMENT 'Nombre de la persona que registra el formulario',
  `institucion_registra` varchar(45) DEFAULT NULL COMMENT 'Institucion a la pertenece la persona que registra',
  `telefono` int(20) DEFAULT NULL COMMENT 'Telefono de quien reporta',
  `id_cargo` int(11) DEFAULT NULL COMMENT 'identificador del Cargo de la persona que reporta',
  `id_diagnostico_final2` int(11) DEFAULT NULL COMMENT 'Identificador del evento final 2',
  `estado_diag_final2` int(11) DEFAULT NULL COMMENT 'Estado diag final2 1:sospechoso, 2:confirmado, 3:descartado',
  `fecha_formulario` date DEFAULT NULL COMMENT 'Fecha en que se llena el formulario',
  `hora_formulario` time DEFAULT NULL COMMENT 'Hora exacta en que se lleno el formulario',
  PRIMARY KEY (`id_form`),
  UNIQUE KEY `uk_vigmor_persona` (`tipo_identificacion`,`numero_identificacion`),
  KEY `fk_mortalidad_un` (`id_un`),
  KEY `fk_mortalidad_corregimiento` (`per_id_corregimiento`),
  KEY `fk_diagnostico1` (`id_diagnostico1`),
  KEY `fk_diagnostico2` (`id_diagnostico2`),
  KEY `fk_diagnostico3` (`id_diagnostico3`),
  KEY `fk_vigmor_servicio` (`id_servicio`),
  KEY `fk_vigmor_pais` (`per_id_pais`),
  KEY `index_numero_identifica` (`numero_identificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5995 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_malformacion_casos_diagnostico`
--
DROP VIEW IF EXISTS `vw_malformacion_casos_diagnostico`;
CREATE TABLE IF NOT EXISTS `vw_malformacion_casos_diagnostico` (
`fecha_reporte` date
,`id_un` int(11)
,`casos` bigint(21)
);
-- --------------------------------------------------------

--
-- Table structure for table `vw_ubicacion`
--

DROP TABLE IF EXISTS `vw_ubicacion`;
CREATE TABLE IF NOT EXISTS `vw_ubicacion` (
  `id_corregimiento` int(11) DEFAULT NULL,
  `nombre_corregimiento` varchar(100) DEFAULT NULL,
  `c_minsa` varchar(10) DEFAULT NULL,
  `id_distrito` int(11) DEFAULT NULL,
  `nombre_distrito` varchar(100) DEFAULT NULL,
  `d_minsa` varchar(10) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  `nombre_provincia` varchar(100) DEFAULT NULL,
  `p_minsa` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure for view `eno_comprimido`
--
DROP TABLE IF EXISTS `eno_comprimido`;

CREATE  VIEW `eno_comprimido` AS select `cun`.`id_un` AS `id_un`,`cun`.`nombre_un` AS `nombre_un`,`pro`.`id_provincia` AS `id_provincia`,`pro`.`nombre_provincia` AS `nombre_provincia`,`reg`.`id_region` AS `id_region`,`reg`.`nombre_region` AS `nombre_region`,`dis`.`id_distrito` AS `id_distrito`,`dis`.`nombre_distrito` AS `nombre_distrito`,`cor`.`id_corregimiento` AS `id_corregimiento`,`cor`.`nombre_corregimiento` AS `nombre_corregimiento`,`enc`.`semana_epi` AS `semana_epi`,`enc`.`anio` AS `anio`,`cat_evento`.`cie_10_1` AS `cie_10_1`,`cat_evento`.`nombre_evento` AS `nombre_evento`,`cat_evento`.`id_evento` AS `id_evento`,`cat_evento`.`id_gevento` AS `id_gevento`,`det`.`sexo` AS `sexo`,`ran`.`id_rango` AS `id_rango`,`ran`.`nombre_rango` AS `nombre_rango`,sum(`det`.`numero_casos`) AS `numero_casos`,`cun`.`sector_un` AS `sector_un`,`enc`.`id_enc` AS `id_enc`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 1)) then sum(`det`.`numero_casos`) else sum(0) end) AS `menor_uno_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 2)) then sum(`det`.`numero_casos`) else sum(0) end) AS `uno_cuatro_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 3)) then sum(`det`.`numero_casos`) else sum(0) end) AS `cinco_nueve_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 4)) then sum(`det`.`numero_casos`) else sum(0) end) AS `diez_catorce_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 5)) then sum(`det`.`numero_casos`) else sum(0) end) AS `quince_diecinueve_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 6)) then sum(`det`.`numero_casos`) else sum(0) end) AS `veinte_veinticuatro_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 7)) then sum(`det`.`numero_casos`) else sum(0) end) AS `veinticinco_treitaycuatro_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 8)) then sum(`det`.`numero_casos`) else sum(0) end) AS `treintaycinco_cuarentaynueve_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 9)) then sum(`det`.`numero_casos`) else sum(0) end) AS `cincuenta_cincuentaynueve_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 10)) then sum(`det`.`numero_casos`) else sum(0) end) AS `sesenta_sesentaycuantro_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 11)) then sum(`det`.`numero_casos`) else sum(0) end) AS `mas_sesentaycinco_m`,(case when ((`det`.`sexo` = _utf8'M') and (`ran`.`id_rango` = 12)) then sum(`det`.`numero_casos`) else sum(0) end) AS `ne_m`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 1)) then sum(`det`.`numero_casos`) else sum(0) end) AS `menor_uno_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 2)) then sum(`det`.`numero_casos`) else sum(0) end) AS `uno_cuatro_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 3)) then sum(`det`.`numero_casos`) else sum(0) end) AS `cinco_nueve_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 4)) then sum(`det`.`numero_casos`) else sum(0) end) AS `diez_catorce_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 5)) then sum(`det`.`numero_casos`) else sum(0) end) AS `quince_diecinueve_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 6)) then sum(`det`.`numero_casos`) else sum(0) end) AS `veinte_veinticuatro_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 7)) then sum(`det`.`numero_casos`) else sum(0) end) AS `veinticinco_treitaycuatro_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 8)) then sum(`det`.`numero_casos`) else sum(0) end) AS `treintaycinco_cuarentaynueve_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 9)) then sum(`det`.`numero_casos`) else sum(0) end) AS `cincuenta_cincuentaynueve_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 10)) then sum(`det`.`numero_casos`) else sum(0) end) AS `sesenta_sesentaycuantro_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 11)) then sum(`det`.`numero_casos`) else sum(0) end) AS `mas_sesentaycinco_f`,(case when ((`det`.`sexo` = _utf8'F') and (`ran`.`id_rango` = 12)) then sum(`det`.`numero_casos`) else sum(0) end) AS `ne_f` from ((((((((`eno_encabezado` `enc` join `eno_detalle` `det` on((`det`.`id_enc` = `enc`.`id_enc`))) join `cat_rango` `ran` on((`det`.`id_rango` = `ran`.`id_rango`))) join `cat_evento` on((`det`.`id_evento` = `cat_evento`.`id_evento`))) join `cat_unidad_notificadora` `cun` on((`enc`.`id_un` = `cun`.`id_un`))) join `cat_corregimiento` `cor` on((`cun`.`id_corregimiento` = `cor`.`id_corregimiento`))) join `cat_distrito` `dis` on((`cor`.`id_distrito` = `dis`.`id_distrito`))) join `cat_region_salud` `reg` on((`reg`.`id_region` = `dis`.`id_region`))) join `cat_provincia` `pro` on((`dis`.`id_provincia` = `pro`.`id_provincia`))) group by `enc`.`anio`,`enc`.`semana_epi`,`pro`.`nombre_provincia`,`reg`.`nombre_region`,`dis`.`nombre_distrito`,`cor`.`nombre_corregimiento`,`cun`.`nombre_un`,`cat_evento`.`cie_10_1`,`det`.`sexo` desc,`ran`.`id_rango`;

-- --------------------------------------------------------

--
-- Structure for view `eno_detallado`
--
DROP TABLE IF EXISTS `eno_detallado`;

CREATE  VIEW `eno_detallado` AS select `eno`.`id_un` AS `id_un`,`eno`.`sector_un` AS `sector_un`,`eno`.`nombre_un` AS `nombre_un`,`eno`.`id_provincia` AS `id_provincia`,`eno`.`nombre_provincia` AS `nombre_provincia`,`eno`.`id_region` AS `id_region`,`eno`.`nombre_region` AS `nombre_region`,`eno`.`id_distrito` AS `id_distrito`,`eno`.`nombre_distrito` AS `nombre_distrito`,`eno`.`id_corregimiento` AS `id_corregimiento`,`eno`.`nombre_corregimiento` AS `nombre_corregimiento`,`eno`.`semana_epi` AS `semana_epi`,`eno`.`anio` AS `anio`,`eno`.`cie_10_1` AS `cie_10_1`,`eno`.`nombre_evento` AS `nombre_evento`,`eno`.`id_evento` AS `id_evento`,`eno`.`id_gevento` AS `id_gevento`,sum(`eno`.`numero_casos`) AS `total`,sum(`eno`.`menor_uno_m`) AS `menor_uno_m`,sum(`eno`.`uno_cuatro_m`) AS `uno_cuatro_m`,sum(`eno`.`cinco_nueve_m`) AS `cinco_nueve_m`,sum(`eno`.`diez_catorce_m`) AS `diez_catorce_m`,sum(`eno`.`quince_diecinueve_m`) AS `quince_diecinueve_m`,sum(`eno`.`veinte_veinticuatro_m`) AS `veinte_veinticuatro_m`,sum(`eno`.`veinticinco_treitaycuatro_m`) AS `veinticinco_treitaycuatro_m`,sum(`eno`.`treintaycinco_cuarentaynueve_m`) AS `treintaycinco_cuarentaynueve_m`,sum(`eno`.`cincuenta_cincuentaynueve_m`) AS `cincuenta_cincuentaynueve_m`,sum(`eno`.`sesenta_sesentaycuantro_m`) AS `sesenta_sesentaycuantro_m`,sum(`eno`.`mas_sesentaycinco_m`) AS `mas_sesentaycinco_m`,sum(`eno`.`ne_m`) AS `ne_m`,sum(`eno`.`menor_uno_f`) AS `menor_uno_f`,sum(`eno`.`uno_cuatro_f`) AS `uno_cuatro_f`,sum(`eno`.`cinco_nueve_f`) AS `cinco_nueve_f`,sum(`eno`.`diez_catorce_f`) AS `diez_catorce_f`,sum(`eno`.`quince_diecinueve_f`) AS `quince_diecinueve_f`,sum(`eno`.`veinte_veinticuatro_f`) AS `veinte_veinticuatro_f`,sum(`eno`.`veinticinco_treitaycuatro_f`) AS `veinticinco_treitaycuatro_f`,sum(`eno`.`treintaycinco_cuarentaynueve_f`) AS `treintaycinco_cuarentaynueve_f`,sum(`eno`.`cincuenta_cincuentaynueve_f`) AS `cincuenta_cincuentaynueve_f`,sum(`eno`.`sesenta_sesentaycuantro_f`) AS `sesenta_sesentaycuantro_f`,sum(`eno`.`mas_sesentaycinco_f`) AS `mas_sesentaycinco_f`,sum(`eno`.`ne_f`) AS `ne_f` from `eno_detalle_nuevo` `eno` group by `eno`.`id_provincia`,`eno`.`id_region`,`eno`.`id_distrito`,`eno`.`id_corregimiento`,`eno`.`id_un`,`eno`.`id_evento`,`eno`.`semana_epi`,`eno`.`anio`;

-- --------------------------------------------------------

--
-- Structure for view `view_alerta_temprana`
--
DROP TABLE IF EXISTS `view_alerta_temprana`;

CREATE  VIEW `view_alerta_temprana` AS select `enc`.`semana_epi` AS `semana_epi`,`enc`.`anio` AS `anio`,dayofmonth(`enc`.`fecha_inic`) AS `DiaToma`,month(`enc`.`fecha_inic`) AS `MesToma`,year(`enc`.`fecha_inic`) AS `AnioToma`,`eve`.`id_evento` AS `id_diagnostico`,`eve`.`nombre_evento` AS `nombre_diagnostico`,`pro_un`.`id_provincia` AS `id_nivel_geo1`,`pro_un`.`nombre_provincia` AS `nombre_nivel_geo1`,`reg_un`.`id_region` AS `id_nivel_geo2`,`reg_un`.`nombre_region` AS `nombre_nivel_geo2`,`dis_un`.`id_distrito` AS `id_nivel_geo3`,`dis_un`.`nombre_distrito` AS `nombre_nivel_geo3`,`cor_un`.`id_corregimiento` AS `id_nivel_geo4`,`cor_un`.`nombre_corregimiento` AS `nombre_nivel_geo4`,`un`.`id_un` AS `id_establecimiento`,`un`.`nombre_un` AS `nombre_establecimiento`,sum(`det`.`numero_casos`) AS `numero_casos` from (((((((`eno_detalle` `det` join `eno_encabezado` `enc` on((`det`.`id_enc` = `enc`.`id_enc`))) join `cat_unidad_notificadora` `un` on((`enc`.`id_un` = `un`.`id_un`))) join `cat_corregimiento` `cor_un` on((`cor_un`.`id_corregimiento` = `un`.`id_corregimiento`))) join `cat_distrito` `dis_un` on((`dis_un`.`id_distrito` = `cor_un`.`id_distrito`))) join `cat_region_salud` `reg_un` on((`reg_un`.`id_region` = `dis_un`.`id_region`))) join `cat_provincia` `pro_un` on((`pro_un`.`id_provincia` = `dis_un`.`id_provincia`))) join `cat_evento` `eve` on((`eve`.`id_evento` = `det`.`id_evento`))) group by `enc`.`anio`,`enc`.`semana_epi`,`enc`.`id_un`,`det`.`id_evento`;

-- --------------------------------------------------------

--
-- Structure for view `view_eno`
--
DROP TABLE IF EXISTS `view_eno`;

CREATE  VIEW `view_eno` AS select `cat_region_salud`.`nombre_region` AS `nombre_region`,`cat_distrito`.`nombre_distrito` AS `nombre_distrito`,`cat_corregimiento`.`nombre_corregimiento` AS `nombre_corregimiento`,`cat_unidad_notificadora`.`nombre_un` AS `nombre_un`,`eno_encabezado`.`semana_epi` AS `semana_epi`,`eno_encabezado`.`anio` AS `anio`,`eno_encabezado`.`id_servicio` AS `servicio`,`cat_evento`.`cie_10_1` AS `cie_10_1`,`cat_evento`.`nombre_evento` AS `nombre_evento`,`eno_detalle`.`sexo` AS `sexo`,`cat_rango`.`id_rango` AS `id_rango`,`cat_rango`.`nombre_rango` AS `nombre_rango`,sum(`eno_detalle`.`numero_casos`) AS `numero_casos`,`cat_region_salud`.`id_region` AS `id_region`,`cat_distrito`.`id_distrito` AS `id_distrito`,`cat_corregimiento`.`id_corregimiento` AS `id_corregimiento`,`cat_unidad_notificadora`.`id_un` AS `id_un`,`cat_unidad_notificadora`.`sector_un` AS `sector_un` from (((((((`eno_encabezado` join `eno_detalle` on((`eno_detalle`.`id_enc` = `eno_encabezado`.`id_enc`))) join `cat_rango` on((`eno_detalle`.`id_rango` = `cat_rango`.`id_rango`))) join `cat_evento` on((`eno_detalle`.`id_evento` = `cat_evento`.`id_evento`))) join `cat_unidad_notificadora` on((`eno_encabezado`.`id_un` = `cat_unidad_notificadora`.`id_un`))) join `cat_region_salud` on((`cat_unidad_notificadora`.`id_region` = `cat_region_salud`.`id_region`))) join `cat_corregimiento` on((`cat_unidad_notificadora`.`id_corregimiento` = `cat_corregimiento`.`id_corregimiento`))) join `cat_distrito` on((`cat_corregimiento`.`id_distrito` = `cat_distrito`.`id_distrito`))) group by `eno_encabezado`.`semana_epi`,`cat_region_salud`.`nombre_region`,`cat_distrito`.`nombre_distrito`,`cat_corregimiento`.`nombre_corregimiento`,`cat_unidad_notificadora`.`nombre_un`,`cat_evento`.`cie_10_1`,`cat_evento`.`nombre_evento`,`eno_detalle`.`sexo` desc,`cat_rango`.`id_rango`;

-- --------------------------------------------------------

--
-- Structure for view `view_eno_matriz`
--
DROP TABLE IF EXISTS `view_eno_matriz`;

CREATE  VIEW `view_eno_matriz` AS select `view_eno`.`nombre_region` AS `nombre_region`,`view_eno`.`nombre_distrito` AS `nombre_distrito`,`view_eno`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_eno`.`nombre_un` AS `nombre_un`,`view_eno`.`cie_10_1` AS `cie_10_1`,`view_eno`.`nombre_evento` AS `nombre_evento`,`view_eno`.`semana_epi` AS `semana_epi`,`view_eno`.`anio` AS `anio`,`view_eno`.`servicio` AS `servicio`,`view_eno`.`sexo` AS `sexo`,`view_eno`.`id_rango` AS `id_rango`,`view_eno`.`id_corregimiento` AS `id_corregimiento`,`view_eno`.`id_distrito` AS `id_distrito`,`view_eno`.`id_region` AS `id_region`,`view_eno`.`id_un` AS `id_un`,`view_eno`.`nombre_rango` AS `nombre_rango`,`view_eno`.`numero_casos` AS `numero_casos`,`view_eno`.`sector_un` AS `sector_un`,if((`view_eno`.`id_rango` = 1),sum(`view_eno`.`numero_casos`),_utf8'0') AS `menor_uno`,if((`view_eno`.`id_rango` = 2),sum(`view_eno`.`numero_casos`),_utf8'0') AS `uno_cuatro`,if((`view_eno`.`id_rango` = 3),sum(`view_eno`.`numero_casos`),_utf8'0') AS `cinco_nueve`,if((`view_eno`.`id_rango` = 4),sum(`view_eno`.`numero_casos`),_utf8'0') AS `nueve_catorce`,if((`view_eno`.`id_rango` = 5),sum(`view_eno`.`numero_casos`),_utf8'0') AS `quince_diecinueve`,if((`view_eno`.`id_rango` = 6),sum(`view_eno`.`numero_casos`),_utf8'0') AS `veinte_veinticuatro`,if((`view_eno`.`id_rango` = 7),sum(`view_eno`.`numero_casos`),_utf8'0') AS `veinticinco_treinticuatro`,if((`view_eno`.`id_rango` = 8),sum(`view_eno`.`numero_casos`),_utf8'0') AS `treinticinco_cuarentinueve`,if((`view_eno`.`id_rango` = 9),sum(`view_eno`.`numero_casos`),_utf8'0') AS `cincuenta_cincuentinueve`,if((`view_eno`.`id_rango` = 10),sum(`view_eno`.`numero_casos`),_utf8'0') AS `sesenta_sesenticuatro`,if((`view_eno`.`id_rango` = 11),sum(`view_eno`.`numero_casos`),_utf8'0') AS `mayor_sesenticinco`,if((`view_eno`.`id_rango` = 12),sum(`view_eno`.`numero_casos`),_utf8'0') AS `N_E` from `view_eno` group by `view_eno`.`nombre_region`,`view_eno`.`nombre_distrito`,`view_eno`.`nombre_corregimiento`,`view_eno`.`nombre_un`,`view_eno`.`semana_epi`,`view_eno`.`anio`,`view_eno`.`cie_10_1`,`view_eno`.`nombre_evento`,`view_eno`.`sexo`,`view_eno`.`id_rango`;

-- --------------------------------------------------------

--
-- Structure for view `view_eno_reporte`
--
DROP TABLE IF EXISTS `view_eno_reporte`;

CREATE  VIEW `view_eno_reporte` AS select `view_eno_reporte_MF`.`nombre_region` AS `nombre_region`,`view_eno_reporte_MF`.`nombre_distrito` AS `nombre_distrito`,`view_eno_reporte_MF`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_eno_reporte_MF`.`nombre_un` AS `nombre_un`,`view_eno_reporte_MF`.`cie_10_1` AS `cie_10_1`,`view_eno_reporte_MF`.`nombre_evento` AS `nombre_evento`,`view_eno_reporte_MF`.`semana_epi` AS `semana_epi`,`view_eno_reporte_MF`.`id_corregimiento` AS `id_corregimiento`,`view_eno_reporte_MF`.`id_distrito` AS `id_distrito`,`view_eno_reporte_MF`.`id_region` AS `id_region`,`view_eno_reporte_MF`.`id_un` AS `id_un`,`view_eno_reporte_MF`.`servicio` AS `servicio`,`view_eno_reporte_MF`.`anio` AS `anio`,`view_eno_reporte_MF`.`sexo` AS `sexo`,`view_eno_reporte_MF`.`sector_un` AS `sector_un`,sum(`view_eno_reporte_MF`.`<1M`) AS `M<1`,sum(`view_eno_reporte_MF`.`<1F`) AS `F<1`,sum(`view_eno_reporte_MF`.`1-4M`) AS `M1-4`,sum(`view_eno_reporte_MF`.`1-4F`) AS `F1-4`,sum(`view_eno_reporte_MF`.`5-9M`) AS `M5-9`,sum(`view_eno_reporte_MF`.`5-9F`) AS `F5-9`,sum(`view_eno_reporte_MF`.`9-14M`) AS `M9-14`,sum(`view_eno_reporte_MF`.`9-14F`) AS `F9-14`,sum(`view_eno_reporte_MF`.`15-19M`) AS `M15-19`,sum(`view_eno_reporte_MF`.`15-19F`) AS `F15-19`,sum(`view_eno_reporte_MF`.`20-24M`) AS `M20-24`,sum(`view_eno_reporte_MF`.`20-24F`) AS `F20-24`,sum(`view_eno_reporte_MF`.`25-34M`) AS `M25-34`,sum(`view_eno_reporte_MF`.`25-34F`) AS `F25-34`,sum(`view_eno_reporte_MF`.`35-49M`) AS `M35-49`,sum(`view_eno_reporte_MF`.`35-49F`) AS `F35-49`,sum(`view_eno_reporte_MF`.`50-59M`) AS `M50-59`,sum(`view_eno_reporte_MF`.`50-59F`) AS `F50-59`,sum(`view_eno_reporte_MF`.`60-64M`) AS `M60-64`,sum(`view_eno_reporte_MF`.`60-64F`) AS `F60-64`,sum(`view_eno_reporte_MF`.`>65M`) AS `M>65`,sum(`view_eno_reporte_MF`.`>65F`) AS `F>65`,sum(`view_eno_reporte_MF`.`NEM`) AS `MNE`,sum(`view_eno_reporte_MF`.`NEF`) AS `FNE` from `view_eno_reporte_MF` group by `view_eno_reporte_MF`.`nombre_region`,`view_eno_reporte_MF`.`nombre_distrito`,`view_eno_reporte_MF`.`nombre_corregimiento`,`view_eno_reporte_MF`.`nombre_un`,`view_eno_reporte_MF`.`cie_10_1`,`view_eno_reporte_MF`.`nombre_evento`,`view_eno_reporte_MF`.`semana_epi`,`view_eno_reporte_MF`.`id_corregimiento`,`view_eno_reporte_MF`.`id_distrito`,`view_eno_reporte_MF`.`id_region`,`view_eno_reporte_MF`.`id_un`,`view_eno_reporte_MF`.`anio`;

-- --------------------------------------------------------

--
-- Structure for view `view_eno_reporte_MF`
--
DROP TABLE IF EXISTS `view_eno_reporte_MF`;

CREATE  VIEW `view_eno_reporte_MF` AS select `view_eno_matriz`.`nombre_region` AS `nombre_region`,`view_eno_matriz`.`nombre_distrito` AS `nombre_distrito`,`view_eno_matriz`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_eno_matriz`.`nombre_un` AS `nombre_un`,`view_eno_matriz`.`cie_10_1` AS `cie_10_1`,`view_eno_matriz`.`nombre_evento` AS `nombre_evento`,`view_eno_matriz`.`semana_epi` AS `semana_epi`,`view_eno_matriz`.`id_corregimiento` AS `id_corregimiento`,`view_eno_matriz`.`id_distrito` AS `id_distrito`,`view_eno_matriz`.`id_region` AS `id_region`,`view_eno_matriz`.`id_un` AS `id_un`,`view_eno_matriz`.`servicio` AS `servicio`,`view_eno_matriz`.`anio` AS `anio`,`view_eno_matriz`.`sexo` AS `sexo`,`view_eno_matriz`.`sector_un` AS `sector_un`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`menor_uno`),_utf8'0') AS `<1M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`menor_uno`),_utf8'0') AS `<1F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`uno_cuatro`),_utf8'0') AS `1-4M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`uno_cuatro`),_utf8'0') AS `1-4F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`cinco_nueve`),_utf8'0') AS `5-9M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`cinco_nueve`),_utf8'0') AS `5-9F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`nueve_catorce`),_utf8'0') AS `9-14M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`nueve_catorce`),_utf8'0') AS `9-14F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`quince_diecinueve`),_utf8'0') AS `15-19M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`quince_diecinueve`),_utf8'0') AS `15-19F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`veinte_veinticuatro`),_utf8'0') AS `20-24M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`veinte_veinticuatro`),_utf8'0') AS `20-24F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`veinticinco_treinticuatro`),_utf8'0') AS `25-34M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`veinticinco_treinticuatro`),_utf8'0') AS `25-34F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`treinticinco_cuarentinueve`),_utf8'0') AS `35-49M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`treinticinco_cuarentinueve`),_utf8'0') AS `35-49F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`cincuenta_cincuentinueve`),_utf8'0') AS `50-59M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`cincuenta_cincuentinueve`),_utf8'0') AS `50-59F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`sesenta_sesenticuatro`),_utf8'0') AS `60-64M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`sesenta_sesenticuatro`),_utf8'0') AS `60-64F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`mayor_sesenticinco`),_utf8'0') AS `>65M`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`mayor_sesenticinco`),_utf8'0') AS `>65F`,if((`view_eno_matriz`.`sexo` = _utf8'M'),sum(`view_eno_matriz`.`N_E`),_utf8'0') AS `NEM`,if((`view_eno_matriz`.`sexo` = _utf8'F'),sum(`view_eno_matriz`.`N_E`),_utf8'0') AS `NEF` from `view_eno_matriz` group by `view_eno_matriz`.`nombre_region`,`view_eno_matriz`.`nombre_distrito`,`view_eno_matriz`.`nombre_corregimiento`,`view_eno_matriz`.`nombre_un`,`view_eno_matriz`.`cie_10_1`,`view_eno_matriz`.`nombre_evento`,`view_eno_matriz`.`semana_epi`,`view_eno_matriz`.`anio`,`view_eno_matriz`.`sexo`;

-- --------------------------------------------------------

--
-- Structure for view `view_evento_egreso`
--
DROP TABLE IF EXISTS `view_evento_egreso`;

CREATE  VIEW `view_evento_egreso` AS select `egre`.`id_rae` AS `id_rae`,`egre`.`id_evento` AS `id_evento`,`neven`.`cod_ref_minsa` AS `ciex`,`neven`.`nombre_evento` AS `nombre_evento`,`f`.`id_un` AS `id_un`,`u`.`cod_ref_minsa` AS `cod_ref_minsa`,`u`.`nombre_un` AS `nombre_un`,`u`.`id_region` AS `id_region`,`reg`.`nombre_region` AS `nombre_region`,`f`.`id_servicio` AS `id_servicio`,`s`.`codigo_servicio` AS `codigo_servicio`,`s`.`nombre_servicio` AS `nombre_servicio`,`m`.`nombre_personal_medico` AS `nombre_personal_medico`,`f`.`nombre_funcionario` AS `nombre_funcionario`,`f`.`nombre_registra` AS `nombre_registra`,`f`.`fecha_cierre` AS `fecha_cierre`,`f`.`fecha_admision` AS `fecha_admision`,`f`.`fecha_egreso` AS `fecha_egreso`,`f`.`tipo_identificacion` AS `tipo_identificacion`,`i`.`nombre_tipo` AS `nombre_tipo`,`f`.`numero_identificacion` AS `numero_identificacion`,`f`.`per_edad` AS `per_edad`,`e`.`nombre` AS `nombre`,`f`.`per_tipo_edad` AS `per_tipo_edad`,`sex`.`sexo` AS `sexo`,`ocu`.`nombre_ocupacion` AS `nombre_ocupacion`,`f`.`per_id_pais` AS `per_id_pais`,`p`.`nombre_pais` AS `nombre_pais`,`f`.`per_id_corregimiento` AS `per_id_corregimiento`,`corr`.`nombre_corregimiento` AS `nombre_corregimiento`,`f`.`per_direccion` AS `per_direccion`,`f`.`per_dir_referencia` AS `per_dir_referencia`,`f`.`per_id_corregimiento_transitoria` AS `per_id_corregimiento_transitoria`,`cor`.`id_corregimiento` AS `id_corregimiento`,`f`.`per_no_hay_dir_transitoria` AS `per_no_hay_dir_transitoria`,`f`.`id_tipo_paciente` AS `id_tipo_paciente`,`pac`.`nombre_tipo_paciente` AS `nombre_tipo_paciente`,`f`.`hospitalizacion` AS `hospitalizacion`,`f`.`id_condicion_salida` AS `id_condicion_salida`,`cond`.`nombre_condicion_salida` AS `nombre_condicion_salida`,`f`.`motivo_salida` AS `motivo_salida`,`f`.`muerte_sop` AS `muerte_sop`,`f`.`autopsia` AS `autopsia`,`f`.`fecha_autopsia` AS `fecha_autopsia` from (((((((((((((((`rae_egreso` `egre` left join `rae_form` `f` on((`egre`.`id_rae` = `f`.`id_rae`))) left join `cat_unidad_notificadora` `u` on((`f`.`id_un` = `u`.`id_un`))) left join `cat_region_salud` `reg` on((`u`.`id_region` = `reg`.`id_region`))) left join `cat_servicio_rae` `s` on((`f`.`id_servicio` = `s`.`id_servicio`))) left join `tbl_personal_medico` `m` on((`f`.`id_personal_medico` = `m`.`id_personal_medico`))) left join `cat_tipo_identidad` `i` on((`f`.`tipo_identificacion` = `i`.`id_tipo_identidad`))) left join `cat_edad` `e` on((`f`.`per_tipo_edad` = `e`.`tipo_edad`))) left join `cat_pais` `p` on((`f`.`per_id_pais` = `p`.`id_pais`))) left join `cat_corregimiento` `corr` on((`f`.`per_id_pais` = `corr`.`id_corregimiento`))) left join `cat_corregimiento` `cor` on((`f`.`per_id_corregimiento_transitoria` = `cor`.`id_corregimiento`))) left join `cat_tipo_paciente` `pac` on((`f`.`id_tipo_paciente` = `pac`.`id_tipo_paciente`))) left join `cat_evento` `neven` on((`egre`.`id_evento` = `neven`.`id_evento`))) left join `cat_condicion_salida` `cond` on((`f`.`id_condicion_salida` = `cond`.`id_condicion_salida`))) left join `tbl_persona` `sex` on((`f`.`numero_identificacion` = `sex`.`numero_identificacion`))) left join `cat_ocupacion` `ocu` on((`sex`.`id_ocupacion` = `ocu`.`id_ocupacion`)));

-- --------------------------------------------------------

--
-- Structure for view `view_flu_analysis`
--
DROP TABLE IF EXISTS `view_flu_analysis`;

CREATE  VIEW `view_flu_analysis` AS (select `uceti`.`id_flureg` AS `id_formulario`,ifnull(`uceti`.`id_un`,' ') AS `id_un`,ifnull(`un`.`nombre_un`,'No disponible') AS `unidad_notificadora`,`uceti`.`nombre_registra` AS `nombre_registra`,`uceti`.`nombre_investigador` AS `nombre_investigador`,date_format(`uceti`.`fecha_formulario`,'%d-%m-%Y') AS `fecha_formulario`,`uceti`.`tipo_identificacion` AS `tipo_identificacion`,`uceti`.`numero_identificacion` AS `numero_identificacion`,`uceti`.`per_tipo_paciente` AS `per_tipo_paciente`,`uceti`.`per_hospitalizado` AS `per_hospitalizado`,ifnull(`uceti`.`per_hospitalizado_lugar`,' ') AS `per_hospitalizado_lugar`,`uceti`.`per_asegurado` AS `per_asegurado`,`per`.`primer_nombre` AS `primer_nombre`,`per`.`segundo_nombre` AS `segundo_nombre`,`per`.`primer_apellido` AS `primer_apellido`,`per`.`segundo_apellido` AS `segundo_apellido`,date_format(`per`.`fecha_nacimiento`,'%d-%m-%Y') AS `fecha_nacimiento`,`per`.`edad` AS `edad`,`per`.`tipo_edad` AS `tipo_edad`,`per`.`sexo` AS `sexo`,`per`.`nombre_responsable` AS `nombre_responsable`,`pro`.`id_provincia` AS `id_provincia`,`pro`.`nombre_provincia` AS `nombre_provincia`,`re`.`id_region` AS `id_region`,`re`.`nombre_region` AS `nombre_region`,`corr`.`id_corregimiento` AS `id_corregimiento`,`corr`.`nombre_corregimiento` AS `nombre_corregimiento`,`dis`.`id_distrito` AS `id_distrito`,`dis`.`nombre_distrito` AS `nombre_distrito`,`per`.`dir_referencia` AS `dir_referencia`,`per`.`dir_trabajo` AS `dir_trabajo`,`per`.`tel_residencial` AS `otra_direccion`,`uceti`.`vac_tarjeta` AS `vac_tarjeta`,`uceti`.`vac_segun_esquema` AS `vac_segun_esquema`,`uceti`.`vac_fecha_anio_previo` AS `vac_fecha_anio_previo`,`uceti`.`vac_fecha_ultima_dosis` AS `vac_fecha_pen_ultima_dosis`,ifnull(`uceti`.`riesgo_embarazo`,' ') AS `riesgo_embarazo`,ifnull(`uceti`.`riesgo_trimestre`,' ') AS `riesgo_trimestre`,`uceti`.`riesgo_enf_cronica` AS `riesgo_enf_cronica`,`uceti`.`riesgo_profesional` AS `riesgo_profesional`,ifnull(`uceti`.`riesgo_pro_cual`,' ') AS `riesgo_pro_cual`,`uceti`.`riesgo_viaje` AS `riesgo_viaje`,`uceti`.`riesgo_viaje_donde` AS `riesgo_viaje_donde`,`uceti`.`riesgo_contacto_confirmado` AS `riesgo_contacto_confirmado`,ifnull(`uceti`.`riesgo_contacto_tipo`,' ') AS `riesgo_contacto_tipo`,`uceti`.`riesgo_aislamiento` AS `riesgo_aislamiento`,`uceti`.`riesgo_contacto_nombre` AS `riesgo_contacto_nombre`,(case when (`uceti`.`eve_sindrome` = 1) then '1' when (`uceti`.`eve_centinela` = 1) then '2' when (`uceti`.`eve_inusitado` = 1) then '3' when (`uceti`.`eve_imprevisto` = 1) then '4' when (`uceti`.`eve_excesivo` = 1) then '5' when (`uceti`.`eve_conglomerado` = 1) then '6' when (`uceti`.`eve_neumo_bacteriana` = 1) then '7' else 'Sin dato' end) AS `id_tipo_evento`,(case when (`uceti`.`eve_sindrome` = 1) then 'S?ndrome Gripal' when (`uceti`.`eve_centinela` = 1) then 'IRAG Centinela' when (`uceti`.`eve_inusitado` = 1) then 'IRAG Inusitado' when (`uceti`.`eve_imprevisto` = 1) then 'IRAG Imprevisto' when (`uceti`.`eve_excesivo` = 1) then 'IRAG N Excesivo' when (`uceti`.`eve_conglomerado` = 1) then 'IRAG Conglomerado' when (`uceti`.`eve_neumo_bacteriana` = 1) then 'Neumon?a Bacteriana' else 'Sin dato' end) AS `tipo_evento`,`ev`.`cie_10_1` AS `cie_10_1`,`ev`.`nombre_evento` AS `nombre_evento`,date_format(`uceti`.`fecha_inicio_sintoma`,'%d-%m-%Y') AS `fecha_inicio_sintoma`,ifnull(date_format(`uceti`.`fecha_hospitalizacion`,'%d-%m-%Y'),' ') AS `fecha_hospitalizacion`,date_format(`uceti`.`fecha_notificacion`,'%d-%m-%Y') AS `fecha_notificacion`,ifnull(date_format(`uceti`.`fecha_egreso`,'%d-%m-%Y'),' ') AS `fecha_egreso`,ifnull(date_format(`uceti`.`fecha_defuncion`,'%d-%m-%Y'),' ') AS `fecha_defuncion`,`uceti`.`antibiotico` AS `antibiotico`,ifnull(`uceti`.`antibiotico_cual`,' ') AS `antibiotico_cual`,ifnull(date_format(`uceti`.`antibiotico_fecha`,'%d-%m-%Y'),' ') AS `antibiotico_fecha`,`uceti`.`antiviral` AS `antiviral`,ifnull(`uceti`.`antiviral_cual`,' ') AS `antiviral_cual`,ifnull(date_format(`uceti`.`antiviral_fecha`,'%d-%m-%Y'),' ') AS `antiviral_fecha`,`uceti`.`sintoma_fiebre` AS `sintoma_fiebre`,ifnull(date_format(`uceti`.`fecha_fiebre`,'%d-%m-%Y'),' ') AS `fecha_fiebre`,`uceti`.`sintoma_tos` AS `sintoma_tos`,ifnull(date_format(`uceti`.`fecha_tos`,'%d-%m-%Y'),' ') AS `fecha_tos`,`uceti`.`sintoma_garganta` AS `sintoma_garganta`,ifnull(date_format(`uceti`.`fecha_garganta`,'%d-%m-%Y'),' ') AS `fecha_garganta`,`uceti`.`sintoma_rinorrea` AS `sintoma_rinorrea`,ifnull(date_format(`uceti`.`fecha_rinorrea`,'%d-%m-%Y'),' ') AS `fecha_rinorrea`,`uceti`.`sintoma_respiratoria` AS `sintoma_respiratoria`,ifnull(date_format(`uceti`.`fecha_respiratoria`,'%d-%m-%Y'),' ') AS `fecha_respiratoria`,`uceti`.`sintoma_otro` AS `sintoma_otro`,ifnull(date_format(`uceti`.`fecha_otro`,'%d-%m-%Y'),' ') AS `fecha_otro`,ifnull(`uceti`.`sintoma_nombre_otro`,' ') AS `sintoma_nombre_otro`,`uceti`.`torax_condensacion` AS `torax_condensacion`,`uceti`.`torax_derrame` AS `torax_derrame`,`uceti`.`torax_broncograma` AS `torax_broncograma`,`uceti`.`torax_infiltrado` AS `torax_infiltrado`,`uceti`.`torax_otro` AS `torax_otro`,`uceti`.`torax_nombre_otro` AS `torax_nombre_otro`,`uceti`.`semana_epi` AS `semana_epi`,`uceti`.`anio` AS `anio` from (((((((((`flureg_form` `uceti` join `tbl_persona` `per` on(((`per`.`tipo_identificacion` = `uceti`.`tipo_identificacion`) and (`per`.`numero_identificacion` = `uceti`.`numero_identificacion`)))) left join `cat_edad` `c_edad` on((`per`.`tipo_edad` = `c_edad`.`tipo_edad`))) left join `cat_tipo_identidad` `tipo_id` on((`per`.`tipo_identificacion` = `tipo_id`.`id_tipo_identidad`))) left join `cat_unidad_notificadora` `un` on((`un`.`id_un` = `uceti`.`id_un`))) left join `cat_region_salud` `re` on((`re`.`id_region` = `per`.`id_region`))) left join `cat_corregimiento` `corr` on((`per`.`id_corregimiento` = `corr`.`id_corregimiento`))) left join `cat_distrito` `dis` on((`corr`.`id_distrito` = `dis`.`id_distrito`))) left join `cat_provincia` `pro` on((`dis`.`id_provincia` = `pro`.`id_provincia`))) left join `cat_evento` `ev` on((`ev`.`id_evento` = `uceti`.`id_evento`))) where (`uceti`.`pendiente_uceti` = '1'));

-- --------------------------------------------------------

--
-- Structure for view `view_flureg`
--
DROP TABLE IF EXISTS `view_flureg`;

CREATE  VIEW `view_flureg` AS (select `uceti`.`id_flureg` AS `id_formulario`,ifnull(`uceti`.`id_un`,' ') AS `id_un`,ifnull(`un`.`nombre_un`,'No disponible') AS `unidad_notificadora`,`uceti`.`nombre_registra` AS `nombre_registra`,`uceti`.`nombre_investigador` AS `nombre_investigador`,date_format(`uceti`.`fecha_formulario`,'%d-%m-%Y') AS `fecha_formulario`,`uceti`.`tipo_identificacion` AS `tipo_identificacion`,`uceti`.`numero_identificacion` AS `numero_identificacion`,`uceti`.`per_tipo_paciente` AS `per_tipo_paciente`,`uceti`.`per_hospitalizado` AS `per_hospitalizado`,ifnull(`uceti`.`per_hospitalizado_lugar`,' ') AS `per_hospitalizado_lugar`,`uceti`.`per_asegurado` AS `per_asegurado`,`per`.`primer_nombre` AS `primer_nombre`,`per`.`segundo_nombre` AS `segundo_nombre`,`per`.`primer_apellido` AS `primer_apellido`,`per`.`segundo_apellido` AS `segundo_apellido`,date_format(`per`.`fecha_nacimiento`,'%d-%m-%Y') AS `fecha_nacimiento`,`per`.`edad` AS `edad`,`per`.`tipo_edad` AS `tipo_edad`,`per`.`sexo` AS `sexo`,`per`.`nombre_responsable` AS `nombre_responsable`,`pro`.`id_provincia` AS `id_provincia`,`pro`.`nombre_provincia` AS `nombre_provincia`,`re`.`id_region` AS `id_region`,`re`.`nombre_region` AS `nombre_region`,`corr`.`id_corregimiento` AS `id_corregimiento`,`corr`.`nombre_corregimiento` AS `nombre_corregimiento`,`dis`.`id_distrito` AS `id_distrito`,`dis`.`nombre_distrito` AS `nombre_distrito`,`per`.`dir_referencia` AS `dir_referencia`,`per`.`dir_trabajo` AS `dir_trabajo`,`per`.`tel_residencial` AS `otra_direccion`,`uceti`.`vac_tarjeta` AS `vac_tarjeta`,`uceti`.`vac_segun_esquema` AS `vac_segun_esquema`,`uceti`.`vac_fecha_anio_previo` AS `vac_fecha_anio_previo`,`uceti`.`vac_fecha_ultima_dosis` AS `vac_fecha_pen_ultima_dosis`,ifnull(`uceti`.`riesgo_embarazo`,' ') AS `riesgo_embarazo`,ifnull(`uceti`.`riesgo_trimestre`,' ') AS `riesgo_trimestre`,`uceti`.`riesgo_enf_cronica` AS `riesgo_enf_cronica`,`uceti`.`riesgo_profesional` AS `riesgo_profesional`,ifnull(`uceti`.`riesgo_pro_cual`,' ') AS `riesgo_pro_cual`,`uceti`.`riesgo_viaje` AS `riesgo_viaje`,`uceti`.`riesgo_viaje_donde` AS `riesgo_viaje_donde`,`uceti`.`riesgo_contacto_confirmado` AS `riesgo_contacto_confirmado`,ifnull(`uceti`.`riesgo_contacto_tipo`,' ') AS `riesgo_contacto_tipo`,`uceti`.`riesgo_aislamiento` AS `riesgo_aislamiento`,`uceti`.`riesgo_contacto_nombre` AS `riesgo_contacto_nombre`,(case when (`uceti`.`eve_sindrome` = 1) then '1' when (`uceti`.`eve_centinela` = 1) then '2' when (`uceti`.`eve_inusitado` = 1) then '3' when (`uceti`.`eve_imprevisto` = 1) then '4' when (`uceti`.`eve_excesivo` = 1) then '5' when (`uceti`.`eve_conglomerado` = 1) then '6' when (`uceti`.`eve_neumo_bacteriana` = 1) then '7' else 'Sin dato' end) AS `id_tipo_evento`,(case when (`uceti`.`eve_sindrome` = 1) then 'S?ndrome Gripal' when (`uceti`.`eve_centinela` = 1) then 'IRAG Centinela' when (`uceti`.`eve_inusitado` = 1) then 'IRAG Inusitado' when (`uceti`.`eve_imprevisto` = 1) then 'IRAG Imprevisto' when (`uceti`.`eve_excesivo` = 1) then 'IRAG N Excesivo' when (`uceti`.`eve_conglomerado` = 1) then 'IRAG Conglomerado' when (`uceti`.`eve_neumo_bacteriana` = 1) then 'Neumon?a Bacteriana' else 'Sin dato' end) AS `tipo_evento`,`ev`.`cie_10_1` AS `cie_10_1`,`ev`.`nombre_evento` AS `nombre_evento`,date_format(`uceti`.`fecha_inicio_sintoma`,'%d-%m-%Y') AS `fecha_inicio_sintoma`,ifnull(date_format(`uceti`.`fecha_hospitalizacion`,'%d-%m-%Y'),' ') AS `fecha_hospitalizacion`,date_format(`uceti`.`fecha_notificacion`,'%d-%m-%Y') AS `fecha_notificacion`,ifnull(date_format(`uceti`.`fecha_egreso`,'%d-%m-%Y'),' ') AS `fecha_egreso`,ifnull(date_format(`uceti`.`fecha_defuncion`,'%d-%m-%Y'),' ') AS `fecha_defuncion`,`uceti`.`antibiotico` AS `antibiotico`,ifnull(`uceti`.`antibiotico_cual`,' ') AS `antibiotico_cual`,ifnull(date_format(`uceti`.`antibiotico_fecha`,'%d-%m-%Y'),' ') AS `antibiotico_fecha`,`uceti`.`antiviral` AS `antiviral`,ifnull(`uceti`.`antiviral_cual`,' ') AS `antiviral_cual`,ifnull(date_format(`uceti`.`antiviral_fecha`,'%d-%m-%Y'),' ') AS `antiviral_fecha`,`uceti`.`sintoma_fiebre` AS `sintoma_fiebre`,ifnull(date_format(`uceti`.`fecha_fiebre`,'%d-%m-%Y'),' ') AS `fecha_fiebre`,`uceti`.`sintoma_tos` AS `sintoma_tos`,ifnull(date_format(`uceti`.`fecha_tos`,'%d-%m-%Y'),' ') AS `fecha_tos`,`uceti`.`sintoma_garganta` AS `sintoma_garganta`,ifnull(date_format(`uceti`.`fecha_garganta`,'%d-%m-%Y'),' ') AS `fecha_garganta`,`uceti`.`sintoma_rinorrea` AS `sintoma_rinorrea`,ifnull(date_format(`uceti`.`fecha_rinorrea`,'%d-%m-%Y'),' ') AS `fecha_rinorrea`,`uceti`.`sintoma_respiratoria` AS `sintoma_respiratoria`,ifnull(date_format(`uceti`.`fecha_respiratoria`,'%d-%m-%Y'),' ') AS `fecha_respiratoria`,`uceti`.`sintoma_otro` AS `sintoma_otro`,ifnull(date_format(`uceti`.`fecha_otro`,'%d-%m-%Y'),' ') AS `fecha_otro`,ifnull(`uceti`.`sintoma_nombre_otro`,' ') AS `sintoma_nombre_otro`,`uceti`.`torax_condensacion` AS `torax_condensacion`,`uceti`.`torax_derrame` AS `torax_derrame`,`uceti`.`torax_broncograma` AS `torax_broncograma`,`uceti`.`torax_infiltrado` AS `torax_infiltrado`,`uceti`.`torax_otro` AS `torax_otro`,`uceti`.`torax_nombre_otro` AS `torax_nombre_otro`,`uceti`.`semana_epi` AS `semana_epi`,`uceti`.`anio` AS `anio` from (((((((((`flureg_form` `uceti` join `tbl_persona` `per` on(((`per`.`tipo_identificacion` = `uceti`.`tipo_identificacion`) and (`per`.`numero_identificacion` = `uceti`.`numero_identificacion`)))) left join `cat_edad` `c_edad` on((`per`.`tipo_edad` = `c_edad`.`tipo_edad`))) left join `cat_tipo_identidad` `tipo_id` on((`per`.`tipo_identificacion` = `tipo_id`.`id_tipo_identidad`))) left join `cat_unidad_notificadora` `un` on((`un`.`id_un` = `uceti`.`id_un`))) left join `cat_region_salud` `re` on((`re`.`id_region` = `per`.`id_region`))) left join `cat_corregimiento` `corr` on((`per`.`id_corregimiento` = `corr`.`id_corregimiento`))) left join `cat_distrito` `dis` on((`corr`.`id_distrito` = `dis`.`id_distrito`))) left join `cat_provincia` `pro` on((`dis`.`id_provincia` = `pro`.`id_provincia`))) left join `cat_evento` `ev` on((`ev`.`id_evento` = `uceti`.`id_evento`))) where (`uceti`.`pendiente_uceti` = '1'));

-- --------------------------------------------------------

--
-- Structure for view `view_notic`
--
DROP TABLE IF EXISTS `view_notic`;

CREATE  VIEW `view_notic` AS select `reg`.`nombre_region` AS `nombre_region`,`dis`.`nombre_distrito` AS `nombre_distrito`,`cor`.`nombre_corregimiento` AS `nombre_corregimiento`,`cun`.`nombre_un` AS `nombre_unidad`,`eve`.`cie_10_1` AS `cie_10`,`eve`.`nombre_evento` AS `nombre_evento`,`eve`.`id_gevento` AS `id_gevento`,`reg`.`id_region` AS `id_region`,`dis`.`id_distrito` AS `id_distrito`,`cor`.`id_corregimiento` AS `id_corregimiento`,`cun`.`id_un` AS `id_unidad`,`cun`.`sector_un` AS `sector_un`,`per`.`sexo` AS `sexo`,`ni`.`per_id_pais` AS `per_id_pais`,`ni`.`per_id_corregimiento` AS `per_id_corregimiento`,`ni`.`semana_epi` AS `semana`,date_format(`ni`.`fecha_inicio_sintomas`,_utf8'%Y') AS `anio`,`per`.`primer_nombre` AS `primer_nombre`,`ni`.`per_edad` AS `edad`,`ni`.`per_tipo_edad` AS `tipo_edad`,if((`ni`.`per_tipo_edad` > 2),(case when ((`ni`.`per_edad` >= 1) and (`ni`.`per_edad` <= 4)) then _utf8'1-4' when ((`ni`.`per_edad` > 4) and (`ni`.`per_edad` <= 9)) then _utf8'5-9' when ((`ni`.`per_edad` > 9) and (`ni`.`per_edad` <= 14)) then _utf8'10-14' when ((`ni`.`per_edad` > 14) and (`ni`.`per_edad` <= 19)) then _utf8'15-19' when ((`ni`.`per_edad` > 19) and (`ni`.`per_edad` <= 24)) then _utf8'20-24' when ((`ni`.`per_edad` > 24) and (`ni`.`per_edad` <= 34)) then _utf8'25-34' when ((`ni`.`per_edad` > 34) and (`ni`.`per_edad` <= 49)) then _utf8'35-49' when ((`ni`.`per_edad` > 49) and (`ni`.`per_edad` <= 59)) then _utf8'50-59' when ((`ni`.`per_edad` > 59) and (`ni`.`per_edad` <= 64)) then _utf8'60-64' when (`ni`.`per_edad` > 65) then _utf8'65 ó +' else _utf8'N/E' end),_utf8'<1') AS `rango` from (((((((`notic_form` `ni` join `cat_unidad_notificadora` `cun` on((`ni`.`id_un` = `cun`.`id_un`))) join `cat_region_salud` `reg` on((`cun`.`id_region` = `reg`.`id_region`))) join `cat_corregimiento` `cor` on((`cun`.`id_corregimiento` = `cor`.`id_corregimiento`))) join `cat_distrito` `dis` on((`cor`.`id_distrito` = `dis`.`id_distrito`))) join `cat_provincia` `pas` on((`reg`.`id_provincia` = `pas`.`id_provincia`))) join `cat_evento` `eve` on((`ni`.`id_diagnostico1` = `eve`.`id_evento`))) join `tbl_persona` `per` on(((`ni`.`tipo_identificacion` = `per`.`tipo_identificacion`) and (`ni`.`numero_identificacion` = `per`.`numero_identificacion`))));

-- --------------------------------------------------------

--
-- Structure for view `view_notic_casos`
--
DROP TABLE IF EXISTS `view_notic_casos`;

CREATE  VIEW `view_notic_casos` AS select `vni`.`nombre_region` AS `nombre_region`,`vni`.`nombre_distrito` AS `nombre_distrito`,`vni`.`nombre_corregimiento` AS `nombre_corregimiento`,`vni`.`nombre_unidad` AS `nombre_unidad`,`vni`.`cie_10` AS `cie_10`,`vni`.`id_gevento` AS `id_gevento`,`vni`.`nombre_evento` AS `nombre_evento`,`vni`.`sexo` AS `sexo`,`vni`.`semana` AS `semana`,`vni`.`anio` AS `anio`,`vni`.`rango` AS `rango`,`vni`.`per_id_pais` AS `per_id_pais`,`vni`.`per_id_corregimiento` AS `per_id_corregimiento`,`vni`.`id_unidad` AS `id_unidad`,`vni`.`sector_un` AS `sector_un`,`vni`.`id_corregimiento` AS `id_corregimiento`,`vni`.`id_distrito` AS `id_distrito`,`vni`.`id_region` AS `id_region`,count(`vni`.`rango`) AS `casos` from `view_notic` `vni` group by `vni`.`nombre_region`,`vni`.`nombre_distrito`,`vni`.`nombre_unidad`,`vni`.`nombre_evento`,`vni`.`sexo`,`vni`.`semana`,`vni`.`anio`,`vni`.`rango`;

-- --------------------------------------------------------

--
-- Structure for view `view_notic_matriz`
--
DROP TABLE IF EXISTS `view_notic_matriz`;

CREATE  VIEW `view_notic_matriz` AS select `p`.`id_provincia` AS `id_provincia`,`p`.`nombre_provincia` AS `nombre_provincia`,`r`.`id_region` AS `id_region`,`r`.`nombre_region` AS `nombre_region`,`d`.`id_distrito` AS `id_distrito`,`d`.`nombre_distrito` AS `nombre_distrito`,`c`.`id_corregimiento` AS `id_corregimiento`,`c`.`nombre_corregimiento` AS `nombre_corregimiento`,`T1`.`id_un` AS `id_un`,`cun`.`nombre_un` AS `nombre_un`,`cun`.`sector_un` AS `sector_un`,`e`.`id_evento` AS `id_evento`,`e`.`cie_10_1` AS `cie_10`,`e`.`nombre_evento` AS `nombre_evento`,`e`.`id_gevento` AS `id_gevento`,`T1`.`anio` AS `anio`,`T1`.`semana_epi` AS `semana_epi`,`T1`.`per_id_pais` AS `per_id_pais`,`T1`.`per_id_corregimiento` AS `per_id_corregimiento`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `m_01`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 1) and (`T1`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `m_14`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 5) and (`T1`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `m_59`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 10) and (`T1`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `m_1014`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 15) and (`T1`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `m_1519`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 20) and (`T1`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `m_2024`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 25) and (`T1`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `m_2534`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 35) and (`T1`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `m_3549`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 50) and (`T1`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `m_5059`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 60) and (`T1`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `m_6064`,(case when ((`per`.`sexo` = 'M') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `m_65mas`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `f_01`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 1) and (`T1`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `f_14`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 5) and (`T1`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `f_59`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 10) and (`T1`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `f_1014`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 15) and (`T1`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `f_1519`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 20) and (`T1`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `f_2024`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 25) and (`T1`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `f_2534`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 35) and (`T1`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `f_3549`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 50) and (`T1`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `f_5059`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 60) and (`T1`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `f_6064`,(case when ((`per`.`sexo` = 'F') and (`T1`.`per_tipo_edad` > 2) and (`T1`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `f_65mas` from (((((((`notic_form` `T1` left join `cat_unidad_notificadora` `cun` on((`T1`.`id_un` = `cun`.`id_un`))) left join `cat_corregimiento` `c` on((`cun`.`id_corregimiento` = `c`.`id_corregimiento`))) left join `cat_distrito` `d` on((`c`.`id_distrito` = `d`.`id_distrito`))) left join `cat_region_salud` `r` on((`d`.`id_region` = `r`.`id_region`))) left join `cat_provincia` `p` on((`d`.`id_provincia` = `p`.`id_provincia`))) left join `cat_evento` `e` on((`e`.`id_evento` = `T1`.`id_diagnostico1`))) join `tbl_persona` `per` on(((`T1`.`tipo_identificacion` = `per`.`tipo_identificacion`) and (`T1`.`numero_identificacion` = `per`.`numero_identificacion`)))) group by `p`.`id_provincia`,`r`.`id_region`,`d`.`id_distrito`,`c`.`id_corregimiento`,`cun`.`id_un`,`T1`.`id_diagnostico1`,`T1`.`anio`,`T1`.`semana_epi`,`per`.`sexo`,`per`.`numero_identificacion`;

-- --------------------------------------------------------

--
-- Structure for view `view_notic_rangos`
--
DROP TABLE IF EXISTS `view_notic_rangos`;

CREATE  VIEW `view_notic_rangos` AS select `vni_casos`.`nombre_region` AS `nombre_region`,`vni_casos`.`nombre_distrito` AS `nombre_distrito`,`vni_casos`.`nombre_corregimiento` AS `nombre_corregimiento`,`vni_casos`.`nombre_unidad` AS `nombre_unidad`,`vni_casos`.`cie_10` AS `cie_10`,`vni_casos`.`id_gevento` AS `id_gevento`,`vni_casos`.`nombre_evento` AS `nombre_evento`,`vni_casos`.`sexo` AS `sexo`,`vni_casos`.`semana` AS `semana`,`vni_casos`.`anio` AS `anio`,`vni_casos`.`per_id_pais` AS `per_id_pais`,`vni_casos`.`per_id_corregimiento` AS `per_id_corregimiento`,`vni_casos`.`id_unidad` AS `id_unidad`,`vni_casos`.`sector_un` AS `sector_un`,`vni_casos`.`id_corregimiento` AS `id_corregimiento`,`vni_casos`.`id_distrito` AS `id_distrito`,`vni_casos`.`id_region` AS `id_region`,sum(`vni_casos`.`casos`) AS `numero_casos`,if((`vni_casos`.`rango` = _utf8'<1'),sum(`vni_casos`.`casos`),_utf8'0') AS `menor_uno`,if((`vni_casos`.`rango` = _utf8'1-4'),sum(`vni_casos`.`casos`),_utf8'0') AS `uno_cuatro`,if((`vni_casos`.`rango` = _utf8'5-9'),sum(`vni_casos`.`casos`),_utf8'0') AS `cinco_nueve`,if((`vni_casos`.`rango` = _utf8'10-14'),sum(`vni_casos`.`casos`),_utf8'0') AS `diez_catorce`,if((`vni_casos`.`rango` = _utf8'15-19'),sum(`vni_casos`.`casos`),_utf8'0') AS `quince_diecinueve`,if((`vni_casos`.`rango` = _utf8'20-24'),sum(`vni_casos`.`casos`),_utf8'0') AS `veinte_veinticuatro`,if((`vni_casos`.`rango` = _utf8'25-34'),sum(`vni_casos`.`casos`),_utf8'0') AS `veinticinco_treinticuatro`,if((`vni_casos`.`rango` = _utf8'35-49'),sum(`vni_casos`.`casos`),_utf8'0') AS `treinticinco_cuarentinueve`,if((`vni_casos`.`rango` = _utf8'50-59'),sum(`vni_casos`.`casos`),_utf8'0') AS `cincuenta_cincuentinueve`,if((`vni_casos`.`rango` = _utf8'60-64'),sum(`vni_casos`.`casos`),_utf8'0') AS `sesenta_sesenticuatro`,if((`vni_casos`.`rango` = _utf8'65 ó +'),sum(`vni_casos`.`casos`),_utf8'0') AS `mayor_sesenticinco`,if((`vni_casos`.`rango` = _utf8'N/E'),sum(`vni_casos`.`casos`),_utf8'0') AS `NE` from `view_notic_casos` `vni_casos` group by `vni_casos`.`nombre_region`,`vni_casos`.`nombre_distrito`,`vni_casos`.`nombre_corregimiento`,`vni_casos`.`nombre_evento`,`vni_casos`.`semana`,`vni_casos`.`sexo`,`vni_casos`.`rango`;

-- --------------------------------------------------------

--
-- Structure for view `view_notic_reporte`
--
DROP TABLE IF EXISTS `view_notic_reporte`;

CREATE  VIEW `view_notic_reporte` AS select `T1`.`id_provincia` AS `id_provincia`,`T1`.`nombre_provincia` AS `nombre_provincia`,`T1`.`id_region` AS `id_region`,`T1`.`nombre_region` AS `nombre_region`,`T1`.`id_distrito` AS `id_distrito`,`T1`.`nombre_distrito` AS `nombre_distrito`,`T1`.`id_corregimiento` AS `id_corregimiento`,`T1`.`nombre_corregimiento` AS `nombre_corregimiento`,`T1`.`id_un` AS `id_unidad`,`T1`.`nombre_un` AS `nombre_unidad`,`T1`.`sector_un` AS `sector_un`,`T1`.`id_evento` AS `id_evento`,`T1`.`cie_10` AS `cie_10`,`T1`.`nombre_evento` AS `nombre_evento`,`T1`.`id_gevento` AS `id_gevento`,`T1`.`anio` AS `anio`,`T1`.`semana_epi` AS `semana`,`T1`.`per_id_corregimiento` AS `per_id_corregimiento`,`T1`.`per_id_pais` AS `per_id_pais`,sum(`T1`.`m_01`) AS `M<1`,sum(`T1`.`m_14`) AS `M1-4`,sum(`T1`.`m_59`) AS `M5-9`,sum(`T1`.`m_1014`) AS `M10-14`,sum(`T1`.`m_1519`) AS `M15-19`,sum(`T1`.`m_2024`) AS `M20-24`,sum(`T1`.`m_2534`) AS `M25-34`,sum(`T1`.`m_3549`) AS `M35-49`,sum(`T1`.`m_5059`) AS `M50-59`,sum(`T1`.`m_6064`) AS `M60-64`,sum(`T1`.`m_65mas`) AS `M>65`,sum(`T1`.`f_01`) AS `F<1`,sum(`T1`.`f_14`) AS `F1-4`,sum(`T1`.`f_59`) AS `F5-9`,sum(`T1`.`f_1014`) AS `F10-14`,sum(`T1`.`f_1519`) AS `F15-19`,sum(`T1`.`f_2024`) AS `F20-24`,sum(`T1`.`f_2534`) AS `F25-34`,sum(`T1`.`f_3549`) AS `F35-49`,sum(`T1`.`f_5059`) AS `F50-59`,sum(`T1`.`f_6064`) AS `F60-64`,sum(`T1`.`f_65mas`) AS `F>65`,0 AS `FNE`,0 AS `MNE` from `view_notic_matriz` `T1` group by `T1`.`anio`,`T1`.`semana_epi`,`T1`.`id_provincia`,`T1`.`id_region`,`T1`.`id_distrito`,`T1`.`id_corregimiento`,`T1`.`id_un`,`T1`.`id_evento`;

-- --------------------------------------------------------

--
-- Structure for view `view_notic_sexo`
--
DROP TABLE IF EXISTS `view_notic_sexo`;

CREATE  VIEW `view_notic_sexo` AS select `vni_rangos`.`nombre_region` AS `nombre_region`,`vni_rangos`.`nombre_distrito` AS `nombre_distrito`,`vni_rangos`.`nombre_corregimiento` AS `nombre_corregimiento`,`vni_rangos`.`nombre_evento` AS `nombre_evento`,`vni_rangos`.`id_gevento` AS `id_gevento`,`vni_rangos`.`nombre_unidad` AS `nombre_unidad`,`vni_rangos`.`cie_10` AS `cie_10`,`vni_rangos`.`semana` AS `semana`,`vni_rangos`.`anio` AS `anio`,`vni_rangos`.`per_id_pais` AS `per_id_pais`,`vni_rangos`.`per_id_corregimiento` AS `per_id_corregimiento`,`vni_rangos`.`id_unidad` AS `id_unidad`,`vni_rangos`.`sector_un` AS `sector_un`,`vni_rangos`.`id_corregimiento` AS `id_corregimiento`,`vni_rangos`.`id_distrito` AS `id_distrito`,`vni_rangos`.`id_region` AS `id_region`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`menor_uno`),_utf8'0') AS `menor_unoM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`menor_uno`),_utf8'0') AS `menor_unoF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`uno_cuatro`),_utf8'0') AS `uno_cuatroM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`uno_cuatro`),_utf8'0') AS `uno_cuatroF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`cinco_nueve`),_utf8'0') AS `cinco_nueveM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`cinco_nueve`),_utf8'0') AS `cinco_nueveF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`diez_catorce`),_utf8'0') AS `diez_catorceM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`diez_catorce`),_utf8'0') AS `diez_catorceF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`quince_diecinueve`),_utf8'0') AS `quince_diecinueveM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`quince_diecinueve`),_utf8'0') AS `quince_diecinueveF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`veinte_veinticuatro`),_utf8'0') AS `veinte_veinticuatroM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`veinte_veinticuatro`),_utf8'0') AS `veinte_veinticuatroF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`veinticinco_treinticuatro`),_utf8'0') AS `veinticinco_treinticuatroM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`veinticinco_treinticuatro`),_utf8'0') AS `veinticinco_treinticuatroF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`treinticinco_cuarentinueve`),_utf8'0') AS `treinticinco_cuarentinueveM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`treinticinco_cuarentinueve`),_utf8'0') AS `treinticinco_cuarentinueveF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`cincuenta_cincuentinueve`),_utf8'0') AS `cincuenta_cincuentinueveM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`cincuenta_cincuentinueve`),_utf8'0') AS `cincuenta_cincuentinueveF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`sesenta_sesenticuatro`),_utf8'0') AS `sesenta_sesenticuatroM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`sesenta_sesenticuatro`),_utf8'0') AS `sesenta_sesenticuatroF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`mayor_sesenticinco`),_utf8'0') AS `mayor_sesenticincoM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`mayor_sesenticinco`),_utf8'0') AS `mayor_sesenticincoF`,if((`vni_rangos`.`sexo` = _utf8'M'),sum(`vni_rangos`.`NE`),_utf8'0') AS `NEM`,if((`vni_rangos`.`sexo` = _utf8'F'),sum(`vni_rangos`.`NE`),_utf8'0') AS `NEF` from `view_notic_rangos` `vni_rangos` group by `vni_rangos`.`cie_10`,`vni_rangos`.`semana`,`vni_rangos`.`anio`;

-- --------------------------------------------------------

--
-- Structure for view `view_rae`
--
DROP TABLE IF EXISTS `view_rae`;

CREATE  VIEW `view_rae` AS select `f`.`id_rae` AS `id_rae`,`f`.`id_un` AS `id_un`,`u`.`cod_ref_minsa` AS `cod_ref_minsa`,`u`.`nombre_un` AS `nombre_un`,`u`.`id_region` AS `id_region`,`reg`.`nombre_region` AS `nombre_region`,`f`.`referido_de` AS `referido_de`,`f`.`referido_otro_id_un` AS `referido_otro_id_un`,`f`.`id_servicio` AS `id_servicio`,`s`.`codigo_servicio` AS `codigo_servicio`,`s`.`nombre_servicio` AS `nombre_servicio`,`f`.`id_personal_medico` AS `id_personal_medico`,`m`.`nombre_personal_medico` AS `nombre_personal_medico`,`f`.`nombre_funcionario` AS `nombre_funcionario`,`f`.`nombre_registra` AS `nombre_registra`,`f`.`institucion_registra` AS `institucion_registra`,`f`.`fecha_cierre` AS `fecha_cierre`,`f`.`fecha_admision` AS `fecha_admision`,`f`.`fecha_egreso` AS `fecha_egreso`,`f`.`tipo_identificacion` AS `tipo_identificacion`,`i`.`nombre_tipo` AS `nombre_tipo`,`f`.`numero_identificacion` AS `numero_identificacion`,`f`.`per_edad` AS `per_edad`,`e`.`nombre` AS `nombre`,`f`.`per_tipo_edad` AS `per_tipo_edad`,`sex`.`sexo` AS `sexo`,`f`.`per_id_pais` AS `per_id_pais`,`p`.`nombre_pais` AS `nombre_pais`,`f`.`per_id_corregimiento` AS `per_id_corregimiento`,`corr`.`nombre_corregimiento` AS `nombre_corregimiento`,`f`.`per_direccion` AS `per_direccion`,`f`.`per_dir_referencia` AS `per_dir_referencia`,`f`.`per_id_corregimiento_transitoria` AS `per_id_corregimiento_transitoria`,`cor`.`id_corregimiento` AS `id_corregimiento`,`f`.`per_no_hay_dir_transitoria` AS `per_no_hay_dir_transitoria`,`f`.`id_tipo_paciente` AS `id_tipo_paciente`,`pac`.`nombre_tipo_paciente` AS `nombre_tipo_paciente`,`f`.`id_diagnostico1` AS `id_diagnostico1`,`diag1`.`cod_ref_minsa` AS `cod1`,`diag1`.`nombre_evento` AS `nom1`,`f`.`estado_diag1` AS `estado_diag1`,`f`.`id_diagnostico2` AS `id_diagnostico2`,`diag2`.`cod_ref_minsa` AS `cod2`,`diag2`.`nombre_evento` AS `nom2`,`f`.`estado_diag2` AS `estado_diag2`,`f`.`id_diagnostico3` AS `id_diagnostico3`,`diag3`.`cod_ref_minsa` AS `cod3`,`diag3`.`nombre_evento` AS `nom3`,`f`.`estado_diag3` AS `estado_diag3`,`f`.`hospitalizacion` AS `hospitalizacion`,`f`.`id_condicion_salida` AS `id_condicion_salida`,`cond`.`nombre_condicion_salida` AS `nombre_condicion_salida`,`f`.`motivo_salida` AS `motivo_salida`,`f`.`muerte_sop` AS `muerte_sop`,`f`.`autopsia` AS `autopsia`,`f`.`fecha_autopsia` AS `fecha_autopsia`,`f`.`referido_a` AS `referido_a`,`f`.`referido_a_otro` AS `referido_a_otro` from (((((((((((((((`rae_form` `f` left join `cat_unidad_notificadora` `u` on((`f`.`id_un` = `u`.`id_un`))) left join `cat_region_salud` `reg` on((`u`.`id_region` = `reg`.`id_region`))) left join `cat_servicio_rae` `s` on((`f`.`id_servicio` = `s`.`id_servicio`))) left join `tbl_personal_medico` `m` on((`f`.`id_personal_medico` = `m`.`id_personal_medico`))) left join `cat_tipo_identidad` `i` on((`f`.`tipo_identificacion` = `i`.`id_tipo_identidad`))) left join `cat_edad` `e` on((`f`.`per_tipo_edad` = `e`.`tipo_edad`))) left join `cat_pais` `p` on((`f`.`per_id_pais` = `p`.`id_pais`))) left join `cat_corregimiento` `corr` on((`f`.`per_id_corregimiento` = `corr`.`id_corregimiento`))) left join `cat_corregimiento` `cor` on((`f`.`per_id_corregimiento_transitoria` = `cor`.`id_corregimiento`))) left join `cat_tipo_paciente` `pac` on((`f`.`id_tipo_paciente` = `pac`.`id_tipo_paciente`))) left join `cat_evento` `diag1` on((`f`.`id_diagnostico1` = `diag1`.`id_evento`))) left join `cat_evento` `diag2` on((`f`.`id_diagnostico2` = `diag2`.`id_evento`))) left join `cat_evento` `diag3` on((`f`.`id_diagnostico3` = `diag3`.`id_evento`))) left join `cat_condicion_salida` `cond` on((`f`.`id_condicion_salida` = `cond`.`id_condicion_salida`))) left join `tbl_persona` `sex` on((`f`.`numero_identificacion` = `sex`.`numero_identificacion`)));

-- --------------------------------------------------------

--
-- Structure for view `view_rae_condicion_reporte`
--
DROP TABLE IF EXISTS `view_rae_condicion_reporte`;

CREATE  VIEW `view_rae_condicion_reporte` AS select `view_rae_paciente_matriz`.`id_provincia` AS `id_provincia`,`view_rae_paciente_matriz`.`nombre_provincia` AS `nombre_provincia`,`view_rae_paciente_matriz`.`id_region` AS `id_region`,`view_rae_paciente_matriz`.`nombre_region` AS `nombre_region`,`view_rae_paciente_matriz`.`id_distrito` AS `id_distrito`,`view_rae_paciente_matriz`.`nombre_distrito` AS `nombre_distrito`,`view_rae_paciente_matriz`.`id_corregimiento` AS `id_corregimiento`,`view_rae_paciente_matriz`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_rae_paciente_matriz`.`id_un` AS `id_un`,`view_rae_paciente_matriz`.`nombre_un` AS `nombre_un`,`view_rae_paciente_matriz`.`id_servicio` AS `id_servicio`,`view_rae_paciente_matriz`.`codigo_servicio` AS `codigo_servicio`,`view_rae_paciente_matriz`.`nombre_servicio` AS `nombre_servicio`,sum(`view_rae_paciente_matriz`.`Curado`) AS `curado`,sum(`view_rae_paciente_matriz`.`Mejorado`) AS `mejorado`,sum(`view_rae_paciente_matriz`.`Igual`) AS `igual`,sum(`view_rae_paciente_matriz`.`Peor`) AS `peor`,sum(`view_rae_paciente_matriz`.`En Estudio`) AS `En Estudio`,sum(`view_rae_paciente_matriz`.`Muerto antes de 48 hrs.`) AS `Muerto antes de 48 hrs.`,sum(`view_rae_paciente_matriz`.`Muerto despues de 48 hrs.`) AS `Muerto despues de 48 hrs.`,sum(`view_rae_paciente_matriz`.`Fugado`) AS `Fugado`,sum(`view_rae_paciente_matriz`.`No Registrado`) AS `No Registrado`,sum(`view_rae_paciente_matriz`.`Muerte en SOP`) AS `Muerte en SOP`,sum(`view_rae_paciente_matriz`.`Autopsia`) AS `Autopsia`,sum(`view_rae_paciente_matriz`.`Procedimiento Qx`) AS `Procedimiento Qx`,((((((((sum(`view_rae_paciente_matriz`.`Curado`) + sum(`view_rae_paciente_matriz`.`Mejorado`)) + sum(`view_rae_paciente_matriz`.`Igual`)) + sum(`view_rae_paciente_matriz`.`Peor`)) + sum(`view_rae_paciente_matriz`.`En Estudio`)) + sum(`view_rae_paciente_matriz`.`Muerto antes de 48 hrs.`)) + sum(`view_rae_paciente_matriz`.`Muerto despues de 48 hrs.`)) + sum(`view_rae_paciente_matriz`.`Fugado`)) + sum(`view_rae_paciente_matriz`.`No Registrado`)) AS `total`,`view_rae_paciente_matriz`.`fecha_admision` AS `fecha_admision`,`view_rae_paciente_matriz`.`mes` AS `mes`,`view_rae_paciente_matriz`.`anio` AS `anio` from `view_rae_paciente_matriz` group by `view_rae_paciente_matriz`.`nombre_provincia`,`view_rae_paciente_matriz`.`nombre_region`,`view_rae_paciente_matriz`.`nombre_distrito`,`view_rae_paciente_matriz`.`nombre_corregimiento`,`view_rae_paciente_matriz`.`nombre_un`,`view_rae_paciente_matriz`.`nombre_servicio`;

-- --------------------------------------------------------

--
-- Structure for view `view_rae_eventos`
--
DROP TABLE IF EXISTS `view_rae_eventos`;

CREATE  VIEW `view_rae_eventos` AS select `egre`.`id_rae` AS `id_rae`,`egre`.`id_evento` AS `id_evento`,`neven`.`cod_ref_minsa` AS `ciex`,`neven`.`nombre_evento` AS `nombre_evento`,`f`.`id_un` AS `id_un`,`u`.`cod_ref_minsa` AS `cod_ref_minsa`,`u`.`nombre_un` AS `nombre_un`,`u`.`id_region` AS `id_region`,`reg`.`nombre_region` AS `nombre_region`,`f`.`id_servicio` AS `id_servicio`,`s`.`codigo_servicio` AS `codigo_servicio`,`s`.`nombre_servicio` AS `nombre_servicio`,`m`.`nombre_personal_medico` AS `nombre_personal_medico`,`f`.`nombre_funcionario` AS `nombre_funcionario`,`f`.`nombre_registra` AS `nombre_registra`,`f`.`fecha_cierre` AS `fecha_cierre`,`f`.`fecha_admision` AS `fecha_admision`,`f`.`fecha_egreso` AS `fecha_egreso`,`f`.`tipo_identificacion` AS `tipo_identificacion`,`i`.`nombre_tipo` AS `nombre_tipo`,`f`.`numero_identificacion` AS `numero_identificacion`,`f`.`per_edad` AS `per_edad`,`e`.`nombre` AS `nombre`,`f`.`per_tipo_edad` AS `per_tipo_edad`,`sex`.`sexo` AS `sexo`,`f`.`per_id_pais` AS `per_id_pais`,`p`.`nombre_pais` AS `nombre_pais`,`f`.`per_id_corregimiento` AS `per_id_corregimiento`,`corr`.`nombre_corregimiento` AS `nombre_corregimiento`,`f`.`per_direccion` AS `per_direccion`,`f`.`per_dir_referencia` AS `per_dir_referencia`,`f`.`per_id_corregimiento_transitoria` AS `per_id_corregimiento_transitoria`,`cor`.`id_corregimiento` AS `id_corregimiento`,`f`.`per_no_hay_dir_transitoria` AS `per_no_hay_dir_transitoria`,`f`.`id_tipo_paciente` AS `id_tipo_paciente`,`pac`.`nombre_tipo_paciente` AS `nombre_tipo_paciente`,`f`.`hospitalizacion` AS `hospitalizacion`,`f`.`id_condicion_salida` AS `id_condicion_salida`,`cond`.`nombre_condicion_salida` AS `nombre_condicion_salida`,`f`.`motivo_salida` AS `motivo_salida`,`f`.`muerte_sop` AS `muerte_sop`,`f`.`autopsia` AS `autopsia`,`f`.`fecha_autopsia` AS `fecha_autopsia` from ((((((((((((((`rae_egreso` `egre` left join `rae_form` `f` on((`egre`.`id_rae` = `f`.`id_rae`))) left join `cat_unidad_notificadora` `u` on((`f`.`id_un` = `u`.`id_un`))) left join `cat_region_salud` `reg` on((`u`.`id_region` = `reg`.`id_region`))) left join `cat_servicio_rae` `s` on((`f`.`id_servicio` = `s`.`id_servicio`))) left join `tbl_personal_medico` `m` on((`f`.`id_personal_medico` = `m`.`id_personal_medico`))) left join `cat_tipo_identidad` `i` on((`f`.`tipo_identificacion` = `i`.`id_tipo_identidad`))) left join `cat_edad` `e` on((`f`.`per_tipo_edad` = `e`.`tipo_edad`))) left join `cat_pais` `p` on((`f`.`per_id_pais` = `p`.`id_pais`))) left join `cat_corregimiento` `corr` on((`f`.`per_id_pais` = `corr`.`id_corregimiento`))) left join `cat_corregimiento` `cor` on((`f`.`per_id_corregimiento_transitoria` = `cor`.`id_corregimiento`))) left join `cat_tipo_paciente` `pac` on((`f`.`id_tipo_paciente` = `pac`.`id_tipo_paciente`))) left join `cat_evento` `neven` on((`egre`.`id_evento` = `neven`.`id_evento`))) left join `cat_condicion_salida` `cond` on((`f`.`id_condicion_salida` = `cond`.`id_condicion_salida`))) left join `tbl_persona` `sex` on((`f`.`numero_identificacion` = `sex`.`numero_identificacion`)));

-- --------------------------------------------------------

--
-- Structure for view `view_rae_eventos2`
--
DROP TABLE IF EXISTS `view_rae_eventos2`;

CREATE  VIEW `view_rae_eventos2` AS select `r`.`id_region` AS `id_region`,`r`.`nombre_region` AS `nombre_region`,`p`.`id_provincia` AS `id_provincia`,`p`.`nombre_provincia` AS `nombre_provincia`,`d`.`id_distrito` AS `id_distrito`,`d`.`nombre_distrito` AS `nombre_distrito`,`c`.`id_corregimiento` AS `id_corregimiento`,`c`.`nombre_corregimiento` AS `nombre_corregimiento`,`rae`.`id_un` AS `id_un`,`cun`.`nombre_un` AS `nombre_un`,`rae`.`id_servicio` AS `id_servicio`,`ser`.`codigo_servicio` AS `codigo_servicio`,`ser`.`nombre_servicio` AS `nombre_servicio`,`rae`.`per_edad` AS `per_dedad`,`geve`.`nombre_gevento` AS `nombre_gevento`,`eve`.`cie_10_1` AS `cie_10_1`,`eve`.`nombre_evento` AS `nombre_evento`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 1) and (`rae`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 5) and (`rae`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 10) and (`rae`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 15) and (`rae`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 20) and (`rae`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 25) and (`rae`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `veinticinco_treitaycuatro_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 35) and (`rae`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `treintaycinco_cuarentaynueve_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 50) and (`rae`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaynueve_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 60) and (`rae`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_m`,(case when ((`per`.`sexo` = _utf8'M') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_m`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 1) and (`rae`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 5) and (`rae`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 10) and (`rae`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 15) and (`rae`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 20) and (`rae`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 25) and (`rae`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `veinticinco_treitaycuatro_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 35) and (`rae`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `treintaycinco_cuarentaynueve_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 50) and (`rae`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaynueve_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 60) and (`rae`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_f`,(case when ((`per`.`sexo` = _utf8'F') and (`rae`.`per_tipo_edad` > 2) and (`rae`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_f`,date_format(`rae`.`fecha_admision`,_utf8'%d-%m-%Y') AS `fecha_admision`,date_format(`rae`.`fecha_admision`,_utf8'%m') AS `mes`,date_format(`rae`.`fecha_admision`,_utf8'%Y') AS `anio` from ((((((((((`rae_form` `rae` left join `cat_unidad_notificadora` `cun` on((`rae`.`id_un` = `cun`.`id_un`))) left join `cat_corregimiento` `c` on((`cun`.`id_corregimiento` = `c`.`id_corregimiento`))) left join `cat_distrito` `d` on((`c`.`id_distrito` = `d`.`id_distrito`))) left join `cat_region_salud` `r` on((`d`.`id_region` = `r`.`id_region`))) left join `cat_provincia` `p` on((`d`.`id_provincia` = `p`.`id_provincia`))) left join `cat_servicio_rae` `ser` on((`rae`.`id_servicio` = `ser`.`id_servicio`))) left join `cat_evento` `eve` on((`rae`.`id_diagnostico1` = `eve`.`id_evento`))) left join `cat_grupo_evento` `geve` on((`eve`.`id_gevento` = `geve`.`id_gevento`))) left join `cat_tipo_paciente` `tpa` on((`rae`.`id_tipo_paciente` = `tpa`.`id_tipo_paciente`))) left join `tbl_persona` `per` on(((`rae`.`tipo_identificacion` = `per`.`tipo_identificacion`) and (`rae`.`numero_identificacion` = `per`.`numero_identificacion`)))) group by `r`.`id_region`,`r`.`nombre_region`,`p`.`id_provincia`,`p`.`nombre_provincia`,`d`.`id_distrito`,`d`.`nombre_distrito`,`c`.`id_corregimiento`,`c`.`nombre_corregimiento`,`rae`.`id_un`,`cun`.`nombre_un`,`rae`.`id_servicio`,`ser`.`codigo_servicio`,`ser`.`nombre_servicio`,`rae`.`per_edad`,`geve`.`nombre_gevento`,`eve`.`cie_10_1`,`eve`.`nombre_evento`;

-- --------------------------------------------------------

--
-- Structure for view `view_rae_eventos_reporte`
--
DROP TABLE IF EXISTS `view_rae_eventos_reporte`;

CREATE  VIEW `view_rae_eventos_reporte` AS select `view_rae_eventos`.`id_provincia` AS `id_provincia`,`view_rae_eventos`.`nombre_provincia` AS `nombre_provincia`,`view_rae_eventos`.`id_region` AS `id_region`,`view_rae_eventos`.`nombre_region` AS `nombre_region`,`view_rae_eventos`.`id_distrito` AS `id_distrito`,`view_rae_eventos`.`nombre_distrito` AS `nombre_distrito`,`view_rae_eventos`.`id_corregimiento` AS `id_corregimiento`,`view_rae_eventos`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_rae_eventos`.`id_un` AS `id_un`,`view_rae_eventos`.`nombre_un` AS `nombre_un`,`view_rae_eventos`.`id_servicio` AS `id_servicio`,`view_rae_eventos`.`codigo_servicio` AS `codigo_servicio`,`view_rae_eventos`.`nombre_servicio` AS `nombre_servicio`,`view_rae_eventos`.`nombre_gevento` AS `nombre_gevento`,`view_rae_eventos`.`cie_10_1` AS `cie_10_1`,`view_rae_eventos`.`nombre_evento` AS `nombre_evento`,sum(`view_rae_eventos`.`menor_uno_m`) AS `menor_uno_m`,sum(`view_rae_eventos`.`uno_cuatro_m`) AS `uno_cuatro_m`,sum(`view_rae_eventos`.`cinco_nueve_m`) AS `cinco_nueve_m`,sum(`view_rae_eventos`.`diez_catorce_m`) AS `diez_catorce_m`,sum(`view_rae_eventos`.`quince_diecinueve_m`) AS `quince_diecinueve_m`,sum(`view_rae_eventos`.`veinte_veinticuatro_m`) AS `veinte_veinticuatro_m`,sum(`view_rae_eventos`.`veinticinco_treitaycuatro_m`) AS `veinticinco_treitaycuatro_m`,sum(`view_rae_eventos`.`treintaycinco_cuarentaynueve_m`) AS `treintaycinco_cuarentaynueve_m`,sum(`view_rae_eventos`.`cincuenta_cincuentaynueve_m`) AS `cincuenta_cincuentaynueve_m`,sum(`view_rae_eventos`.`sesenta_sesentaycuantro_m`) AS `sesenta_sesentaycuantro_m`,sum(`view_rae_eventos`.`mas_sesentaycinco_m`) AS `mas_sesentaycinco_m`,sum(`view_rae_eventos`.`menor_uno_f`) AS `menor_uno_f`,sum(`view_rae_eventos`.`uno_cuatro_f`) AS `uno_cuatro_f`,sum(`view_rae_eventos`.`cinco_nueve_f`) AS `cinco_nueve_f`,sum(`view_rae_eventos`.`diez_catorce_f`) AS `diez_catorce_f`,sum(`view_rae_eventos`.`quince_diecinueve_f`) AS `quince_diecinueve_f`,sum(`view_rae_eventos`.`veinte_veinticuatro_f`) AS `veinte_veinticuatro_f`,sum(`view_rae_eventos`.`veinticinco_treitaycuatro_f`) AS `veinticinco_treitaycuatro_f`,sum(`view_rae_eventos`.`treintaycinco_cuarentaynueve_f`) AS `treintaycinco_cuarentaynueve_f`,sum(`view_rae_eventos`.`cincuenta_cincuentaynueve_f`) AS `cincuenta_cincuentaynueve_f`,sum(`view_rae_eventos`.`sesenta_sesentaycuantro_f`) AS `sesenta_sesentaycuantro_f`,sum(`view_rae_eventos`.`mas_sesentaycinco_f`) AS `mas_sesentaycinco_f`,`view_rae_eventos`.`fecha_admision` AS `fecha_admision`,`view_rae_eventos`.`mes` AS `mes`,`view_rae_eventos`.`anio` AS `anio` from `view_rae_eventos2` `view_rae_eventos` group by `view_rae_eventos`.`id_provincia`,`view_rae_eventos`.`nombre_provincia`,`view_rae_eventos`.`id_region`,`view_rae_eventos`.`nombre_region`,`view_rae_eventos`.`id_distrito`,`view_rae_eventos`.`nombre_distrito`,`view_rae_eventos`.`id_corregimiento`,`view_rae_eventos`.`nombre_corregimiento`,`view_rae_eventos`.`id_un`,`view_rae_eventos`.`nombre_un`,`view_rae_eventos`.`id_servicio`,`view_rae_eventos`.`codigo_servicio`,`view_rae_eventos`.`nombre_servicio`,`view_rae_eventos`.`nombre_gevento`,`view_rae_eventos`.`cie_10_1`,`view_rae_eventos`.`nombre_evento`,`view_rae_eventos`.`fecha_admision`,`view_rae_eventos`.`mes`,`view_rae_eventos`.`anio`;

-- --------------------------------------------------------

--
-- Structure for view `view_rae_paciente_matriz`
--
DROP TABLE IF EXISTS `view_rae_paciente_matriz`;

CREATE  VIEW `view_rae_paciente_matriz` AS select `p`.`id_provincia` AS `id_provincia`,`p`.`nombre_provincia` AS `nombre_provincia`,`r`.`id_region` AS `id_region`,`r`.`nombre_region` AS `nombre_region`,`d`.`id_distrito` AS `id_distrito`,`d`.`nombre_distrito` AS `nombre_distrito`,`c`.`id_corregimiento` AS `id_corregimiento`,`c`.`nombre_corregimiento` AS `nombre_corregimiento`,`rae`.`id_un` AS `id_un`,`cun`.`nombre_un` AS `nombre_un`,`rae`.`id_servicio` AS `id_servicio`,`ser`.`codigo_servicio` AS `codigo_servicio`,`ser`.`nombre_servicio` AS `nombre_servicio`,(to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`)) AS `total`,count(`rae`.`id_rae`) AS `total_N`,(case when (`rae`.`id_tipo_paciente` = 1) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `uno`,(case when (`rae`.`id_tipo_paciente` = 1) then count(`rae`.`id_rae`) else sum(0) end) AS `uno_N`,(case when (`rae`.`id_tipo_paciente` = 2) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `dos`,(case when (`rae`.`id_tipo_paciente` = 2) then count(`rae`.`id_rae`) else sum(0) end) AS `dos_N`,(case when (`rae`.`id_tipo_paciente` = 3) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `tres`,(case when (`rae`.`id_tipo_paciente` = 3) then count(`rae`.`id_rae`) else sum(0) end) AS `tres_N`,(case when (`rae`.`id_tipo_paciente` = 4) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `cuatro`,(case when (`rae`.`id_tipo_paciente` = 4) then count(`rae`.`id_rae`) else sum(0) end) AS `cuatro_N`,(case when (`rae`.`id_tipo_paciente` = 5) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `cinco`,(case when (`rae`.`id_tipo_paciente` = 5) then count(`rae`.`id_rae`) else sum(0) end) AS `cinco_N`,(case when (`rae`.`id_tipo_paciente` = 6) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `seis`,(case when (`rae`.`id_tipo_paciente` = 6) then count(`rae`.`id_rae`) else sum(0) end) AS `seis_N`,(case when (`rae`.`id_tipo_paciente` = 7) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `siete`,(case when (`rae`.`id_tipo_paciente` = 7) then count(`rae`.`id_rae`) else sum(0) end) AS `siete_N`,(case when (`rae`.`id_tipo_paciente` = 8) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `ocho`,(case when (`rae`.`id_tipo_paciente` = 8) then count(`rae`.`id_rae`) else sum(0) end) AS `ocho_N`,(case when (`rae`.`id_tipo_paciente` = 9) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `nueve`,(case when (`rae`.`id_tipo_paciente` = 9) then count(`rae`.`id_rae`) else sum(0) end) AS `nueve_N`,(case when (`rae`.`id_tipo_paciente` = 10) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `diez`,(case when (`rae`.`id_tipo_paciente` = 10) then count(`rae`.`id_rae`) else sum(0) end) AS `diez_N`,(case when (`rae`.`id_tipo_paciente` = 11) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `once`,(case when (`rae`.`id_tipo_paciente` = 11) then count(`rae`.`id_rae`) else sum(0) end) AS `once_N`,(case when (`rae`.`id_tipo_paciente` = 12) then sum((to_days(`rae`.`fecha_egreso`) - to_days(`rae`.`fecha_admision`))) else sum(0) end) AS `doce`,(case when (`rae`.`id_tipo_paciente` = 12) then count(`rae`.`id_rae`) else sum(0) end) AS `doce_N`,count(`rae`.`id_rae`) AS `total_condicion`,(case when (`rae`.`id_condicion_salida` = 1) then count(`rae`.`id_rae`) else sum(0) end) AS `Curado`,(case when (`rae`.`id_condicion_salida` = 2) then count(`rae`.`id_rae`) else sum(0) end) AS `Mejorado`,(case when (`rae`.`id_condicion_salida` = 3) then count(`rae`.`id_rae`) else sum(0) end) AS `Igual`,(case when (`rae`.`id_condicion_salida` = 4) then count(`rae`.`id_rae`) else sum(0) end) AS `Peor`,(case when (`rae`.`id_condicion_salida` = 5) then count(`rae`.`id_rae`) else sum(0) end) AS `En Estudio`,(case when (`rae`.`id_condicion_salida` = 6) then count(`rae`.`id_rae`) else sum(0) end) AS `Muerto antes de 48 hrs.`,(case when (`rae`.`id_condicion_salida` = 7) then count(`rae`.`id_rae`) else sum(0) end) AS `Muerto despues de 48 hrs.`,(case when (`rae`.`id_condicion_salida` = 8) then count(`rae`.`id_rae`) else sum(0) end) AS `Fugado`,(case when (`rae`.`id_condicion_salida` = 9) then count(`rae`.`id_rae`) else sum(0) end) AS `No Registrado`,(case when (`rae`.`muerte_sop` = 1) then count(`rae`.`id_rae`) else sum(0) end) AS `Muerte en SOP`,(case when (`rae`.`autopsia` = 1) then count(`rae`.`id_rae`) else sum(0) end) AS `Autopsia`,(case when (`pro`.`tipo_procedimiento` = 1) then count(`pro`.`id_rae`) else sum(0) end) AS `Procedimiento Qx`,date_format(`rae`.`fecha_admision`,_utf8'%d-%m-%Y') AS `fecha_admision`,date_format(`rae`.`fecha_admision`,_utf8'%m') AS `mes`,date_format(`rae`.`fecha_admision`,_utf8'%Y') AS `anio` from ((((((((`rae_form` `rae` left join `cat_unidad_notificadora` `cun` on((`rae`.`id_un` = `cun`.`id_un`))) left join `cat_corregimiento` `c` on((`cun`.`id_corregimiento` = `c`.`id_corregimiento`))) left join `cat_distrito` `d` on((`c`.`id_distrito` = `d`.`id_distrito`))) left join `cat_region_salud` `r` on((`d`.`id_region` = `r`.`id_region`))) left join `cat_provincia` `p` on((`d`.`id_provincia` = `p`.`id_provincia`))) left join `cat_servicio_rae` `ser` on((`rae`.`id_servicio` = `ser`.`id_servicio`))) left join `rae_procedimiento` `pro` on((`rae`.`id_rae` = `pro`.`id_rae`))) left join `cat_tipo_paciente` `tpa` on((`rae`.`id_tipo_paciente` = `tpa`.`id_tipo_paciente`))) group by `p`.`id_provincia`,`r`.`id_region`,`d`.`id_distrito`,`c`.`id_corregimiento`,`cun`.`id_un`,`ser`.`id_servicio`,`tpa`.`id_tipo_paciente`,`rae`.`id_condicion_salida`;

-- --------------------------------------------------------

--
-- Structure for view `view_rae_paciente_reporte`
--
DROP TABLE IF EXISTS `view_rae_paciente_reporte`;

CREATE  VIEW `view_rae_paciente_reporte` AS select `view_rae_paciente_matriz`.`id_provincia` AS `id_provincia`,`view_rae_paciente_matriz`.`nombre_provincia` AS `nombre_provincia`,`view_rae_paciente_matriz`.`id_region` AS `id_region`,`view_rae_paciente_matriz`.`nombre_region` AS `nombre_region`,`view_rae_paciente_matriz`.`id_distrito` AS `id_distrito`,`view_rae_paciente_matriz`.`nombre_distrito` AS `nombre_distrito`,`view_rae_paciente_matriz`.`id_corregimiento` AS `id_corregimiento`,`view_rae_paciente_matriz`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_rae_paciente_matriz`.`id_un` AS `id_un`,`view_rae_paciente_matriz`.`nombre_un` AS `nombre_un`,`view_rae_paciente_matriz`.`id_servicio` AS `id_servicio`,`view_rae_paciente_matriz`.`codigo_servicio` AS `codigo_servicio`,`view_rae_paciente_matriz`.`nombre_servicio` AS `nombre_servicio`,sum(`view_rae_paciente_matriz`.`uno`) AS `uno`,sum(`view_rae_paciente_matriz`.`uno_N`) AS `uno_N`,sum(`view_rae_paciente_matriz`.`dos`) AS `dos`,sum(`view_rae_paciente_matriz`.`dos_N`) AS `dos_N`,sum(`view_rae_paciente_matriz`.`tres`) AS `tres`,sum(`view_rae_paciente_matriz`.`tres_N`) AS `tres_N`,sum(`view_rae_paciente_matriz`.`cuatro`) AS `cuatro`,sum(`view_rae_paciente_matriz`.`cuatro_N`) AS `cuatro_N`,sum(`view_rae_paciente_matriz`.`cinco`) AS `cinco`,sum(`view_rae_paciente_matriz`.`cinco_N`) AS `cinco_N`,sum(`view_rae_paciente_matriz`.`seis`) AS `seis`,sum(`view_rae_paciente_matriz`.`seis_N`) AS `seis_N`,sum(`view_rae_paciente_matriz`.`siete`) AS `siete`,sum(`view_rae_paciente_matriz`.`siete_N`) AS `siete_N`,sum(`view_rae_paciente_matriz`.`ocho`) AS `ocho`,sum(`view_rae_paciente_matriz`.`ocho_N`) AS `ocho_N`,sum(`view_rae_paciente_matriz`.`nueve`) AS `nueve`,sum(`view_rae_paciente_matriz`.`nueve_N`) AS `nueve_N`,sum(`view_rae_paciente_matriz`.`diez`) AS `diez`,sum(`view_rae_paciente_matriz`.`diez_N`) AS `diez_N`,sum(`view_rae_paciente_matriz`.`once`) AS `once`,sum(`view_rae_paciente_matriz`.`once_N`) AS `once_N`,sum(`view_rae_paciente_matriz`.`doce`) AS `doce`,sum(`view_rae_paciente_matriz`.`doce_N`) AS `doce_N`,(((((((((sum(`view_rae_paciente_matriz`.`dos`) + sum(`view_rae_paciente_matriz`.`tres`)) + sum(`view_rae_paciente_matriz`.`cuatro`)) + sum(`view_rae_paciente_matriz`.`cinco`)) + sum(`view_rae_paciente_matriz`.`seis`)) + sum(`view_rae_paciente_matriz`.`siete`)) + sum(`view_rae_paciente_matriz`.`ocho`)) + sum(`view_rae_paciente_matriz`.`nueve`)) + sum(`view_rae_paciente_matriz`.`diez`)) + sum(`view_rae_paciente_matriz`.`once`)) AS `total_asegurados`,(((((((((sum(`view_rae_paciente_matriz`.`dos_N`) + sum(`view_rae_paciente_matriz`.`tres_N`)) + sum(`view_rae_paciente_matriz`.`cuatro_N`)) + sum(`view_rae_paciente_matriz`.`cinco_N`)) + sum(`view_rae_paciente_matriz`.`seis_N`)) + sum(`view_rae_paciente_matriz`.`siete_N`)) + sum(`view_rae_paciente_matriz`.`ocho_N`)) + sum(`view_rae_paciente_matriz`.`nueve_N`)) + sum(`view_rae_paciente_matriz`.`diez_N`)) + sum(`view_rae_paciente_matriz`.`once_N`)) AS `total_asegurados_N`,`view_rae_paciente_matriz`.`fecha_admision` AS `fecha_admision`,`view_rae_paciente_matriz`.`mes` AS `mes`,`view_rae_paciente_matriz`.`anio` AS `anio` from `view_rae_paciente_matriz` group by `view_rae_paciente_matriz`.`nombre_provincia`,`view_rae_paciente_matriz`.`nombre_region`,`view_rae_paciente_matriz`.`nombre_distrito`,`view_rae_paciente_matriz`.`nombre_corregimiento`,`view_rae_paciente_matriz`.`nombre_un`,`view_rae_paciente_matriz`.`nombre_servicio`;

-- --------------------------------------------------------

--
-- Structure for view `view_rae_procedimiento`
--
DROP TABLE IF EXISTS `view_rae_procedimiento`;

CREATE  VIEW `view_rae_procedimiento` AS select `r`.`id_rae` AS `id_rae`,`r`.`id_procedimiento` AS `id_procedimiento`,`r`.`codigo_procedimiento` AS `codigo_procedimiento`,`c`.`nombre_procedimiento` AS `nombre_procedimiento`,`r`.`tipo_procedimiento` AS `tipo_procedimiento` from (`rae_procedimiento` `r` left join `cat_procedimiento` `c` on((`r`.`id_procedimiento` = `c`.`id_procedimiento`)));

-- --------------------------------------------------------

--
-- Structure for view `view_tb_reporte`
--
DROP TABLE IF EXISTS `view_tb_reporte`;

CREATE  VIEW `view_tb_reporte` AS select `t5`.`id_provincia` AS `id_provincia`,`t5`.`nombre_provincia` AS `nombre_provincia`,`t2`.`id_region` AS `id_region`,`t3`.`nombre_region` AS `nombre_region`,`t7`.`id_distrito` AS `id_distrito`,`t7`.`nombre_distrito` AS `nombre_distrito`,`t6`.`id_corregimiento` AS `id_corregimiento`,`t6`.`nombre_corregimiento` AS `nombre_corregimiento`,`t1`.`id_un` AS `id_un`,`t2`.`nombre_un` AS `nombre_un`,`t1`.`fecha_notificacion` AS `fecha_notificacion`,`t1`.`trat_fecha_inicio_tratF1` AS `trat_fecha_inicio_tratF1`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` < 3)) then 1 else 0 end) AS `fem_rango_uno`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 1 and 4)) then 1 else 0 end) AS `fem_rango_dos`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 5 and 9)) then 1 else 0 end) AS `fem_rango_tres`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 10 and 14)) then 1 else 0 end) AS `fem_rango_cuatro`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 15 and 19)) then 1 else 0 end) AS `fem_rango_cinco`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 20 and 24)) then 1 else 0 end) AS `fem_rango_seis`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 25 and 34)) then 1 else 0 end) AS `fem_rango_siete`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 35 and 49)) then 1 else 0 end) AS `fem_rango_ocho`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 50 and 59)) then 1 else 0 end) AS `fem_rango_nueve`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 60 and 64)) then 1 else 0 end) AS `fem_rango_diez`,(case when ((`t4`.`sexo` = 'F') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` > 64)) then 1 else 0 end) AS `fem_rango_once`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` < 3)) then 1 else 0 end) AS `mas_rango_uno`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 1 and 4)) then 1 else 0 end) AS `mas_rango_dos`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 5 and 9)) then 1 else 0 end) AS `mas_rango_tres`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 10 and 14)) then 1 else 0 end) AS `mas_rango_cuatro`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 15 and 19)) then 1 else 0 end) AS `mas_rango_cinco`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 20 and 24)) then 1 else 0 end) AS `mas_rango_seis`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 25 and 34)) then 1 else 0 end) AS `mas_rango_siete`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 35 and 49)) then 1 else 0 end) AS `mas_rango_ocho`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 50 and 59)) then 1 else 0 end) AS `mas_rango_nueve`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` between 60 and 64)) then 1 else 0 end) AS `mas_rango_diez`,(case when ((`t4`.`sexo` = 'M') and (`t1`.`per_tipo_edad` = 3) and (`t1`.`per_edad` > 64)) then 1 else 0 end) AS `mas_rango_once`,(case when ((`t1`.`clas_trat_previo` = 1) and ((`t1`.`mat_diag_resultado_BK1` = 1) or (`t1`.`mat_diag_resultado_BK2` = 1) or (`t1`.`mat_diag_resultado_BK3` = 1)) and (`t1`.`clas_pulmonar_EP` = 1)) then 1 else 0 end) AS `TBC_pulmonar_BKpos`,(case when ((`t1`.`clas_trat_previo` = 1) and ((`t1`.`mat_diag_resultado_BK1` = 0) or (`t1`.`mat_diag_resultado_BK2` = 0) or (`t1`.`mat_diag_resultado_BK3` = 0)) and (`t1`.`clas_pulmonar_EP` = 1)) then 1 else 0 end) AS `TBC_pulmonar_BKneg`,(case when ((`t1`.`clas_trat_previo` = 1) and (isnull(`t1`.`mat_diag_resultado_BK1`) or isnull(`t1`.`mat_diag_resultado_BK2`) or isnull(`t1`.`mat_diag_resultado_BK3`)) and (`t1`.`clas_pulmonar_EP` = 1)) then 1 else 0 end) AS `TBC_pulmonar_sin_BK`,(case when ((`t1`.`clas_trat_previo` = 1) and (`t1`.`clas_pulmonar_EP` = 2)) then 1 else 0 end) AS `TBC_extrapulmonar`,(case when ((`t1`.`clas_trat_previo` = 2) and (`t1`.`clas_pulmonar_EP` = 2)) then 1 else 0 end) AS `TBC_extrapulmonar_old`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 1) or (`t1`.`mat_diag_resultado_BK2` = 1) or (`t1`.`mat_diag_resultado_BK3` = 1)) and (`t1`.`clas_recaida` = 1)) then 1 else 0 end) AS `BKpos_recaidas`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 1) or (`t1`.`mat_diag_resultado_BK2` = 1) or (`t1`.`mat_diag_resultado_BK3` = 1)) and (`t1`.`clas_postfracaso` = 1)) then 1 else 0 end) AS `BKpos_fracaso`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 1) or (`t1`.`mat_diag_resultado_BK2` = 1) or (`t1`.`mat_diag_resultado_BK3` = 1)) and (`t1`.`clas_perdsegui` = 1)) then 1 else 0 end) AS `BKpos_perd_sgto`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 1) or (`t1`.`mat_diag_resultado_BK2` = 1) or (`t1`.`mat_diag_resultado_BK3` = 1)) and (`t1`.`clas_otros_antestratado` = 1)) then 1 else 0 end) AS `BKpos_otros`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 0) or (`t1`.`mat_diag_resultado_BK2` = 0) or (`t1`.`mat_diag_resultado_BK3` = 0)) and (`t1`.`clas_recaida` = 1)) then 1 else 0 end) AS `BKneg_recaidas`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 0) or (`t1`.`mat_diag_resultado_BK2` = 0) or (`t1`.`mat_diag_resultado_BK3` = 0)) and (`t1`.`clas_postfracaso` = 1)) then 1 else 0 end) AS `BKneg_fracaso`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 0) or (`t1`.`mat_diag_resultado_BK2` = 0) or (`t1`.`mat_diag_resultado_BK3` = 0)) and (`t1`.`clas_perdsegui` = 1)) then 1 else 0 end) AS `BKneg_perd_sgto`,(case when ((`t1`.`clas_trat_previo` = 2) and ((`t1`.`mat_diag_resultado_BK1` = 0) or (`t1`.`mat_diag_resultado_BK2` = 0) or (`t1`.`mat_diag_resultado_BK3` = 0)) and (`t1`.`clas_otros_antestratado` = 1)) then 1 else 0 end) AS `BKneg_otros`,(case when ((`t1`.`clas_trat_previo` = 2) and (isnull(`t1`.`mat_diag_resultado_BK1`) or isnull(`t1`.`mat_diag_resultado_BK2`) or isnull(`t1`.`mat_diag_resultado_BK3`)) and (`t1`.`clas_recaida` = 1)) then 1 else 0 end) AS `sinBK_recaidas`,(case when ((`t1`.`clas_trat_previo` = 2) and (isnull(`t1`.`mat_diag_resultado_BK1`) or isnull(`t1`.`mat_diag_resultado_BK2`) or isnull(`t1`.`mat_diag_resultado_BK3`)) and (`t1`.`clas_postfracaso` = 1)) then 1 else 0 end) AS `sinBK_fracaso`,(case when ((`t1`.`clas_trat_previo` = 2) and (isnull(`t1`.`mat_diag_resultado_BK1`) or isnull(`t1`.`mat_diag_resultado_BK2`) or isnull(`t1`.`mat_diag_resultado_BK3`)) and (`t1`.`clas_perdsegui` = 1)) then 1 else 0 end) AS `sinBK_perd_sgto`,(case when ((`t1`.`clas_trat_previo` = 2) and (isnull(`t1`.`mat_diag_resultado_BK1`) or isnull(`t1`.`mat_diag_resultado_BK2`) or isnull(`t1`.`mat_diag_resultado_BK3`)) and (`t1`.`clas_otros_antestratado` = 1)) then 1 else 0 end) AS `sinBK_otros` from ((((((`tb_form` `t1` left join `cat_unidad_notificadora` `t2` on((`t1`.`id_un` = `t2`.`id_un`))) left join `cat_region_salud` `t3` on((`t2`.`id_region` = `t3`.`id_region`))) left join `cat_provincia` `t5` on((`t3`.`id_provincia` = `t5`.`id_provincia`))) left join `cat_corregimiento` `t6` on((`t2`.`id_corregimiento` = `t6`.`id_corregimiento`))) left join `cat_distrito` `t7` on((`t6`.`id_distrito` = `t7`.`id_distrito`))) join `tbl_persona` `t4` on(((`t1`.`numero_identificacion` = `t4`.`numero_identificacion`) and (`t1`.`tipo_identificacion` = `t4`.`tipo_identificacion`)))) order by `t1`.`id_tb`;

-- --------------------------------------------------------

--
-- Structure for view `view_tb_sabana`
--
DROP TABLE IF EXISTS `view_tb_sabana`;

CREATE  VIEW `view_tb_sabana` AS select `t1`.`id_tb` AS `id_tb`,`t1`.`id_corregimiento` AS `id_corregimiento`,`t2`.`nombre_corregimiento` AS `nombre_corregimiento`,`t2`.`id_distrito` AS `id_distrito`,`t3`.`nombre_distrito` AS `nombre_distrito`,`t3`.`id_region` AS `id_region`,`t4`.`nombre_region` AS `nombre_region`,`t4`.`id_provincia` AS `id_provincia`,`t5`.`nombre_provincia` AS `nombre_provincia`,`t1`.`id_un` AS `id_un`,`t6`.`nombre_un` AS `nombre_un`,(case when (`t1`.`unidad_disponible` = 1) then 'X' else '' end) AS `unidad_disponible`,`t1`.`nombre_investigador` AS `nombre_investigador`,date_format(`t1`.`fecha_formulario`,'%d-%m-%Y') AS `fecha_formulario`,date_format(`t1`.`fecha_notificacion`,'%d-%m-%Y') AS `fechafor_notificacion`,`t1`.`fecha_notificacion` AS `fecha_notificacion`,`t1`.`nombre_registra` AS `nombre_registra`,`t1`.`tipo_identificacion` AS `tipo_identificacion`,`t12`.`nombre_tipo` AS `nombre_tipo`,`t1`.`numero_identificacion` AS `numero_identificacion`,`t7`.`primer_nombre` AS `primer_nombre`,`t7`.`segundo_nombre` AS `segundo_nombre`,`t7`.`primer_apellido` AS `primer_apellido`,`t7`.`segundo_apellido` AS `segundo_apellido`,`t7`.`casada_apellido` AS `casada_apellido`,`t7`.`sexo` AS `sexo`,(case when (`t7`.`sexo` = 'F') then 'Mujer' else 'Hombre' end) AS `per_sexo`,date_format(`t7`.`fecha_nacimiento`,'%d-%m-%Y') AS `fecha_nacimiento`,`t1`.`per_edad` AS `per_edad`,`t1`.`per_tipo_edad` AS `per_id_tipo_edad`,(case when (`t1`.`per_tipo_edad` = 3) then 'Años' when (`t1`.`per_tipo_edad` = 2) then 'Meses' else 'Días' end) AS `per_tipo_edad`,`t1`.`riesgo_embarazo` AS `riesgo_embarazo`,`t1`.`riesgo_semana` AS `riesgo_semana`,`t1`.`id_profesion` AS `id_profesion`,`t14`.`nombre_ocupacion` AS `nombre_ocupacion`,`t11`.`id_pais` AS `per_id_pais`,trim(`t11`.`nombre_pais`) AS `per_nombre_pais`,`t10`.`id_provincia` AS `per_id_provincia`,trim(`t10`.`nombre_provincia`) AS `per_nombre_provincia`,`t9`.`id_distrito` AS `per_id_distrito`,trim(`t9`.`nombre_distrito`) AS `per_nombre_distrio`,`t8`.`id_corregimiento` AS `per_id_corregimiento`,trim(`t8`.`nombre_corregimiento`) AS `per_nombre_corregimiento`,`t1`.`per_direccion` AS `per_direccion`,`t1`.`per_telefono` AS `per_telefono`,`t7`.`id_etnia` AS `id_grupo_poblacion`,`t13`.`nombre_grupo_poblacion` AS `nombre_grupo_poblacion`,`t1`.`per_empleado` AS `per_empleado`,`t7`.`id_estado_civil` AS `id_estado_civil`,`t7`.`id_escolaridad` AS `id_escolaridad`,`t1`.`per_nombre_referencia` AS `per_nombre_referencia`,`t1`.`per_parentesco` AS `per_parentesco`,`t1`.`per_telefono_referencia` AS `per_telefono_referencia`,`t1`.`per_antes_preso` AS `per_antes_preso`,`t1`.`ant_diabetes` AS `ant_diabetes`,`t1`.`ant_preso` AS `ant_preso`,date_format(`t1`.`ant_fecha_preso`,'d%-m%-%Y') AS `ant_fecha_preso`,`t1`.`ant_drug` AS `ant_drug`,`t1`.`ant_alcoholism` AS `ant_alcoholism`,`t1`.`ant_smoking` AS `ant_smoking`,`t1`.`ant_mining` AS `ant_mining`,`t1`.`ant_overcrowding` AS `ant_overcrowding`,`t1`.`ant_indigence` AS `ant_indigence`,`t1`.`ant_drinkable` AS `ant_drinkable`,`t1`.`ant_sanitation` AS `ant_sanitation`,`t1`.`ant_contactposi` AS `ant_contactposi`,`t1`.`ant_BCG` AS `ant_BCG`,`t1`.`ant_weight` AS `ant_weight_kg`,`t1`.`ant_height` AS `ant_height`,date_format(`t1`.`mat_diag_fecha_BK1`,'%d-%m-%Y') AS `mat_diag_fecha_BK1`,`t1`.`mat_diag_resultado_BK1` AS `mat_diag_resultado_BK1`,`t1`.`id_clasificacion_BK1` AS `id_clasificacion_BK1`,date_format(`t1`.`mat_diag_fecha_BK2`,'%d-%m-%Y') AS `mat_diag_fecha_BK2`,`t1`.`mat_diag_resultado_BK2` AS `mat_diag_resultado_BK2`,`t1`.`id_clasificacion_BK2` AS `id_clasificacion_BK2`,date_format(`t1`.`mat_diag_fecha_BK3`,'%d-%m-%Y') AS `mat_diag_fecha_BK3`,`t1`.`mat_diag_resultado_BK3` AS `mat_diag_resultado_BK3`,`t1`.`id_clasificacion_BK3` AS `id_clasificacion_BK3`,(case when (`t1`.`mat_diag_res_cultivo` = 1) then 'Micobacterium no tuberculosas' when (`t1`.`mat_diag_res_cultivo` = 2) then 'Micobacterium tuberculosis' when (`t1`.`mat_diag_res_cultivo` = 3) then 'No hubo crecimiento de micobacterias' else '' end) AS `mat_diag_res_cultivo`,date_format(`t1`.`mat_diag_fecha_res_cultivo`,'%d-%m-%Y') AS `mat_diag_fecha_res_cultivo`,`t1`.`mat_diag_metodo_WRD` AS `mat_diag_metodo_WRD`,`t1`.`mat_diag_res_metodo_WRD` AS `mat_diag_res_metodo_WRD`,date_format(`t1`.`mat_diag_fecha_res_WRD`,'%d-%m-%Y') AS `mat_diag_fecha_res_WRD`,`t1`.`mat_diag_res_clinico` AS `mat_diag_res_clinico`,date_format(`t1`.`mat_diag_fecha_clinico`,'%d-%m-%Y') AS `mat_diag_fecha_clinico`,`t1`.`mat_diag_res_R_X` AS `mat_diag_res_R_X`,date_format(`t1`.`mat_diag_fecha_R_X`,'%d-%m-%Y') AS `mat_diag_fecha_R_X`,`t1`.`mat_diag_res_histopa` AS `mat_diag_res_histopa`,date_format(`t1`.`mat_diag_fecha_histopa`,'%d-%m-%Y') AS `mat_diag_fecha_histopa`,`t1`.`clasificacion_tb` AS `clasificacion_tb`,`t1`.`clas_pulmonar_EP` AS `clas_pulmonar_EP`,`t1`.`clas_lugar_EP` AS `clas_lugar_EP`,`t1`.`clas_trat_previo` AS `clas_trat_previo`,`t1`.`clas_recaida` AS `clas_recaida`,`t1`.`clas_postfracaso` AS `clas_postfracaso`,`t1`.`clas_perdsegui` AS `clas_perdsegui`,`t1`.`clas_otros_antestratado` AS `clas_otros_antestratado`,`t1`.`clas_diag_VIH` AS `clas_diag_VIH`,date_format(`t1`.`clas_fecha_diag_VIH`,'%d-%m-%Y') AS `clas_fecha_diag_VIH`,`t1`.`clas_met_diag` AS `clas_met_diag`,`t1`.`clas_esp_MonoR` AS `clas_esp_MonoR`,`t1`.`clas_PoliR_H` AS `clas_PoliR_H`,`t1`.`clas_PoliR_R` AS `clas_PoliR_R`,`t1`.`clas_PoliR_Z` AS `clas_PoliR_Z`,`t1`.`clas_PoliR_E` AS `clas_PoliR_E`,`t1`.`clas_PoliR_S` AS `clas_PoliR_S`,`t1`.`clas_PoliR_fluoroquinolonas` AS `clas_PoliR_fluoroquinolonas`,`t1`.`clas_PoliR_2linea` AS `clas_PoliR_2linea`,`t1`.`clas_id_fluoroquinolonas` AS `clas_id_fluoroquinolonas`,`t1`.`clas_id_2linea` AS `clas_id_2linea`,`t1`.`trat_referido` AS `trat_referido`,`t1`.`trat_inst_salud_ref` AS `trat_inst_salud_ref`,`t15`.`nombre_un` AS `trat_nombre_inst_ref`,date_format(`t1`.`trat_fecha_inicio_tratF1`,'%d-%m-%Y') AS `trat_fechafor_inicio_tratF1`,`t1`.`trat_fecha_inicio_tratF1` AS `trat_fecha_inicio_tratF1`,`t1`.`trat_med_H_F1` AS `trat_med_H_F1`,`t1`.`trat_med_R_F1` AS `trat_med_R_F1`,`t1`.`trat_med_Z_F1` AS `trat_med_Z_F1`,`t1`.`trat_med_E_F1` AS `trat_med_E_F1`,`t1`.`trat_med_S_F1` AS `trat_med_S_F1`,`t1`.`trat_med_otros_F1` AS `trat_med_otros_F1`,date_format(`t1`.`trat_fecha_fin_tratF1`,'%d-%m-%Y') AS `trat_fecha_fin_tratF1`,`t1`.`id_adm_tratamiento_F1` AS `id_adm_tratamiento_F1`,date_format(`t1`.`trat_fecha_inicio_tratF2`,'%d-%m-%Y') AS `trat_fecha_inicio_tratF2`,`t1`.`trat_med_H_F2` AS `trat_med_H_F2`,`t1`.`trat_med_R_F2` AS `trat_med_R_F2`,`t1`.`trat_med_E_F2` AS `trat_med_E_F2`,`t1`.`trat_med_otros_F2` AS `trat_med_otros_F2`,date_format(`t1`.`trat_fecha_fin_tratF2`,'%d-%m-%Y') AS `trat_fecha_fin_tratF2`,`t1`.`id_adm_tratamiento_F2` AS `id_adm_tratamiento_F2`,date_format(`t1`.`TB_VIH_fecha_prueba_VIH`,'%d-%m-%Y') AS `TB_VIH_fecha_prueba_VIH`,`t1`.`TB_VIH_res_previa_VIH` AS `TB_VIH_res_previa_VIH`,`t1`.`TB_VIH_aseso_VIH` AS `TB_VIH_aseso_VIH`,`t1`.`TB_VIH_cotrimoxazol` AS `TB_VIH_cotrimoxazol`,date_format(`t1`.`TB_VIH_fecha_cotrimoxazol`,'%d-%m-%Y') AS `TB_VIH_fecha_cotrimoxazol`,`t1`.`TB_VIH_act_TARV` AS `TB_VIH_act_TARV`,date_format(`t1`.`TB_VIH_fecha_inicio_TARV`,'%d-%m-%Y') AS `TB_VIH_fecha_inicio_TARV`,`t1`.`TB_VIH_lug_adm_TARV` AS `TB_VIH_lug_adm_TARV`,`t16`.`nombre_un` AS `TB_VIH_nombre_un_TARV`,`t1`.`TB_VIH_isoniacida` AS `TB_VIH_isoniacida`,`t1`.`contacto_identificados_5min` AS `contacto_identificados_5min`,`t1`.`contacto_sinto_resp_5min` AS `contacto_sinto_resp_5min`,`t1`.`contacto_evaluados_5min` AS `contacto_evaluados_5min`,`t1`.`contacto_quimioprofilaxis_5min` AS `contacto_quimioprofilaxis_5min`,`t1`.`contacto_TB_5min` AS `contacto_TB_5min`,`t1`.`contacto_identificados_5pl` AS `contacto_identificados_5pl`,`t1`.`contacto_sinto_resp_5pl` AS `contacto_sinto_resp_5pl`,`t1`.`contacto_evaluados_5pl` AS `contacto_evaluados_5pl`,`t1`.`contacto_quimioprofilaxis_5pl` AS `contacto_quimioprofilaxis_5pl`,`t1`.`contacto_TB_5pl` AS `contacto_TB_5pl`,`t1`.`apoyo_social` AS `apoyo_social`,`t1`.`apoyo_nutricional` AS `apoyo_nutricional`,`t1`.`apoyo_economico` AS `apoyo_economico`,`t1`.`egreso_fecha_egreso` AS `egreso_fecha_egreso`,`t1`.`egreso_cond_egreso` AS `egreso_cond_egreso`,`t1`.`egreso_motivo_exclusion` AS `egreso_motivo_exclusion`,`t1`.`anio` AS `anio`,`t1`.`semana_epi` AS `semana_epi`,`t1`.`nombre_toma_muestra` AS `nombre_toma_muestra`,`t1`.`pendiente_uceti` AS `pendiente_uceti`,`t1`.`pendiente_silab` AS `pendiente_silab`,`t1`.`actualizacion_silab` AS `actualizacion_silab`,`t1`.`source_entry` AS `source_entry` from (((((((((((((((`tb_form` `t1` left join `cat_corregimiento` `t2` on((`t1`.`id_corregimiento` = `t2`.`id_corregimiento`))) left join `cat_distrito` `t3` on((`t3`.`id_distrito` = `t2`.`id_distrito`))) left join `cat_region_salud` `t4` on((`t4`.`id_region` = `t3`.`id_region`))) left join `cat_provincia` `t5` on((`t5`.`id_provincia` = `t4`.`id_provincia`))) left join `cat_unidad_notificadora` `t6` on((`t6`.`id_un` = `t1`.`id_un`))) join `tbl_persona` `t7` on(((`t1`.`numero_identificacion` = `t7`.`numero_identificacion`) and (`t1`.`tipo_identificacion` = `t7`.`tipo_identificacion`)))) join `cat_tipo_identidad` `t12` on((`t12`.`id_tipo_identidad` = `t7`.`tipo_identificacion`))) left join `cat_corregimiento` `t8` on((`t8`.`id_corregimiento` = `t7`.`id_corregimiento`))) left join `cat_distrito` `t9` on((`t9`.`id_distrito` = `t8`.`id_distrito`))) left join `cat_provincia` `t10` on((`t10`.`id_provincia` = `t9`.`id_provincia`))) left join `cat_pais` `t11` on((`t11`.`id_pais` = `t7`.`id_pais`))) left join `cat_grupo_poblacion` `t13` on((`t13`.`id_grupo_poblacion` = `t7`.`id_etnia`))) left join `cat_ocupacion` `t14` on((`t14`.`id_ocupacion` = `t1`.`id_profesion`))) left join `cat_unidad_notificadora` `t15` on((`t15`.`id_un` = `t1`.`trat_inst_salud_ref`))) left join `cat_unidad_notificadora` `t16` on((`t16`.`id_un` = `t1`.`TB_VIH_lug_adm_TARV`)));

-- --------------------------------------------------------

--
-- Structure for view `view_tb_sabana_excel`
--
DROP TABLE IF EXISTS `view_tb_sabana_excel`;

CREATE  VIEW `view_tb_sabana_excel` AS select `T1`.`id_tb` AS `id_tb`,`T2`.`id_corregimiento` AS `id_corregimiento`,`T2`.`nombre_corregimiento` AS `nombre_corregimiento`,`T3`.`id_distrito` AS `id_distrito`,`T3`.`nombre_distrito` AS `nombre_distrito`,`T4`.`id_region` AS `id_region`,`T4`.`nombre_region` AS `nombre_region`,`T5`.`id_provincia` AS `id_provincia`,`T5`.`nombre_provincia` AS `nombre_provincia`,`T6`.`id_un` AS `id_un`,`T6`.`nombre_un` AS `nombre_un`,`T1`.`anio` AS `anio`,`T1`.`semana_epi` AS `semana_epi`,`T1`.`nombre_investigador` AS `nombre_investigador`,date_format(`T1`.`fecha_formulario`,'%d-%m-%Y') AS `fecha_formulario`,`T1`.`fecha_notificacion` AS `fecha_noti`,date_format(`T1`.`fecha_notificacion`,'%d-%m-%Y') AS `fecha_notificacion`,`T1`.`nombre_registra` AS `nombre_registra`,`T12`.`nombre_tipo` AS `nombre_tipo`,`T1`.`numero_identificacion` AS `numero_identificacion`,`T7`.`primer_nombre` AS `primer_nombre`,`T7`.`segundo_nombre` AS `segundo_nombre`,`T7`.`primer_apellido` AS `primer_apellido`,`T7`.`segundo_apellido` AS `segundo_apellido`,`T7`.`casada_apellido` AS `casada_apellido`,date_format(`T7`.`fecha_nacimiento`,'%d-%m-%Y') AS `fecha_nacimiento`,(case when (`T1`.`per_tipo_edad` = 3) then 'Años' when (`T1`.`per_tipo_edad` = 2) then 'Meses' else 'Días' end) AS `per_tipo_edad`,`T1`.`per_edad` AS `per_edad`,(case when (`T7`.`sexo` = 'F') then 'Mujer' else 'Hombre' end) AS `per_sexo`,(case when (`T1`.`riesgo_embarazo` = 1) then 'Si' when (`T1`.`riesgo_embarazo` = 0) then 'No' else 'No sabe' end) AS `riesgo_embarazo`,`T1`.`riesgo_semana` AS `riesgo_semana`,`T13`.`nombre_gpopoblacional` AS `nombre_gpopoblacional`,`T25`.`nombre_etnia` AS `nombre_etnia`,(case when (`T1`.`per_empleado` = 1) then 'Si' when (`T1`.`per_empleado` = 0) then 'No' else 'No sabe' end) AS `per_empleado`,`T14`.`nombre_profesion` AS `nombre_profesion`,(case when (`T7`.`id_estado_civil` = 1) then 'Soltero' when (`T7`.`id_estado_civil` = 2) then 'Casado' when (`T7`.`id_estado_civil` = 3) then 'Unido' when (`T7`.`id_estado_civil` = 4) then 'Divorciado' else 'NE' end) AS `id_estado_civil`,(case when (`T7`.`id_escolaridad` = 1) then 'Primaria' when (`T7`.`id_escolaridad` = 2) then 'Secundaria' when (`T7`.`id_escolaridad` = 3) then 'Secundaria' when (`T7`.`id_escolaridad` = 4) then 'Universidad' when (`T7`.`id_escolaridad` = 5) then 'Universidad' when (`T7`.`id_escolaridad` = 6) then 'Vocacional' when (`T7`.`id_escolaridad` = 7) then 'Ninguna' else 'NE' end) AS `id_escolaridad`,`T1`.`per_direccion` AS `per_direccion`,`T1`.`per_telefono` AS `per_telefono`,`T1`.`per_nombre_referencia` AS `per_nombre_referencia`,`T1`.`per_parentesco` AS `per_parentesco`,`T1`.`per_telefono_referencia` AS `per_telefono_referencia`,(case when (`T1`.`per_antes_preso` = 1) then 'Si' when (`T1`.`per_antes_preso` = 0) then 'No' else 'No sabe' end) AS `per_antes_preso`,date_format(`T1`.`per_fecha_antespreso`,'d%-m%-%Y') AS `per_fecha_antespreso`,(case when (`T1`.`ant_diabetes` = 1) then 'Si' when (`T1`.`ant_diabetes` = 0) then 'No' else 'No sabe' end) AS `ant_diabetes`,(case when (`T1`.`ant_preso` = 1) then 'Si' when (`T1`.`ant_preso` = 0) then 'No' else 'No sabe' end) AS `ant_preso`,date_format(`T1`.`ant_fecha_preso`,'d%-m%-%Y') AS `ant_fecha_preso`,(case when (`T1`.`ant_drug` = 1) then 'Si' when (`T1`.`ant_drug` = 0) then 'No' else 'No sabe' end) AS `ant_drug`,(case when (`T1`.`ant_alcoholism` = 1) then 'Si' when (`T1`.`ant_alcoholism` = 0) then 'No' else 'No sabe' end) AS `ant_alcoholism`,(case when (`T1`.`ant_smoking` = 1) then 'Si' when (`T1`.`ant_smoking` = 0) then 'No' else 'No sabe' end) AS `ant_smoking`,(case when (`T1`.`ant_mining` = 1) then 'Si' when (`T1`.`ant_mining` = 0) then 'No' else 'No sabe' end) AS `ant_mining`,(case when (`T1`.`ant_overcrowding` = 1) then 'Si' when (`T1`.`ant_overcrowding` = 0) then 'No' else 'No sabe' end) AS `ant_overcrowding`,(case when (`T1`.`ant_indigence` = 1) then 'Si' when (`T1`.`ant_indigence` = 0) then 'No' else 'No sabe' end) AS `ant_indigence`,(case when (`T1`.`ant_drinkable` = 1) then 'Si' when (`T1`.`ant_drinkable` = 0) then 'No' else 'No sabe' end) AS `ant_drinkable`,(case when (`T1`.`ant_sanitation` = 1) then 'Si' when (`T1`.`ant_sanitation` = 0) then 'No' else 'No sabe' end) AS `ant_sanitation`,(case when (`T1`.`ant_contactposi` = 1) then 'Si' when (`T1`.`ant_contactposi` = 0) then 'No' else 'No sabe' end) AS `ant_contactposi`,(case when (`T1`.`ant_BCG` = 1) then 'Si' when (`T1`.`ant_BCG` = 0) then 'No' else 'No sabe' end) AS `ant_BCG`,`T1`.`ant_weight` AS `ant_weight_kg`,`T1`.`ant_height` AS `ant_height`,date_format(`T1`.`mat_diag_fecha_BK1`,'%d-%m-%Y') AS `mat_diag_fecha_BK1`,(case when (`T1`.`mat_diag_resultado_BK1` = 1) then 'Positivo' when (`T1`.`mat_diag_resultado_BK1` = 0) then 'Negativo' else '' end) AS `mat_diag_resultado_BK1`,`T17`.`nombre_clasificacion_BK` AS `nombre_clasificacion_BK1`,date_format(`T1`.`mat_diag_fecha_BK2`,'%d-%m-%Y') AS `mat_diag_fecha_BK2`,(case when (`T1`.`mat_diag_resultado_BK2` = 1) then 'Positivo' when (`T1`.`mat_diag_resultado_BK2` = 0) then 'Negativo' else '' end) AS `mat_diag_resultado_BK2`,`T18`.`nombre_clasificacion_BK` AS `nombre_clasificacion_BK2`,date_format(`T1`.`mat_diag_fecha_BK3`,'%d-%m-%Y') AS `mat_diag_fecha_BK3`,(case when (`T1`.`mat_diag_resultado_BK3` = 1) then 'Positivo' when (`T1`.`mat_diag_resultado_BK3` = 0) then 'Negativo' else '' end) AS `mat_diag_resultado_BK3`,`T19`.`nombre_clasificacion_BK` AS `nombre_clasificacion_BK3`,(case when (`T1`.`mat_diag_res_cultivo` = 1) then 'Micobacterium no tuberculosas' when (`T1`.`mat_diag_res_cultivo` = 2) then 'Micobacterium tuberculosis' when (`T1`.`mat_diag_res_cultivo` = 3) then 'No hubo crecimiento de micobacterias' else '' end) AS `mat_diag_res_cultivo`,date_format(`T1`.`mat_diag_fecha_res_cultivo`,'%d-%m-%Y') AS `mat_diag_fecha_res_cultivo`,(case when (`T1`.`mat_diag_metodo_WRD` = 1) then 'Xpert MTB/RIF' when (`T1`.`mat_diag_metodo_WRD` = 2) then 'Otro' else '' end) AS `mat_diag_metodo_WRD`,(case when (`T1`.`mat_diag_res_metodo_WRD` = 1) then 'Positivo' when (`T1`.`mat_diag_res_metodo_WRD` = 0) then 'Negativo' else '' end) AS `mat_diag_res_metodo_WRD`,date_format(`T1`.`mat_diag_fecha_res_WRD`,'%d-%m-%Y') AS `mat_diag_fecha_res_WRD`,(case when (`T1`.`mat_diag_res_clinico` = 1) then 'Positivo' when (`T1`.`mat_diag_res_clinico` = 0) then 'Negativo' else '' end) AS `mat_diag_res_clinico`,date_format(`T1`.`mat_diag_fecha_clinico`,'%d-%m-%Y') AS `mat_diag_fecha_clinico`,(case when (`T1`.`mat_diag_res_R_X` = 1) then 'Positivo' when (`T1`.`mat_diag_res_R_X` = 0) then 'Negativo' else '' end) AS `mat_diag_res_R_X`,date_format(`T1`.`mat_diag_fecha_R_X`,'%d-%m-%Y') AS `mat_diag_fecha_R_X`,(case when (`T1`.`mat_diag_res_histopa` = 1) then 'Positivo' when (`T1`.`mat_diag_res_histopa` = 0) then 'Negativo' else '' end) AS `mat_diag_res_histopa`,date_format(`T1`.`mat_diag_fecha_histopa`,'%d-%m-%Y') AS `mat_diag_fecha_histopa`,(case when (`T1`.`clasificacion_tb` = 1) then 'clinicamente' when (`T1`.`clas_recaida` = 0) then 'bacteriologicamente' else '' end) AS `clasificacion_tb`,(case when (`T1`.`clas_pulmonar_EP` = 1) then 'Pulmonar' else 'Extrapulmonar(EP)' end) AS `clas_pulmonar_EP`,(case when (`T1`.`clas_lugar_EP` = 1) then 'Meningea' when (`T1`.`clas_lugar_EP` = 2) then 'Otra' else '' end) AS `clas_lugar_EP`,(case when (`T1`.`clas_trat_previo` = 1) then 'Nuevo' when (`T1`.`clas_trat_previo` = 2) then 'Antes tratado' else 'Pacientes con historia desconocida de tratamientos previo por TB' end) AS `clas_trat_previo`,(case when (`T1`.`clas_recaida` = 1) then 'Si' when (`T1`.`clas_recaida` = 0) then 'No' else '' end) AS `clas_recaida`,(case when (`T1`.`clas_postfracaso` = 1) then 'Si' when (`T1`.`clas_postfracaso` = 0) then 'No' else '' end) AS `clas_postfracaso`,(case when (`T1`.`clas_perdsegui` = 1) then 'Si' when (`T1`.`clas_perdsegui` = 0) then 'No' else '' end) AS `clas_perdsegui`,(case when (`T1`.`clas_otros_antestratado` = 1) then 'Si' when (`T1`.`clas_otros_antestratado` = 0) then 'No' else '' end) AS `clas_otros_antestratado`,(case when (`T1`.`clas_diag_VIH` = 1) then 'Positivo' when (`T1`.`clas_diag_VIH` = 0) then 'Negativo' when (`T1`.`clas_diag_VIH` = 2) then 'Desconocido' else '' end) AS `clas_diag_VIH`,date_format(`T1`.`clas_fecha_diag_VIH`,'%d-%m-%Y') AS `clas_fecha_diag_VIH`,(case when (`T1`.`clas_met_diag` = 1) then 'Ninguna' when (`T1`.`clas_met_diag` = 2) then 'MonoR' when (`T1`.`clas_met_diag` = 3) then 'PoliR' when (`T1`.`clas_met_diag` = 4) then 'MDR' when (`T1`.`clas_met_diag` = 5) then 'XDR' when (`T1`.`clas_met_diag` = 6) then 'TB-RR' when (`T1`.`clas_met_diag` = 7) then 'Desconocida' else '' end) AS `clas_met_diag`,(case when (`T1`.`clas_esp_MonoR` = 1) then 'H' when (`T1`.`clas_esp_MonoR` = 2) then 'R' when (`T1`.`clas_esp_MonoR` = 3) then 'Z' when (`T1`.`clas_esp_MonoR` = 4) then 'E' when (`T1`.`clas_esp_MonoR` = 5) then 'S' else '' end) AS `clas_esp_MonoR`,(case when (`T1`.`clas_PoliR_H` = 1) then 'Si' else 'No' end) AS `clas_PoliR_H`,(case when (`T1`.`clas_PoliR_R` = 1) then 'Si' else 'No' end) AS `clas_PoliR_R`,(case when (`T1`.`clas_PoliR_Z` = 1) then 'Si' else 'No' end) AS `clas_PoliR_Z`,(case when (`T1`.`clas_PoliR_E` = 1) then 'Si' else 'No' end) AS `clas_PoliR_E`,(case when (`T1`.`clas_PoliR_S` = 1) then 'Si' else 'No' end) AS `clas_PoliR_S`,(case when (`T1`.`clas_PoliR_fluoroquinolonas` = 1) then 'Si' else 'No' end) AS `clas_PoliR_fluoroquinolonas`,`T20`.`nombre_fluoroquinolonas` AS `nombre_fluoroquinolonas`,(case when (`T1`.`clas_PoliR_2linea` = 1) then 'Si' else 'No' end) AS `clas_PoliR_2linea`,`T21`.`nombre_inyect_2linea` AS `nombre_inyect_2linea`,(case when (`T1`.`trat_referido` = 1) then 'Si' when (`T1`.`trat_referido` = 0) then 'No' else '' end) AS `trat_referido`,`T15`.`nombre_un` AS `trat_nombre_inst_ref`,`T1`.`trat_fecha_inicio_tratF1` AS `fecha_fase1`,date_format(`T1`.`trat_fecha_inicio_tratF1`,'%d-%m-%Y') AS `trat_fecha_inicio_tratF1`,(case when (`T1`.`trat_med_H_F1` = 1) then 'Si' else 'No' end) AS `trat_med_H_F1`,(case when (`T1`.`trat_med_Z_F1` = 2) then 'Si' else 'No' end) AS `trat_med_Z_F1`,(case when (`T1`.`trat_med_R_F1` = 3) then 'Si' else 'No' end) AS `trat_med_R_F1`,(case when (`T1`.`trat_med_E_F1` = 4) then 'Si' else 'No' end) AS `trat_med_E_F1`,(case when (`T1`.`trat_med_S_F1` = 5) then 'Si' else 'No' end) AS `trat_med_S_F1`,(case when (`T1`.`trat_med_otros_F1` = 6) then 'Si' else 'No' end) AS `trat_med_otros_F1`,date_format(`T1`.`trat_fecha_fin_tratF1`,'%d-%m-%Y') AS `trat_fecha_fin_tratF1`,`T22`.`nombre_adm_tratamiento` AS `nombre_adm_tratamientoF1`,date_format(`T1`.`trat_fecha_inicio_tratF2`,'%d-%m-%Y') AS `trat_fecha_inicio_tratF2`,(case when (`T1`.`trat_med_H_F2` = 1) then 'Si' else 'No' end) AS `trat_med_H_F2`,(case when (`T1`.`trat_med_R_F2` = 2) then 'Si' else 'No' end) AS `trat_med_R_F2`,(case when (`T1`.`trat_med_E_F2` = 3) then 'Si' else 'No' end) AS `trat_med_E_F2`,(case when (`T1`.`trat_med_otros_F2` = 4) then 'Si' else 'No' end) AS `trat_med_otros_F2`,date_format(`T1`.`trat_fecha_fin_tratF2`,'%d-%m-%Y') AS `trat_fecha_fin_tratF2`,`T23`.`nombre_adm_tratamiento` AS `nombre_adm_tratamientoF2`,(case when (`T1`.`TB_VIH_solicitud_VIH` = 1) then 'Si' when (`T1`.`TB_VIH_solicitud_VIH` = 0) then 'No' else '' end) AS `TB_VIH_solicitud_VIH`,(case when (`T1`.`TB_VIH_acepto_VIH` = 1) then 'Si' when (`T1`.`TB_VIH_acepto_VIH` = 0) then 'No' else '' end) AS `TB_VIH_acepto_VIH`,(case when (`T1`.`TB_VIH_realizada_VIH` = 1) then 'Si' when (`T1`.`TB_VIH_realizada_VIH` = 0) then 'No' else '' end) AS `TB_VIH_realizada_VIH`,date_format(`T1`.`TB_VIH_fecha_muestra_VIH`,'%d-%m-%Y') AS `TB_VIH_fecha_muestra_VIH`,(case when (`T1`.`TB_VIH_res_VIH` = 1) then 'Positivo' when (`T1`.`TB_VIH_res_VIH` = 0) then 'Negativo' else '' end) AS `TB_VIH_res_VIH`,date_format(`T1`.`TB_VIH_fecha_prueba_VIH`,'%d-%m-%Y') AS `TB_VIH_fecha_prueba_VIH`,(case when (`T1`.`TB_VIH_res_previa_VIH` = 1) then 'Positivo' when (`T1`.`TB_VIH_res_previa_VIH` = 0) then 'Negativo' else '' end) AS `TB_VIH_res_previa_VIH`,(case when (`T1`.`TB_VIH_aseso_VIH` = 1) then 'Si' when (`T1`.`TB_VIH_aseso_VIH` = 0) then 'No' else '' end) AS `TB_VIH_aseso_VIH`,(case when (`T1`.`TB_VIH_cotrimoxazol` = 1) then 'Si' when (`T1`.`TB_VIH_cotrimoxazol` = 0) then 'No' else '' end) AS `TB_VIH_cotrimoxazol`,date_format(`T1`.`TB_VIH_fecha_cotrimoxazol`,'%d-%m-%Y') AS `TB_VIH_fecha_cotrimoxazol`,(case when (`T1`.`TB_VIH_ref_TARV` = 1) then 'Si' when (`T1`.`TB_VIH_ref_TARV` = 0) then 'No' else '' end) AS `TB_VIH_ref_TARV`,(case when (`T1`.`TB_VIH_inicio_TARV` = 1) then 'Si' when (`T1`.`TB_VIH_inicio_TARV` = 0) then 'No' else '' end) AS `TB_VIH_inicio_TARV`,(case when (`T1`.`TB_VIH_act_TARV` = 1) then 'Si' when (`T1`.`TB_VIH_act_TARV` = 0) then 'No' else '' end) AS `TB_VIH_act_TARV`,date_format(`T1`.`TB_VIH_fecha_inicio_TARV`,'%d-%m-%Y') AS `TB_VIH_fecha_inicio_TARV`,`T16`.`nombre_un` AS `TB_VIH_nombre_un_TARV`,(case when (`T1`.`TB_VIH_isoniacida` = 1) then 'Si' when (`T1`.`TB_VIH_isoniacida` = 0) then 'No' else '' end) AS `TB_VIH_isoniacida`,`T1`.`contacto_identificados_5min` AS `contacto_identificados_5min`,`T1`.`contacto_sinto_resp_5min` AS `contacto_sinto_resp_5min`,`T1`.`contacto_evaluados_5min` AS `contacto_evaluados_5min`,`T1`.`contacto_quimioprofilaxis_5min` AS `contacto_quimioprofilaxis_5min`,`T1`.`contacto_TB_5min` AS `contacto_TB_5min`,`T1`.`contacto_identificados_5pl` AS `contacto_identificados_5pl`,`T1`.`contacto_sinto_resp_5pl` AS `contacto_sinto_resp_5pl`,`T1`.`contacto_evaluados_5pl` AS `contacto_evaluados_5pl`,`T1`.`contacto_quimioprofilaxis_5pl` AS `contacto_quimioprofilaxis_5pl`,`T1`.`contacto_TB_5pl` AS `contacto_TB_5pl`,(case when (`T1`.`apoyo_social` = 1) then 'Si' when (`T1`.`apoyo_social` = 0) then 'No' else '' end) AS `apoyo_social`,(case when (`T1`.`apoyo_nutricional` = 1) then 'Si' when (`T1`.`apoyo_nutricional` = 0) then 'No' else '' end) AS `apoyo_nutricional`,(case when (`T1`.`apoyo_economico` = 1) then 'Si' when (`T1`.`apoyo_economico` = 0) then 'No' else '' end) AS `apoyo_economico`,date_format(`T1`.`egreso_fecha_egreso`,'%d-%m-%Y') AS `egreso_fecha_egreso`,(case when (`T1`.`egreso_cond_egreso` = 1) then 'Curado' when (`T1`.`egreso_cond_egreso` = 2) then 'Termino tratamiento' when (`T1`.`egreso_cond_egreso` = 3) then 'Perdido duran. sgto' when (`T1`.`egreso_cond_egreso` = 4) then 'Muerte' when (`T1`.`egreso_cond_egreso` = 5) then 'Fracaso' when (`T1`.`egreso_cond_egreso` = 6) then 'No evaluado' when (`T1`.`egreso_cond_egreso` = 7) then 'Exclusión' else '' end) AS `egreso_cond_egreso`,`T24`.`nombre_exclusion` AS `egreso_motivo_exclusion` from ((((((((((((((((((((`tb_form` `T1` left join `cat_corregimiento` `T2` on((`T1`.`id_corregimiento` = `T2`.`id_corregimiento`))) left join `cat_distrito` `T3` on((`T3`.`id_distrito` = `T2`.`id_distrito`))) left join `cat_region_salud` `T4` on((`T4`.`id_region` = `T3`.`id_region`))) left join `cat_provincia` `T5` on((`T5`.`id_provincia` = `T4`.`id_provincia`))) left join `cat_unidad_notificadora` `T6` on((`T6`.`id_un` = `T1`.`id_un`))) join `tbl_persona` `T7` on(((`T1`.`numero_identificacion` = `T7`.`numero_identificacion`) and (`T1`.`tipo_identificacion` = `T7`.`tipo_identificacion`)))) join `cat_tipo_identidad` `T12` on((`T12`.`id_tipo_identidad` = `T7`.`tipo_identificacion`))) left join `cat_gpopoblacional` `T13` on((`T13`.`id_gpopoblacional` = `T7`.`id_gpopoblacional`))) left join `cat_profesion` `T14` on((`T14`.`id_profesion` = `T1`.`id_profesion`))) left join `cat_unidad_notificadora` `T15` on((`T15`.`id_un` = `T1`.`trat_inst_salud_ref`))) left join `cat_unidad_notificadora` `T16` on((`T16`.`id_un` = `T1`.`TB_VIH_lug_adm_TARV`))) left join `cat_clasificacion_bk` `T17` on((`T17`.`id_clasificacion_BK` = `T1`.`id_clasificacion_BK1`))) left join `cat_clasificacion_bk` `T18` on((`T18`.`id_clasificacion_BK` = `T1`.`id_clasificacion_BK2`))) left join `cat_clasificacion_bk` `T19` on((`T19`.`id_clasificacion_BK` = `T1`.`id_clasificacion_BK3`))) left join `cat_fluoroquinolonas` `T20` on((`T20`.`id_fluoroquinolonas` = `T1`.`clas_id_fluoroquinolonas`))) left join `cat_inyect_2linea` `T21` on((`T21`.`id_inyect_2linea` = `T1`.`clas_id_2linea`))) left join `cat_adm_tratamiento` `T22` on((`T22`.`id_adm_tratamiento` = `T1`.`id_adm_tratamiento_F1`))) left join `cat_adm_tratamiento` `T23` on((`T23`.`id_adm_tratamiento` = `T1`.`id_adm_tratamiento_F2`))) left join `cat_exclusion` `T24` on((`T24`.`id_exclusion` = `T1`.`egreso_motivo_exclusion`))) left join `cat_etnia_tb` `T25` on((`T25`.`id_etnia` = `T7`.`id_etnia`)));

-- --------------------------------------------------------

--
-- Structure for view `view_vigmor`
--
DROP TABLE IF EXISTS `view_vigmor`;

CREATE  VIEW `view_vigmor` AS select `reg`.`id_region` AS `id_region`,`reg`.`nombre_region` AS `nombre_region`,`dis`.`id_distrito` AS `id_distrito`,`dis`.`nombre_distrito` AS `nombre_distrito`,`cor`.`id_corregimiento` AS `id_corregimiento`,`cor`.`nombre_corregimiento` AS `nombre_corregimiento`,`cun`.`id_un` AS `id_unidad`,`cun`.`nombre_un` AS `nombre_unidad`,`cun`.`sector_un` AS `sector_un`,`eve`.`id_evento` AS `id_evento`,`eve`.`cie_10_1` AS `cie_10`,`eve`.`id_gevento` AS `id_gevento`,`eve`.`nombre_evento` AS `nombre_evento`,`per`.`sexo` AS `sexo`,`vm_form`.`per_id_pais` AS `per_id_pais`,`vm_form`.`per_id_corregimiento` AS `per_id_corregimiento`,`vm_form`.`semana_epi` AS `semana`,`vm_form`.`anio` AS `anio`,`vm_form`.`nombre_sala` AS `nombre_sala`,`per`.`primer_nombre` AS `primer_nombre`,`vm_form`.`per_edad` AS `edad`,`vm_form`.`per_tipo_edad` AS `tipo_edad`,if((`vm_form`.`per_tipo_edad` > 2),(case when ((`vm_form`.`per_edad` >= 1) and (`vm_form`.`per_edad` <= 4)) then _utf8'1-4' when ((`vm_form`.`per_edad` > 4) and (`vm_form`.`per_edad` <= 9)) then _utf8'5-9' when ((`vm_form`.`per_edad` > 9) and (`vm_form`.`per_edad` <= 14)) then _utf8'10-14' when ((`vm_form`.`per_edad` > 14) and (`vm_form`.`per_edad` <= 19)) then _utf8'15-19' when ((`vm_form`.`per_edad` > 19) and (`vm_form`.`per_edad` <= 24)) then _utf8'20-24' when ((`vm_form`.`per_edad` > 24) and (`vm_form`.`per_edad` <= 34)) then _utf8'25-34' when ((`vm_form`.`per_edad` > 34) and (`vm_form`.`per_edad` <= 49)) then _utf8'35-49' when ((`vm_form`.`per_edad` > 49) and (`vm_form`.`per_edad` <= 59)) then _utf8'50-59' when ((`vm_form`.`per_edad` > 59) and (`vm_form`.`per_edad` <= 64)) then _utf8'60-64' when (`vm_form`.`per_edad` > 65) then _utf8'65 รณ +' else _utf8'N/E' end),_utf8'<1') AS `rango` from (((((((`vm_form` join `cat_unidad_notificadora` `cun` on((`vm_form`.`id_un` = `cun`.`id_un`))) join `cat_region_salud` `reg` on((`cun`.`id_region` = `reg`.`id_region`))) join `cat_corregimiento` `cor` on((`cun`.`id_corregimiento` = `cor`.`id_corregimiento`))) join `cat_distrito` `dis` on((`cor`.`id_distrito` = `dis`.`id_distrito`))) join `cat_provincia` `pas` on((`reg`.`id_provincia` = `pas`.`id_provincia`))) join `cat_evento` `eve` on((`vm_form`.`id_diagnostico1` = `eve`.`id_evento`))) join `tbl_persona` `per` on(((`vm_form`.`tipo_identificacion` = `per`.`tipo_identificacion`) and (`vm_form`.`numero_identificacion` = `per`.`numero_identificacion`))));

-- --------------------------------------------------------

--
-- Structure for view `view_vigmor_casos`
--
DROP TABLE IF EXISTS `view_vigmor_casos`;

CREATE  VIEW `view_vigmor_casos` AS select `view_vigmor`.`nombre_region` AS `nombre_region`,`view_vigmor`.`nombre_distrito` AS `nombre_distrito`,`view_vigmor`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_vigmor`.`nombre_unidad` AS `nombre_unidad`,`view_vigmor`.`id_evento` AS `id_evento`,`view_vigmor`.`cie_10` AS `cie_10`,`view_vigmor`.`nombre_evento` AS `nombre_evento`,`view_vigmor`.`sexo` AS `sexo`,`view_vigmor`.`semana` AS `semana`,`view_vigmor`.`anio` AS `anio`,`view_vigmor`.`rango` AS `rango`,`view_vigmor`.`per_id_pais` AS `per_id_pais`,`view_vigmor`.`per_id_corregimiento` AS `per_id_corregimiento`,`view_vigmor`.`id_unidad` AS `id_unidad`,`view_vigmor`.`sector_un` AS `sector_un`,`view_vigmor`.`id_corregimiento` AS `id_corregimiento`,`view_vigmor`.`id_distrito` AS `id_distrito`,`view_vigmor`.`id_region` AS `id_region`,`view_vigmor`.`id_gevento` AS `id_gevento`,count(`view_vigmor`.`rango`) AS `casos` from `view_vigmor` group by `view_vigmor`.`nombre_region`,`view_vigmor`.`nombre_distrito`,`view_vigmor`.`nombre_unidad`,`view_vigmor`.`nombre_evento`,`view_vigmor`.`sexo`,`view_vigmor`.`semana`,`view_vigmor`.`anio`,`view_vigmor`.`rango`;

-- --------------------------------------------------------

--
-- Structure for view `view_vigmor_rangos`
--
DROP TABLE IF EXISTS `view_vigmor_rangos`;

CREATE  VIEW `view_vigmor_rangos` AS select `view_vigmor_casos`.`nombre_region` AS `nombre_region`,`view_vigmor_casos`.`nombre_distrito` AS `nombre_distrito`,`view_vigmor_casos`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_vigmor_casos`.`nombre_unidad` AS `nombre_unidad`,`view_vigmor_casos`.`id_evento` AS `id_evento`,`view_vigmor_casos`.`cie_10` AS `cie_10`,`view_vigmor_casos`.`nombre_evento` AS `nombre_evento`,`view_vigmor_casos`.`id_gevento` AS `id_gevento`,`view_vigmor_casos`.`sexo` AS `sexo`,`view_vigmor_casos`.`semana` AS `semana`,`view_vigmor_casos`.`anio` AS `anio`,`view_vigmor_casos`.`per_id_pais` AS `per_id_pais`,`view_vigmor_casos`.`per_id_corregimiento` AS `per_id_corregimiento`,`view_vigmor_casos`.`id_unidad` AS `id_unidad`,`view_vigmor_casos`.`sector_un` AS `sector_un`,`view_vigmor_casos`.`id_corregimiento` AS `id_corregimiento`,`view_vigmor_casos`.`id_distrito` AS `id_distrito`,`view_vigmor_casos`.`id_region` AS `id_region`,sum(`view_vigmor_casos`.`casos`) AS `numero_casos`,if((`view_vigmor_casos`.`rango` = _utf8'<1'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `menor_uno`,if((`view_vigmor_casos`.`rango` = _utf8'1-4'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `uno_cuatro`,if((`view_vigmor_casos`.`rango` = _utf8'5-9'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `cinco_nueve`,if((`view_vigmor_casos`.`rango` = _utf8'10-14'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `diez_catorce`,if((`view_vigmor_casos`.`rango` = _utf8'15-19'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `quince_diecinueve`,if((`view_vigmor_casos`.`rango` = _utf8'20-24'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `veinte_veinticuatro`,if((`view_vigmor_casos`.`rango` = _utf8'25-34'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `veinticinco_treinticuatro`,if((`view_vigmor_casos`.`rango` = _utf8'35-49'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `treinticinco_cuarentinueve`,if((`view_vigmor_casos`.`rango` = _utf8'50-59'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `cincuenta_cincuentinueve`,if((`view_vigmor_casos`.`rango` = _utf8'60-64'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `sesenta_sesenticuatro`,if((`view_vigmor_casos`.`rango` = _utf8'65 รณ +'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `mayor_sesenticinco`,if((`view_vigmor_casos`.`rango` = _utf8'N/E'),sum(`view_vigmor_casos`.`casos`),_utf8'0') AS `NE` from `view_vigmor_casos` group by `view_vigmor_casos`.`nombre_region`,`view_vigmor_casos`.`nombre_distrito`,`view_vigmor_casos`.`nombre_corregimiento`,`view_vigmor_casos`.`nombre_evento`,`view_vigmor_casos`.`semana`,`view_vigmor_casos`.`sexo`,`view_vigmor_casos`.`rango`;

-- --------------------------------------------------------

--
-- Structure for view `view_vigmor_reporte`
--
DROP TABLE IF EXISTS `view_vigmor_reporte`;

CREATE  VIEW `view_vigmor_reporte` AS select `view_vigmor_sexo`.`nombre_region` AS `nombre_region`,`view_vigmor_sexo`.`nombre_distrito` AS `nombre_distrito`,`view_vigmor_sexo`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_vigmor_sexo`.`id_evento` AS `id_evento`,`view_vigmor_sexo`.`nombre_evento` AS `nombre_evento`,`view_vigmor_sexo`.`id_un` AS `id_un`,`view_vigmor_sexo`.`nombre_unidad` AS `nombre_unidad`,`view_vigmor_sexo`.`cie_10` AS `cie_10`,`view_vigmor_sexo`.`id_gevento` AS `id_gevento`,`view_vigmor_sexo`.`semana` AS `semana`,`view_vigmor_sexo`.`anio` AS `anio`,`view_vigmor_sexo`.`per_id_pais` AS `per_id_pais`,`view_vigmor_sexo`.`per_id_corregimiento` AS `per_id_corregimiento`,`view_vigmor_sexo`.`id_unidad` AS `id_unidad`,`view_vigmor_sexo`.`sector_un` AS `sector_un`,`view_vigmor_sexo`.`id_corregimiento` AS `id_corregimiento`,`view_vigmor_sexo`.`id_distrito` AS `id_distrito`,`view_vigmor_sexo`.`id_region` AS `id_region`,sum(`view_vigmor_sexo`.`menor_unoM`) AS `M<1`,sum(`view_vigmor_sexo`.`menor_unoF`) AS `F<1`,sum(`view_vigmor_sexo`.`uno_cuatroM`) AS `M1-4`,sum(`view_vigmor_sexo`.`uno_cuatroF`) AS `F1-4`,sum(`view_vigmor_sexo`.`cinco_nueveM`) AS `M5-9`,sum(`view_vigmor_sexo`.`cinco_nueveF`) AS `F5-9`,sum(`view_vigmor_sexo`.`diez_catorceM`) AS `M10-14`,sum(`view_vigmor_sexo`.`diez_catorceF`) AS `F10-14`,sum(`view_vigmor_sexo`.`quince_diecinueveM`) AS `M15-19`,sum(`view_vigmor_sexo`.`quince_diecinueveF`) AS `F15-19`,sum(`view_vigmor_sexo`.`veinte_veinticuatroM`) AS `M20-24`,sum(`view_vigmor_sexo`.`veinte_veinticuatroF`) AS `F20-24`,sum(`view_vigmor_sexo`.`veinticinco_treinticuatroM`) AS `M25-34`,sum(`view_vigmor_sexo`.`veinticinco_treinticuatroF`) AS `F25-34`,sum(`view_vigmor_sexo`.`treinticinco_cuarentinueveM`) AS `M35-49`,sum(`view_vigmor_sexo`.`treinticinco_cuarentinueveF`) AS `F35-49`,sum(`view_vigmor_sexo`.`cincuenta_cincuentinueveM`) AS `M50-59`,sum(`view_vigmor_sexo`.`cincuenta_cincuentinueveF`) AS `F50-59`,sum(`view_vigmor_sexo`.`sesenta_sesenticuatroM`) AS `M60-64`,sum(`view_vigmor_sexo`.`sesenta_sesenticuatroF`) AS `F60-64`,sum(`view_vigmor_sexo`.`mayor_sesenticincoM`) AS `M>65`,sum(`view_vigmor_sexo`.`mayor_sesenticincoF`) AS `F>65`,sum(`view_vigmor_sexo`.`NEM`) AS `MNE`,sum(`view_vigmor_sexo`.`NEF`) AS `FNE` from `view_vigmor_sexo` group by `view_vigmor_sexo`.`cie_10`,`view_vigmor_sexo`.`semana`,`view_vigmor_sexo`.`anio`;

-- --------------------------------------------------------

--
-- Structure for view `view_vigmor_sexo`
--
DROP TABLE IF EXISTS `view_vigmor_sexo`;

CREATE  VIEW `view_vigmor_sexo` AS select `view_vigmor_rangos`.`nombre_region` AS `nombre_region`,`view_vigmor_rangos`.`nombre_distrito` AS `nombre_distrito`,`view_vigmor_rangos`.`nombre_corregimiento` AS `nombre_corregimiento`,`view_vigmor_rangos`.`id_evento` AS `id_evento`,`view_vigmor_rangos`.`nombre_evento` AS `nombre_evento`,`view_vigmor_rangos`.`id_unidad` AS `id_un`,`view_vigmor_rangos`.`nombre_unidad` AS `nombre_unidad`,`view_vigmor_rangos`.`cie_10` AS `cie_10`,`view_vigmor_rangos`.`id_gevento` AS `id_gevento`,`view_vigmor_rangos`.`semana` AS `semana`,`view_vigmor_rangos`.`anio` AS `anio`,`view_vigmor_rangos`.`per_id_pais` AS `per_id_pais`,`view_vigmor_rangos`.`per_id_corregimiento` AS `per_id_corregimiento`,`view_vigmor_rangos`.`id_unidad` AS `id_unidad`,`view_vigmor_rangos`.`sector_un` AS `sector_un`,`view_vigmor_rangos`.`id_corregimiento` AS `id_corregimiento`,`view_vigmor_rangos`.`id_distrito` AS `id_distrito`,`view_vigmor_rangos`.`id_region` AS `id_region`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`menor_uno`),_utf8'0') AS `menor_unoM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`menor_uno`),_utf8'0') AS `menor_unoF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`uno_cuatro`),_utf8'0') AS `uno_cuatroM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`uno_cuatro`),_utf8'0') AS `uno_cuatroF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`cinco_nueve`),_utf8'0') AS `cinco_nueveM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`cinco_nueve`),_utf8'0') AS `cinco_nueveF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`diez_catorce`),_utf8'0') AS `diez_catorceM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`diez_catorce`),_utf8'0') AS `diez_catorceF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`quince_diecinueve`),_utf8'0') AS `quince_diecinueveM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`quince_diecinueve`),_utf8'0') AS `quince_diecinueveF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`veinte_veinticuatro`),_utf8'0') AS `veinte_veinticuatroM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`veinte_veinticuatro`),_utf8'0') AS `veinte_veinticuatroF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`veinticinco_treinticuatro`),_utf8'0') AS `veinticinco_treinticuatroM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`veinticinco_treinticuatro`),_utf8'0') AS `veinticinco_treinticuatroF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`treinticinco_cuarentinueve`),_utf8'0') AS `treinticinco_cuarentinueveM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`treinticinco_cuarentinueve`),_utf8'0') AS `treinticinco_cuarentinueveF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`cincuenta_cincuentinueve`),_utf8'0') AS `cincuenta_cincuentinueveM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`cincuenta_cincuentinueve`),_utf8'0') AS `cincuenta_cincuentinueveF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`sesenta_sesenticuatro`),_utf8'0') AS `sesenta_sesenticuatroM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`sesenta_sesenticuatro`),_utf8'0') AS `sesenta_sesenticuatroF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`mayor_sesenticinco`),_utf8'0') AS `mayor_sesenticincoM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`mayor_sesenticinco`),_utf8'0') AS `mayor_sesenticincoF`,if((`view_vigmor_rangos`.`sexo` = _utf8'M'),sum(`view_vigmor_rangos`.`NE`),_utf8'0') AS `NEM`,if((`view_vigmor_rangos`.`sexo` = _utf8'F'),sum(`view_vigmor_rangos`.`NE`),_utf8'0') AS `NEF` from `view_vigmor_rangos` group by `view_vigmor_rangos`.`nombre_region`,`view_vigmor_rangos`.`nombre_distrito`,`view_vigmor_rangos`.`nombre_corregimiento`,`view_vigmor_rangos`.`nombre_evento`,`view_vigmor_rangos`.`semana`,`view_vigmor_rangos`.`anio`;

-- --------------------------------------------------------

--
-- Structure for view `view_vih_enfermedad_matriz`
--
DROP TABLE IF EXISTS `view_vih_enfermedad_matriz`;

CREATE  VIEW `view_vih_enfermedad_matriz` AS select `vih`.`anio` AS `anio`,`vih`.`semana_epi` AS `semana_epi`,`eve`.`id_evento` AS `id_evento`,`eve`.`nombre_evento` AS `nombre_evento`,`vih`.`cond_vih` AS `cond_vih`,`vih`.`cond_sida` AS `cond_sida`,`vih`.`cond_condicion_paciente` AS `cond_condicion_paciente`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 1) and (`vih`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 5) and (`vih`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 10) and (`vih`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 15) and (`vih`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 20) and (`vih`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 25) and (`vih`.`per_edad` <= 29)) then sum(1) else sum(0) end) AS `veinticinco_veintinueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 30) and (`vih`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `treinta_treitaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 35) and (`vih`.`per_edad` <= 39)) then sum(1) else sum(0) end) AS `treintaycinco_treintaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 40) and (`vih`.`per_edad` <= 44)) then sum(1) else sum(0) end) AS `cuarenta_cuarentaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 45) and (`vih`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `cuarentaycinco_cuarentaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 50) and (`vih`.`per_edad` <= 54)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 55) and (`vih`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuentaycinco_cincuentaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 60) and (`vih`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_m`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 1) and (`vih`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 5) and (`vih`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 10) and (`vih`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 15) and (`vih`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 20) and (`vih`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 25) and (`vih`.`per_edad` <= 29)) then sum(1) else sum(0) end) AS `veinticinco_veintinueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 30) and (`vih`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `treinta_treitaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 35) and (`vih`.`per_edad` <= 39)) then sum(1) else sum(0) end) AS `treintaycinco_treintaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 40) and (`vih`.`per_edad` <= 44)) then sum(1) else sum(0) end) AS `cuarenta_cuarentaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 45) and (`vih`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `cuarentaycinco_cuarentaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 50) and (`vih`.`per_edad` <= 54)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 55) and (`vih`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuentaycinco_cincuentaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 60) and (`vih`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_f` from (((`vih_enfermedad_oportunista` `enf` join `vih_form` `vih` on((`enf`.`id_vih_form` = `vih`.`id_vih_form`))) join `cat_evento` `eve` on((`eve`.`id_evento` = `enf`.`id_evento`))) join `tbl_persona` `per` on(((`per`.`numero_identificacion` = `vih`.`numero_identificacion`) and (`per`.`tipo_identificacion` = `vih`.`id_tipo_identidad`)))) group by `vih`.`anio`,`vih`.`semana_epi`,`enf`.`id_evento`,`vih`.`cond_vih`,`vih`.`cond_sida`;

-- --------------------------------------------------------

--
-- Structure for view `view_vih_factor_matriz`
--
DROP TABLE IF EXISTS `view_vih_factor_matriz`;

CREATE  VIEW `view_vih_factor_matriz` AS select `vih`.`anio` AS `anio`,`vih`.`semana_epi` AS `semana_epi`,`reg`.`id_region` AS `id_region`,`reg`.`nombre_region` AS `nombre_region`,`factor`.`id_factor` AS `id_factor`,`factor`.`factor_nombre` AS `factor_nombre`,`grupo`.`id_grupo_factor` AS `id_grupo_factor`,`grupo`.`grupo_factor_nombre` AS `grupo_factor_nombre`,`vih`.`cond_vih` AS `cond_vih`,`vih`.`cond_sida` AS `cond_sida`,`vih`.`cond_condicion_paciente` AS `cond_condicion_paciente`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 1) and (`vih`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 5) and (`vih`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 10) and (`vih`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 15) and (`vih`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 20) and (`vih`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 25) and (`vih`.`per_edad` <= 29)) then sum(1) else sum(0) end) AS `veinticinco_veintinueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 30) and (`vih`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `treinta_treitaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 35) and (`vih`.`per_edad` <= 39)) then sum(1) else sum(0) end) AS `treintaycinco_treintaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 40) and (`vih`.`per_edad` <= 44)) then sum(1) else sum(0) end) AS `cuarenta_cuarentaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 45) and (`vih`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `cuarentaycinco_cuarentaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 50) and (`vih`.`per_edad` <= 54)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 55) and (`vih`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuentaycinco_cincuentaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 60) and (`vih`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_m`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 1) and (`vih`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 5) and (`vih`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 10) and (`vih`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 15) and (`vih`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 20) and (`vih`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 25) and (`vih`.`per_edad` <= 29)) then sum(1) else sum(0) end) AS `veinticinco_veintinueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 30) and (`vih`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `treinta_treitaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 35) and (`vih`.`per_edad` <= 39)) then sum(1) else sum(0) end) AS `treintaycinco_treintaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 40) and (`vih`.`per_edad` <= 44)) then sum(1) else sum(0) end) AS `cuarenta_cuarentaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 45) and (`vih`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `cuarentaycinco_cuarentaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 50) and (`vih`.`per_edad` <= 54)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 55) and (`vih`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuentaycinco_cincuentaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 60) and (`vih`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_f` from ((((((`vih_factor_riesgo` `riesgo` join `vih_form` `vih` on((`riesgo`.`id_vih_form` = `vih`.`id_vih_form`))) join `cat_factor_riesgo` `factor` on((`factor`.`id_factor` = `riesgo`.`id_factor`))) join `cat_grupo_factor_riesgo` `grupo` on((`grupo`.`id_grupo_factor` = `riesgo`.`id_grupo_factor`))) join `tbl_persona` `per` on(((`per`.`numero_identificacion` = `vih`.`numero_identificacion`) and (`per`.`tipo_identificacion` = `vih`.`id_tipo_identidad`)))) left join `cat_unidad_notificadora` `un` on((`vih`.`id_un` = `un`.`id_un`))) left join `cat_region_salud` `reg` on((`un`.`id_region` = `reg`.`id_region`))) group by `vih`.`anio`,`vih`.`semana_epi`,`reg`.`id_region`,`factor`.`id_factor`,`grupo`.`id_grupo_factor`,`vih`.`cond_vih`,`vih`.`cond_sida`;

-- --------------------------------------------------------

--
-- Structure for view `view_vih_factor_reporte`
--
DROP TABLE IF EXISTS `view_vih_factor_reporte`;

CREATE  VIEW `view_vih_factor_reporte` AS select `matriz`.`anio` AS `anio`,`matriz`.`semana_epi` AS `semana_epi`,`matriz`.`cond_condicion_paciente` AS `cond_condicion_paciente`,`matriz`.`id_region` AS `id_region`,`matriz`.`nombre_region` AS `nombre_region`,`matriz`.`id_grupo_factor` AS `id_grupo_factor`,`matriz`.`grupo_factor_nombre` AS `grupo_factor_nombre`,`matriz`.`id_factor` AS `id_factor`,`matriz`.`factor_nombre` AS `factor_nombre`,`matriz`.`cond_vih` AS `cond_vih`,`matriz`.`cond_sida` AS `cond_sida`,sum(`matriz`.`menor_uno_m`) AS `menor_uno_m`,sum(`matriz`.`uno_cuatro_m`) AS `uno_cuatro_m`,sum(`matriz`.`cinco_nueve_m`) AS `cinco_nueve_m`,sum(`matriz`.`diez_catorce_m`) AS `diez_catorce_m`,sum(`matriz`.`quince_diecinueve_m`) AS `quince_diecinueve_m`,sum(`matriz`.`veinte_veinticuatro_m`) AS `veinte_veinticuatro_m`,sum(`matriz`.`veinticinco_veintinueve_m`) AS `veinticinco_veintinueve_m`,sum(`matriz`.`treinta_treitaycuatro_m`) AS `treinta_treitaycuatro_m`,sum(`matriz`.`treintaycinco_treintaynueve_m`) AS `treintaycinco_treintaynueve_m`,sum(`matriz`.`cuarenta_cuarentaycuatro_m`) AS `cuarenta_cuarentaycuatro_m`,sum(`matriz`.`cuarentaycinco_cuarentaynueve_m`) AS `cuarentaycinco_cuarentaynueve_m`,sum(`matriz`.`cincuenta_cincuentaycuatro_m`) AS `cincuenta_cincuentaycuatro_m`,sum(`matriz`.`cincuentaycinco_cincuentaynueve_m`) AS `cincuentaycinco_cincuentaynueve_m`,sum(`matriz`.`sesenta_sesentaycuantro_m`) AS `sesenta_sesentaycuantro_m`,sum(`matriz`.`mas_sesentaycinco_m`) AS `mas_sesentaycinco_m`,sum(`matriz`.`menor_uno_f`) AS `menor_uno_f`,sum(`matriz`.`uno_cuatro_f`) AS `uno_cuatro_f`,sum(`matriz`.`cinco_nueve_f`) AS `cinco_nueve_f`,sum(`matriz`.`diez_catorce_f`) AS `diez_catorce_f`,sum(`matriz`.`quince_diecinueve_f`) AS `quince_diecinueve_f`,sum(`matriz`.`veinte_veinticuatro_f`) AS `veinte_veinticuatro_f`,sum(`matriz`.`veinticinco_veintinueve_f`) AS `veinticinco_veintinueve_f`,sum(`matriz`.`treinta_treitaycuatro_f`) AS `treinta_treitaycuatro_f`,sum(`matriz`.`treintaycinco_treintaynueve_f`) AS `treintaycinco_treintaynueve_f`,sum(`matriz`.`cuarenta_cuarentaycuatro_f`) AS `cuarenta_cuarentaycuatro_f`,sum(`matriz`.`cuarentaycinco_cuarentaynueve_f`) AS `cuarentaycinco_cuarentaynueve_f`,sum(`matriz`.`cincuenta_cincuentaycuatro_f`) AS `cincuenta_cincuentaycuatro_f`,sum(`matriz`.`cincuentaycinco_cincuentaynueve_f`) AS `cincuentaycinco_cincuentaynueve_f`,sum(`matriz`.`sesenta_sesentaycuantro_f`) AS `sesenta_sesentaycuantro_f`,sum(`matriz`.`mas_sesentaycinco_f`) AS `mas_sesentaycinco_f` from `view_vih_factor_matriz` `matriz` group by `matriz`.`anio`,`matriz`.`semana_epi`,`matriz`.`id_region`,`matriz`.`id_grupo_factor`,`matriz`.`id_factor`;

-- --------------------------------------------------------

--
-- Structure for view `view_vih_matriz`
--
DROP TABLE IF EXISTS `view_vih_matriz`;

CREATE  VIEW `view_vih_matriz` AS select `p`.`id_provincia` AS `id_provincia`,`p`.`nombre_provincia` AS `nombre_provincia`,`r`.`id_region` AS `id_region`,`r`.`nombre_region` AS `nombre_region`,`d`.`id_distrito` AS `id_distrito`,`d`.`nombre_distrito` AS `nombre_distrito`,`c`.`id_corregimiento` AS `id_corregimiento`,`c`.`nombre_corregimiento` AS `nombre_corregimiento`,`vih`.`id_un` AS `id_un`,`cun`.`nombre_un` AS `nombre_un`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 1) and (`vih`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 5) and (`vih`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 10) and (`vih`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 15) and (`vih`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 20) and (`vih`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 25) and (`vih`.`per_edad` <= 29)) then sum(1) else sum(0) end) AS `veinticinco_veintinueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 30) and (`vih`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `treinta_treitaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 35) and (`vih`.`per_edad` <= 39)) then sum(1) else sum(0) end) AS `treintaycinco_treintaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 40) and (`vih`.`per_edad` <= 44)) then sum(1) else sum(0) end) AS `cuarenta_cuarentaycuatro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 45) and (`vih`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `cuarentaycinco_cuarentaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 50) and (`vih`.`per_edad` <= 54)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaycinco_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 55) and (`vih`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuentaycinco_cincuentaynueve_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 60) and (`vih`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_m`,(case when ((`per`.`sexo` = 'M') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_m`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` <= 2)) then sum(1) else sum(0) end) AS `menor_uno_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 1) and (`vih`.`per_edad` <= 4)) then sum(1) else sum(0) end) AS `uno_cuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 5) and (`vih`.`per_edad` <= 9)) then sum(1) else sum(0) end) AS `cinco_nueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 10) and (`vih`.`per_edad` <= 14)) then sum(1) else sum(0) end) AS `diez_catorce_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 15) and (`vih`.`per_edad` <= 19)) then sum(1) else sum(0) end) AS `quince_diecinueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 20) and (`vih`.`per_edad` <= 24)) then sum(1) else sum(0) end) AS `veinte_veinticuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 25) and (`vih`.`per_edad` <= 29)) then sum(1) else sum(0) end) AS `veinticinco_veintinueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 30) and (`vih`.`per_edad` <= 34)) then sum(1) else sum(0) end) AS `treinta_treitaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 35) and (`vih`.`per_edad` <= 39)) then sum(1) else sum(0) end) AS `treintaycinco_treintaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 40) and (`vih`.`per_edad` <= 44)) then sum(1) else sum(0) end) AS `cuarenta_cuarentaycuatro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 45) and (`vih`.`per_edad` <= 49)) then sum(1) else sum(0) end) AS `cuarentaycinco_cuarentaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 50) and (`vih`.`per_edad` <= 54)) then sum(1) else sum(0) end) AS `cincuenta_cincuentaycinco_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 55) and (`vih`.`per_edad` <= 59)) then sum(1) else sum(0) end) AS `cincuentaycinco_cincuentaynueve_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 60) and (`vih`.`per_edad` <= 64)) then sum(1) else sum(0) end) AS `sesenta_sesentaycuantro_f`,(case when ((`per`.`sexo` = 'F') and (`vih`.`per_tipo_edad` > 2) and (`vih`.`per_edad` >= 65)) then sum(1) else sum(0) end) AS `mas_sesentaycinco_f`,`vih`.`cond_vih` AS `cond_vih`,`vih`.`cond_sida` AS `cond_sida`,date_format(`vih`.`fecha_notificacion`,'%d-%m-%Y') AS `fecha_notificacion`,`vih`.`anio` AS `anio`,`vih`.`semana_epi` AS `semana_epi`,`vih`.`cond_condicion_paciente` AS `cond_condicion_paciente` from ((((((`vih_form` `vih` left join `cat_unidad_notificadora` `cun` on((`vih`.`id_un` = `cun`.`id_un`))) left join `cat_corregimiento` `c` on((`cun`.`id_corregimiento` = `c`.`id_corregimiento`))) left join `cat_distrito` `d` on((`c`.`id_distrito` = `d`.`id_distrito`))) left join `cat_region_salud` `r` on((`d`.`id_region` = `r`.`id_region`))) left join `cat_provincia` `p` on((`d`.`id_provincia` = `p`.`id_provincia`))) join `tbl_persona` `per` on(((`vih`.`id_tipo_identidad` = `per`.`tipo_identificacion`) and (`vih`.`numero_identificacion` = `per`.`numero_identificacion`)))) group by `p`.`id_provincia`,`r`.`id_region`,`d`.`id_distrito`,`c`.`id_corregimiento`,`cun`.`id_un`,`per`.`sexo`,`vih`.`per_edad`;

-- --------------------------------------------------------

--
-- Structure for view `view_vih_regiones`
--
DROP TABLE IF EXISTS `view_vih_regiones`;

CREATE  VIEW `view_vih_regiones` AS select (case when ((`r`.`id_region` = 1) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m1`,(case when ((`r`.`id_region` = 2) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m2`,(case when ((`r`.`id_region` = 3) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m3`,(case when ((`r`.`id_region` = 4) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m4`,(case when ((`r`.`id_region` = 5) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m5`,(case when ((`r`.`id_region` = 6) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m6`,(case when ((`r`.`id_region` = 7) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m7`,(case when ((`r`.`id_region` = 8) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m8`,(case when ((`r`.`id_region` = 9) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m9`,(case when ((`r`.`id_region` = 10) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m10`,(case when ((`r`.`id_region` = 11) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m11`,(case when ((`r`.`id_region` = 12) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m12`,(case when ((`r`.`id_region` = 13) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m13`,(case when ((`r`.`id_region` = 14) and (`per`.`sexo` = 'M')) then sum(1) else sum(0) end) AS `region_m14`,(case when ((`r`.`id_region` = 1) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f1`,(case when ((`r`.`id_region` = 2) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f2`,(case when ((`r`.`id_region` = 3) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f3`,(case when ((`r`.`id_region` = 4) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f4`,(case when ((`r`.`id_region` = 5) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f5`,(case when ((`r`.`id_region` = 6) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f6`,(case when ((`r`.`id_region` = 7) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f7`,(case when ((`r`.`id_region` = 8) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f8`,(case when ((`r`.`id_region` = 9) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f9`,(case when ((`r`.`id_region` = 10) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f10`,(case when ((`r`.`id_region` = 11) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f11`,(case when ((`r`.`id_region` = 12) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f12`,(case when ((`r`.`id_region` = 13) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f13`,(case when ((`r`.`id_region` = 14) and (`per`.`sexo` = 'F')) then sum(1) else sum(0) end) AS `region_f14`,`vih`.`cond_vih` AS `cond_vih`,`vih`.`cond_sida` AS `cond_sida`,date_format(`vih`.`fecha_notificacion`,'%d-%m-%Y') AS `fecha_notificacion`,`vih`.`anio` AS `anio`,`vih`.`semana_epi` AS `semana_epi`,`vih`.`cond_condicion_paciente` AS `cond_condicion_paciente` from (((((`vih_form` `vih` left join `cat_unidad_notificadora` `cun` on((`vih`.`id_un` = `cun`.`id_un`))) left join `cat_corregimiento` `c` on((`cun`.`id_corregimiento` = `c`.`id_corregimiento`))) left join `cat_distrito` `d` on((`c`.`id_distrito` = `d`.`id_distrito`))) left join `cat_region_salud` `r` on((`d`.`id_region` = `r`.`id_region`))) join `tbl_persona` `per` on(((`vih`.`id_tipo_identidad` = `per`.`tipo_identificacion`) and (`vih`.`numero_identificacion` = `per`.`numero_identificacion`)))) group by `vih`.`anio`,`vih`.`semana_epi`,`r`.`id_region`,`d`.`id_distrito`,`c`.`id_corregimiento`,`cun`.`id_un`,`per`.`sexo`,`vih`.`per_edad`,`per`.`numero_identificacion`;

-- --------------------------------------------------------

--
-- Structure for view `view_vih_reporte`
--
DROP TABLE IF EXISTS `view_vih_reporte`;

CREATE  VIEW `view_vih_reporte` AS select `matriz`.`id_provincia` AS `id_provincia`,`matriz`.`nombre_provincia` AS `nombre_provincia`,`matriz`.`id_region` AS `id_region`,`matriz`.`nombre_region` AS `nombre_region`,`matriz`.`id_distrito` AS `id_distrito`,`matriz`.`nombre_distrito` AS `nombre_distrito`,`matriz`.`id_corregimiento` AS `id_corregimiento`,`matriz`.`nombre_corregimiento` AS `nombre_corregimiento`,`matriz`.`id_un` AS `id_un`,`matriz`.`nombre_un` AS `nombre_un`,sum(`matriz`.`menor_uno_m`) AS `menor_uno_m`,sum(`matriz`.`uno_cuatro_m`) AS `uno_cuatro_m`,sum(`matriz`.`cinco_nueve_m`) AS `cinco_nueve_m`,sum(`matriz`.`diez_catorce_m`) AS `diez_catorce_m`,sum(`matriz`.`quince_diecinueve_m`) AS `quince_diecinueve_m`,sum(`matriz`.`veinte_veinticuatro_m`) AS `veinte_veinticuatro_m`,sum(`matriz`.`veinticinco_veintinueve_m`) AS `veinticinco_veintinueve_m`,sum(`matriz`.`treinta_treitaycuatro_m`) AS `treinta_treitaycuatro_m`,sum(`matriz`.`treintaycinco_treintaynueve_m`) AS `treintaycinco_treintaynueve_m`,sum(`matriz`.`cuarenta_cuarentaycuatro_m`) AS `cuarenta_cuarentaycuatro_m`,sum(`matriz`.`cuarentaycinco_cuarentaynueve_m`) AS `cuarentaycinco_cuarentaynueve_m`,sum(`matriz`.`cincuenta_cincuentaycinco_m`) AS `cincuenta_cincuentaycinco_m`,sum(`matriz`.`cincuentaycinco_cincuentaynueve_m`) AS `cincuentaycinco_cincuentaynueve_m`,sum(`matriz`.`sesenta_sesentaycuantro_m`) AS `sesenta_sesentaycuantro_m`,sum(`matriz`.`mas_sesentaycinco_m`) AS `mas_sesentaycinco_m`,sum(`matriz`.`menor_uno_f`) AS `menor_uno_f`,sum(`matriz`.`uno_cuatro_f`) AS `uno_cuatro_f`,sum(`matriz`.`cinco_nueve_f`) AS `cinco_nueve_f`,sum(`matriz`.`diez_catorce_f`) AS `diez_catorce_f`,sum(`matriz`.`quince_diecinueve_f`) AS `quince_diecinueve_f`,sum(`matriz`.`veinte_veinticuatro_f`) AS `veinte_veinticuatro_f`,sum(`matriz`.`veinticinco_veintinueve_f`) AS `veinticinco_veintinueve_f`,sum(`matriz`.`treinta_treitaycuatro_f`) AS `treinta_treitaycuatro_f`,sum(`matriz`.`treintaycinco_treintaynueve_f`) AS `treintaycinco_treintaynueve_f`,sum(`matriz`.`cuarenta_cuarentaycuatro_f`) AS `cuarenta_cuarentaycuatro_f`,sum(`matriz`.`cuarentaycinco_cuarentaynueve_f`) AS `cuarentaycinco_cuarentaynueve_f`,sum(`matriz`.`cincuenta_cincuentaycinco_f`) AS `cincuenta_cincuentaycinco_f`,sum(`matriz`.`cincuentaycinco_cincuentaynueve_f`) AS `cincuentaycinco_cincuentaynueve_f`,sum(`matriz`.`sesenta_sesentaycuantro_f`) AS `sesenta_sesentaycuantro_f`,sum(`matriz`.`mas_sesentaycinco_f`) AS `mas_sesentaycinco_f`,sum(`matriz`.`cond_vih`) AS `cond_vih`,sum(`matriz`.`cond_sida`) AS `cond_sida`,`matriz`.`fecha_notificacion` AS `fecha_admision`,`matriz`.`anio` AS `anio`,`matriz`.`semana_epi` AS `semana_epi`,`matriz`.`cond_condicion_paciente` AS `cond_condicion_paciente` from `view_vih_matriz` `matriz` group by `matriz`.`anio`,`matriz`.`semana_epi`,`matriz`.`id_provincia`,`matriz`.`id_region`,`matriz`.`id_distrito`,`matriz`.`id_corregimiento`,`matriz`.`id_un`,`matriz`.`cond_condicion_paciente`;

-- --------------------------------------------------------

--
-- Structure for view `vw_malformacion_casos_diagnostico`
--
DROP TABLE IF EXISTS `vw_malformacion_casos_diagnostico`;

CREATE  VIEW `vw_malformacion_casos_diagnostico` AS select `f`.`fecha_reporte` AS `fecha_reporte`,`f`.`id_un` AS `id_un`,count(`f`.`id_mal`) AS `casos` from (`mal_evento` `me` join `mal_form` `f` on((`me`.`id_mal` = `f`.`id_mal`))) where ((`f`.`bebe_peso_nacimiento` >= 500) and (`f`.`bebe_edad_gestacional` >= 22) and ((((year(curdate()) - year(`f`.`per_fecha_empezo_vivir`)) - (right(curdate(),5) < right(`f`.`per_fecha_empezo_vivir`,5))) >= 1) or isnull(`f`.`per_fecha_empezo_vivir`)) and ((`f`.`fecha_reporte` - interval 7 day) <= `f`.`bebe_fecha_nacimiento`)) group by `f`.`id_mal`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cat_corregimiento`
--
ALTER TABLE `cat_corregimiento`
  ADD CONSTRAINT `fk_distrito` FOREIGN KEY (`id_distrito`) REFERENCES `cat_distrito` (`id_distrito`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cat_diag_etiologico`
--
ALTER TABLE `cat_diag_etiologico`
  ADD CONSTRAINT `fk_diag_sindromico_etiologico` FOREIGN KEY (`id_diag_sindromico`) REFERENCES `cat_diag_sindromico` (`id_diag_sindromico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cat_distrito`
--
ALTER TABLE `cat_distrito`
  ADD CONSTRAINT `fk_provincia` FOREIGN KEY (`id_provincia`) REFERENCES `cat_provincia` (`id_provincia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_region_distrito` FOREIGN KEY (`id_region`) REFERENCES `cat_region_salud` (`id_region`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cat_evento`
--
ALTER TABLE `cat_evento`
  ADD CONSTRAINT `fk_gevento` FOREIGN KEY (`id_gevento`) REFERENCES `cat_grupo_evento` (`id_gevento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cat_factor_riesgo`
--
ALTER TABLE `cat_factor_riesgo`
  ADD CONSTRAINT `fk_factor_riego_grupo_factor_riesgo` FOREIGN KEY (`id_grupo_factor`) REFERENCES `cat_grupo_factor_riesgo` (`id_grupo_factor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cat_unidad_notificadora`
--
ALTER TABLE `cat_unidad_notificadora`
  ADD CONSTRAINT `fk_corregimiento` FOREIGN KEY (`id_corregimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_region` FOREIGN KEY (`id_region`) REFERENCES `cat_region_salud` (`id_region`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tipo_unidad` FOREIGN KEY (`idtipo_instalacion`) REFERENCES `tbl_tipo_instalacion` (`idtipo_instalacion`);

--
-- Constraints for table `denominador_detalle`
--
ALTER TABLE `denominador_detalle`
  ADD CONSTRAINT `fk_denominador_denominador_detalle_iddenominador` FOREIGN KEY (`id_denominador`) REFERENCES `denominador` (`id_denominador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eno_detalle`
--
ALTER TABLE `eno_detalle`
  ADD CONSTRAINT `fk_cat_rango` FOREIGN KEY (`id_rango`) REFERENCES `cat_rango` (`id_rango`),
  ADD CONSTRAINT `fk_encabezado_detalle` FOREIGN KEY (`id_enc`) REFERENCES `eno_encabezado` (`id_enc`),
  ADD CONSTRAINT `fk_eno_evento` FOREIGN KEY (`id_evento`) REFERENCES `cat_evento` (`id_evento`);

--
-- Constraints for table `eno_encabezado`
--
ALTER TABLE `eno_encabezado`
  ADD CONSTRAINT `fk_eno_enc_servicio` FOREIGN KEY (`id_servicio`) REFERENCES `cat_servicio` (`id_servicio`),
  ADD CONSTRAINT `fk_un` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `flureg_antecendente_vacunal`
--
ALTER TABLE `flureg_antecendente_vacunal`
  ADD CONSTRAINT `fk_flureg_antecendente_vacunal_cat_antecendente_vacunal` FOREIGN KEY (`id_cat_antecendente_vacunal`) REFERENCES `cat_antecendente_vacunal` (`id_cat_antecendente_vacunal`),
  ADD CONSTRAINT `fk_flureg_antecendente_vacunal_tbl_persona` FOREIGN KEY (`tipo_identificacion`, `numero_identificacion`) REFERENCES `tbl_persona` (`tipo_identificacion`, `numero_identificacion`);

--
-- Constraints for table `flureg_enfermedad_cronica`
--
ALTER TABLE `flureg_enfermedad_cronica`
  ADD CONSTRAINT `fk_flureg_enfermedad_cronica_cat_enfermedad_cronica` FOREIGN KEY (`id_cat_enfermedad_cronica`) REFERENCES `cat_enfermedad_cronica` (`id_cat_enfermedad_cronica`),
  ADD CONSTRAINT `fk_flureg_enfermedad_cronica_tbl_persona` FOREIGN KEY (`tipo_identificacion`, `numero_identificacion`) REFERENCES `tbl_persona` (`tipo_identificacion`, `numero_identificacion`);

--
-- Constraints for table `mal_evento`
--
ALTER TABLE `mal_evento`
  ADD CONSTRAINT `fk_mal_evento_evento` FOREIGN KEY (`id_evento`) REFERENCES `cat_evento` (`id_evento`),
  ADD CONSTRAINT `mal_evento_ibfk_1` FOREIGN KEY (`id_mal`) REFERENCES `mal_form` (`id_mal`) ON UPDATE CASCADE;

--
-- Constraints for table `mal_form`
--
ALTER TABLE `mal_form`
  ADD CONSTRAINT `fk_mal_embarazo_multiple` FOREIGN KEY (`ant_id_embarazo_multiples`) REFERENCES `cat_embarazo_multiple` (`id_embarazo_multiple`),
  ADD CONSTRAINT `fk_mal_enfermedad_madre` FOREIGN KEY (`ant_id_enfermedad_madre`) REFERENCES `cat_enfermedad_madre` (`id_enfermedad_madre`),
  ADD CONSTRAINT `fk_mal_estudio` FOREIGN KEY (`per_id_estudio`) REFERENCES `cat_estudio` (`id_estudio`),
  ADD CONSTRAINT `fk_mal_etnia` FOREIGN KEY (`per_id_etnia`) REFERENCES `cat_etnia` (`id_etnia`),
  ADD CONSTRAINT `fk_mal_infeccion_madre` FOREIGN KEY (`ant_id_infeccion_madre`) REFERENCES `cat_infeccion_madre` (`id_infeccion_madre`),
  ADD CONSTRAINT `fk_mal_persona` FOREIGN KEY (`tipo_identificacion`, `numero_identificacion`) REFERENCES `tbl_persona` (`tipo_identificacion`, `numero_identificacion`),
  ADD CONSTRAINT `fk_mal_persona_corr` FOREIGN KEY (`per_id_corregimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`),
  ADD CONSTRAINT `fk_mal_persona_pais` FOREIGN KEY (`per_id_pais`) REFERENCES `cat_pais` (`id_pais`),
  ADD CONSTRAINT `fk_mal_servicio` FOREIGN KEY (`id_servicio`) REFERENCES `cat_servicio_rae` (`id_servicio`),
  ADD CONSTRAINT `fk_mal_un` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`);

--
-- Constraints for table `mal_nacidos`
--
ALTER TABLE `mal_nacidos`
  ADD CONSTRAINT `fk_mal_nacidos_instalacion` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`);

--
-- Constraints for table `mat_contacto_grupo_contacto`
--
ALTER TABLE `mat_contacto_grupo_contacto`
  ADD CONSTRAINT `fk_mat_rel_contacto` FOREIGN KEY (`id_contacto`) REFERENCES `mat_contacto` (`id_contacto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_mat_rel_grupo_contacto` FOREIGN KEY (`id_grupo_contacto`) REFERENCES `mat_grupo_contacto` (`id_grupo_contacto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notic_form`
--
ALTER TABLE `notic_form`
  ADD CONSTRAINT `fk_notic_evento` FOREIGN KEY (`id_diagnostico1`) REFERENCES `cat_evento` (`id_evento`),
  ADD CONSTRAINT `fk_notic_persona` FOREIGN KEY (`tipo_identificacion`, `numero_identificacion`) REFERENCES `tbl_persona` (`tipo_identificacion`, `numero_identificacion`),
  ADD CONSTRAINT `fk_notic_persona_pais` FOREIGN KEY (`per_id_pais`) REFERENCES `cat_pais` (`id_pais`),
  ADD CONSTRAINT `fk_notic_un` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`);

--
-- Constraints for table `notic_sintoma`
--
ALTER TABLE `notic_sintoma`
  ADD CONSTRAINT `fk_rel_sintoma` FOREIGN KEY (`id_sintoma`) REFERENCES `cat_sintoma` (`id_sintoma`),
  ADD CONSTRAINT `notic_sintoma_ibfk_1` FOREIGN KEY (`id_notic`) REFERENCES `notic_form` (`id_notic`) ON UPDATE CASCADE;

--
-- Constraints for table `rae_egreso`
--
ALTER TABLE `rae_egreso`
  ADD CONSTRAINT `rae_egreso_ibfk_1` FOREIGN KEY (`id_rae`) REFERENCES `rae_form` (`id_rae`) ON UPDATE CASCADE;

--
-- Constraints for table `rae_form`
--
ALTER TABLE `rae_form`
  ADD CONSTRAINT `fk_rae_condicion_salida` FOREIGN KEY (`id_condicion_salida`) REFERENCES `cat_condicion_salida` (`id_condicion_salida`),
  ADD CONSTRAINT `fk_rae_evento` FOREIGN KEY (`id_diagnostico1`) REFERENCES `cat_evento` (`id_evento`),
  ADD CONSTRAINT `fk_rae_persona` FOREIGN KEY (`tipo_identificacion`, `numero_identificacion`) REFERENCES `tbl_persona` (`tipo_identificacion`, `numero_identificacion`),
  ADD CONSTRAINT `fk_rae_personal_medico` FOREIGN KEY (`id_personal_medico`) REFERENCES `tbl_personal_medico` (`id_personal_medico`),
  ADD CONSTRAINT `fk_rae_persona_corr` FOREIGN KEY (`per_id_corregimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`),
  ADD CONSTRAINT `fk_rae_persona_corr_transitoria` FOREIGN KEY (`per_id_corregimiento_transitoria`) REFERENCES `cat_corregimiento` (`id_corregimiento`),
  ADD CONSTRAINT `fk_rae_persona_pais` FOREIGN KEY (`per_id_pais`) REFERENCES `cat_pais` (`id_pais`),
  ADD CONSTRAINT `fk_rae_servicio` FOREIGN KEY (`id_servicio`) REFERENCES `cat_servicio_rae` (`id_servicio`),
  ADD CONSTRAINT `fk_rae_tipo_paciente` FOREIGN KEY (`id_tipo_paciente`) REFERENCES `cat_tipo_paciente` (`id_tipo_paciente`),
  ADD CONSTRAINT `fk_rae_un` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`);

--
-- Constraints for table `rae_movimiento`
--
ALTER TABLE `rae_movimiento`
  ADD CONSTRAINT `fk_rae_movimiento_servicio` FOREIGN KEY (`id_servicio`) REFERENCES `cat_servicio_rae` (`id_servicio`),
  ADD CONSTRAINT `rae_movimiento_ibfk_1` FOREIGN KEY (`id_rae`) REFERENCES `rae_form` (`id_rae`) ON UPDATE CASCADE;

--
-- Constraints for table `rae_procedimiento`
--
ALTER TABLE `rae_procedimiento`
  ADD CONSTRAINT `fk_rae_procedimiento_procedimiento` FOREIGN KEY (`id_procedimiento`) REFERENCES `cat_procedimiento` (`id_procedimiento`),
  ADD CONSTRAINT `rae_procedimiento_ibfk_1` FOREIGN KEY (`id_rae`) REFERENCES `rae_form` (`id_rae`) ON UPDATE CASCADE;

--
-- Constraints for table `rel_examen_tipo_muestra`
--
ALTER TABLE `rel_examen_tipo_muestra`
  ADD CONSTRAINT `fk_rel_examen` FOREIGN KEY (`id_examen`) REFERENCES `cat_examen` (`id_examen`),
  ADD CONSTRAINT `fk_rel_tipo_muestra` FOREIGN KEY (`id_tipo_muestra`) REFERENCES `cat_tipo_muestra` (`id_tipo_muestra`);

--
-- Constraints for table `tbl_persona`
--
ALTER TABLE `tbl_persona`
  ADD CONSTRAINT `fk_persona_corregimiento0` FOREIGN KEY (`id_corregimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`),
  ADD CONSTRAINT `fk_persona_etnia` FOREIGN KEY (`id_etnia`) REFERENCES `cat_etnia` (`id_etnia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_persona_genero` FOREIGN KEY (`id_genero`) REFERENCES `cat_genero` (`id_genero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_persona_pais` FOREIGN KEY (`id_pais`) REFERENCES `cat_pais` (`id_pais`),
  ADD CONSTRAINT `fk_persona_tipo_edad` FOREIGN KEY (`tipo_edad`) REFERENCES `cat_edad` (`tipo_edad`),
  ADD CONSTRAINT `fk_persona_tipo_identificacion0` FOREIGN KEY (`tipo_identificacion`) REFERENCES `cat_tipo_identidad` (`id_tipo_identidad`),
  ADD CONSTRAINT `fk_per_nacimiento_corregimiento` FOREIGN KEY (`id_corregimiento_nacimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`),
  ADD CONSTRAINT `fk_per_nacimiento_pais` FOREIGN KEY (`id_pais_nacimiento`) REFERENCES `cat_pais` (`id_pais`);

--
-- Constraints for table `tbl_personal_medico`
--
ALTER TABLE `tbl_personal_medico`
  ADD CONSTRAINT `fk_personal_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cat_cargo` (`id_cargo`);

--
-- Constraints for table `tbl_tipo_instalacion`
--
ALTER TABLE `tbl_tipo_instalacion`
  ADD CONSTRAINT `FK_tipo_instalacion_nivel` FOREIGN KEY (`idnivel_instalacion`) REFERENCES `tbl_nivel_instalacion` (`idnivel_instalacion`);

--
-- Constraints for table `vicits_antibiotico`
--
ALTER TABLE `vicits_antibiotico`
  ADD CONSTRAINT `fk_vicits_antibiotico_form` FOREIGN KEY (`id_vicits_form`) REFERENCES `vicits_form` (`id_vicits_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_droga`
--
ALTER TABLE `vicits_droga`
  ADD CONSTRAINT `fk_vicits_droga` FOREIGN KEY (`id_droga`) REFERENCES `cat_droga` (`id_droga`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_droga_form` FOREIGN KEY (`id_vicits_form`) REFERENCES `vicits_form` (`id_vicits_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_form`
--
ALTER TABLE `vicits_form`
  ADD CONSTRAINT `fk_vicits_corregimiento` FOREIGN KEY (`per_id_corregimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_pais` FOREIGN KEY (`per_id_pais`) REFERENCES `cat_pais` (`id_pais`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_tipo_identidad` FOREIGN KEY (`id_tipo_identidad`) REFERENCES `cat_tipo_identidad` (`id_tipo_identidad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_un` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_its`
--
ALTER TABLE `vicits_its`
  ADD CONSTRAINT `fk_vicits_its` FOREIGN KEY (`id_ITS`) REFERENCES `cat_ITS` (`id_ITS`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_its_form` FOREIGN KEY (`id_vicits_form`) REFERENCES `vicits_form` (`id_vicits_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_lab_muestra`
--
ALTER TABLE `vicits_lab_muestra`
  ADD CONSTRAINT `fk_vicits_lab_muestra` FOREIGN KEY (`id_tipos_muestras`) REFERENCES `cat_tipos_muestras` (`id_tipos_muestras`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_lab_muestra_formulario` FOREIGN KEY (`id_vicits_laboratorio`) REFERENCES `vicits_laboratorio` (`id_vicits_laboratorio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_lab_prueba`
--
ALTER TABLE `vicits_lab_prueba`
  ADD CONSTRAINT `fk_vicits_lab_prueba` FOREIGN KEY (`id_prueba`) REFERENCES `cat_prueba` (`id_prueba`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_lab_prueba_formulario` FOREIGN KEY (`id_vicits_laboratorio`) REFERENCES `vicits_laboratorio` (`id_vicits_laboratorio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_laboratorio`
--
ALTER TABLE `vicits_laboratorio`
  ADD CONSTRAINT `fk_vicits_lab_grupo_poblacion` FOREIGN KEY (`id_grupo_poblacion`) REFERENCES `cat_grupo_poblacion` (`id_grupo_poblacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_lab_un` FOREIGN KEY (`id_un`) REFERENCES `cat_unidad_notificadora` (`id_un`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_sintoma`
--
ALTER TABLE `vicits_sintoma`
  ADD CONSTRAINT `fk_vicits_sintoma` FOREIGN KEY (`id_signo_sintoma`) REFERENCES `cat_signo_sintoma` (`id_signo_sintoma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_sintoma_form` FOREIGN KEY (`id_vicits_form`) REFERENCES `vicits_form` (`id_vicits_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vicits_tratamiento`
--
ALTER TABLE `vicits_tratamiento`
  ADD CONSTRAINT `fk_vicits_diag_etiologico` FOREIGN KEY (`id_diag_etiologico`) REFERENCES `cat_diag_etiologico` (`id_diag_etiologico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_diag_sindromico` FOREIGN KEY (`id_diag_sindromico`) REFERENCES `cat_diag_sindromico` (`id_diag_sindromico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_tratamiento` FOREIGN KEY (`id_tratamiento`) REFERENCES `cat_tratamiento` (`id_tratamiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vicits_tratamiento_form` FOREIGN KEY (`id_vicits_form`) REFERENCES `vicits_form` (`id_vicits_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vih_enfermedad_oportunista`
--
ALTER TABLE `vih_enfermedad_oportunista`
  ADD CONSTRAINT `fk_vih_enfemerdad_form` FOREIGN KEY (`id_vih_form`) REFERENCES `vih_form` (`id_vih_form`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vih_evento` FOREIGN KEY (`id_evento`) REFERENCES `cat_evento` (`id_evento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vih_factor_riesgo`
--
ALTER TABLE `vih_factor_riesgo`
  ADD CONSTRAINT `fk_vih_factor` FOREIGN KEY (`id_factor`) REFERENCES `cat_factor_riesgo` (`id_factor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vih_form` FOREIGN KEY (`id_vih_form`) REFERENCES `vih_form` (`id_vih_form`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vih_grupo_factor` FOREIGN KEY (`id_grupo_factor`) REFERENCES `cat_grupo_factor_riesgo` (`id_grupo_factor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vih_form`
--
ALTER TABLE `vih_form`
  ADD CONSTRAINT `fk_vih_corregimiento` FOREIGN KEY (`per_id_corregimiento`) REFERENCES `cat_corregimiento` (`id_corregimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vih_tipo_identidad` FOREIGN KEY (`id_tipo_identidad`) REFERENCES `cat_tipo_identidad` (`id_tipo_identidad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vih_muestra_prueba_silab`
--
ALTER TABLE `vih_muestra_prueba_silab`
  ADD CONSTRAINT `fk_vih_muestra_prueba_form` FOREIGN KEY (`id_vih_form`) REFERENCES `vih_form` (`id_vih_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vih_muestra_silab`
--
ALTER TABLE `vih_muestra_silab`
  ADD CONSTRAINT `fk_vih_muestra_form` FOREIGN KEY (`id_vih_form`) REFERENCES `vih_form` (`id_vih_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vih_tarv`
--
ALTER TABLE `vih_tarv`
  ADD CONSTRAINT `fk_vih_tarv_vih_form` FOREIGN KEY (`id_vih_form`) REFERENCES `vih_form` (`id_vih_form`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vm_form`
--
ALTER TABLE `vm_form`
  ADD CONSTRAINT `fk_vigmor_pais` FOREIGN KEY (`per_id_pais`) REFERENCES `cat_pais` (`id_pais`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
