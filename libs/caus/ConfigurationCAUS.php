<?php
class ConfigurationCAUS{

    const DBHandler = 'mysql';
    const DBuser = 'root';
    const DBpass = '';
    const DB = 'caus_sisvig';
    const host = 'localhost';
    
 //BD EN EL SERVIDOR DE GODADDY
//    const DBHandler = 'mysql';
//    const DBuser = 'dtroncoso';
//    const DBpass = 'dTBdFlu2010';
//    const DB = 'caus_sisvig';
//    const host = 'localhost';
    
    // BD EN EL SERVIDOR DE GODADDY
//    const DBHandler = 'mysql';
//    const DBuser = 'sisvig';
//    const DBpass = '123456';
//    const DB = 'caus_sisvig';
//    const host = 'localhost';

    // No. de dias para cambiar una clave, si es 0 no solicita cambiar
    const expiracion = 0;
    const orgCdc = 48;
    const orgMinsa = 49;

    // Expiracion de la sesion del usuario (en minutos), si es 0 entonces la sesion no expira
    const tiempoSesion = 120;

    // Variables para envio de correo electronico
    const emailAsunto = "Recuperar clave para software de SISIVIG";
    const emailAsuntoUsuario = "Recuperar usuario para software de SISVIG";
    const emailDe = "admin_sisvig@programainfluenza.org";
    const emailResponder = "admin_sisvig@programainfluenza.org";
    const emailPara = "admin_sisvig@programainfluenza.org";

    // Acciones
    const Agregar = 1;
    const Modificar = 2;
    const Borrar = 3;
    const Consultar = 4;
    const Reportes = 5;

    // Niveles de Ubicaciones
    const Provincia = 1;
    const Region = 2;
    const Distrito = 3;
    const Corregimiento = 4;
    const Localidad = 5;

    //Sistema
    Const sisvig = 1;
    //Modulos
    Const vigRutinaria = 70;
    Const vigBrotes = 71;
    Const vigMortalidad = 72;
    Const vigCentinela = 73;
    Const casosEspeciales = 74;
    Const catalogos = 4;
    Const ayuda = -1; // falta
    Const idSeccionPadreOrg = 47;
    // Secciones
    Const invCasos = 84; //falta
    Const influenza = 88; //falta
    Const TB = 110; 
    Const brotes = -1;
    Const vigmor = 2;
    Const eno = 3;
    Const notic = 6;
    Const rae = 66;
    Const monitoreo = -1; // falta
    const materna = -1; //falta
    const perinatal = -1; //falta
    const ninos = -1; //falta    
    Const catGrupoEvento = 13;
    Const catEvento = 14;
    Const catUnidadNotificadora = 15;
    Const catServicios = 56;
    Const catSintomas = 63;
    Const catTipoMuestras = 61;
    Const catExamenes = 62;
    Const catCargo = 60;
    Const catOcupacion = 64;
    Const catTipoIdentidad = 65;
    Const catPersonalMedico = 77;
    Const catServiciosRae = 78;
    Const malformacion = 79;
    Const vih = 92;
//    Const vicIts = 101;
    Const vicIts = 100;
    Const mat = 108;
    //SubSecciones
    Const vigmorFormulario = 7;
    Const vigmorReporte = 8;
    Const vigmorRepIndividual = 9;
    Const enoFormulario = 10;
    Const enoreporte = 11;
    Const enoCargar = 12;
    Const noticFormulario = 57;
    Const noticReporteIndividual = 58;
    Const noticReporteConsolidado = 59;
    Const raeFormulario = 67;
    Const raeReporteIndividual = 68;
    Const raeReporteServicio = 69;
    Const raeReporteMorbilidad = 75;
    Const raePermisoEspecial = 76;
    Const malformacionFormulario = 80;
    Const malformacionNacidos = 81;
    Const malformacionReportes = 82;    
    Const casosFormulario = 85;
    Const casosReporteIndividual = 86;
    Const casosReporteConsolidado = 87;
    Const fluFormulario = 89;
    Const fluExportacionVariables = 103; //nuevo
    Const TBFormulario = 111;
    Const TBPagIni = 112; 
    Const TBReportes = 113; 
    //Identificador de la seccion padre para las organizaciones
    //Nuevos catalogos
    Const catEnfermedadCronica = 90;
    Const catAntecedenteVacunal = 91;
    Const vihFormulario = 93;
    Const vihExportarExcel = 94;
    Const vihRepConsolidado = 95;
    Const vihRepRegiones = 96;
    Const vihRepFactorRiesgo = 97;
    Const vihRepEnfermedad = 98;
    Const vihSincronizarSilab = 99;
//    Const vihSincronizarEpiInfo = 105; 
    Const vihSincronizarEpiInfo = 104;
    //Submenus de VICITS
//    Const vicItsFormulario = 102;
//    Const vicItsExportarExcel = 103;
    Const vicItsFormulario = 101;
    Const vicItsExportarExcel = 102;
    Const vicItsFormLaboratorio = 107;
    Const matFormulario = 109;
}
?>
