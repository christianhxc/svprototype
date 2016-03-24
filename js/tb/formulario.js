var globalEnfermedadesRelacionados = new Array();
var globalVacunasRelacionados = new Array();
var globalMuestras = new Array();

var globalMuestrastb = new Array();
var globalMuestrasSilab = new Array();
var globalPruebasSilab = new Array();

// Inicializacion fechas


$(function() {
    $( "#fecha_nacimiento" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_preso" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_antespreso" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_BK1" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_BK2" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_BK3" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_cultivo_basilo" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_WRD" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_clinico" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_R-X" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_histopa" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_diag_VIH" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_transf" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_inicio_tratF1" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_fin_tratF1" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_inicio_tratF2" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_fin_tratF2" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_muestra_VIH" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_cotrimoxazol" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_TARV" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});



$(function() {
    $( "#fecha_inicio_TARV" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_prueba_VIH" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_prueba_VIH_con" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_cotrimoxazol2" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_cotrimoxazol2_con" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_inicio_TARV2" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_inicio_TARV2_con" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_control" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_control_BK" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_cultivo_control" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});



$(function() {
    $( "#fecha_preso_contact" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_visita" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_egreso" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#egr_fecha_trat_seg_linea" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_reac_adv" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


$(function() {
    $( "#fecha_notificacion" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "2010:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});



// Inicio de script - AM
$(document).ready(function() {
    
    
    $("table[id='insert_control']").toggle(false);
    $("#cancelar_Ctrl").css("display","none");
    
    // Divide en tabs el ingreso de los datos
    $(function() {
        $("#tabs").tabs({
            selected:0, 
            select:function(event, ui){
                if(ui.index==5)
                    $('#next').html("Siguiente");
                else
                    $('#next').html("Siguiente");
            }
        });
    });
//    sexoEmbarazo();
    relacionempleado();
    GrupoPoblacional();
    privacionLibertad();
    privacionLibertadAntes();
    clasificacionBK1();
    clasificacionBK2();
    clasificacionBK3();
    clasificacionTB();
    clasificacionBKControl();
    clasificacionPresoControl();
    resultadoBKControl();
    resultadoCultivoControl();
    ControlReaAdv();
    otroRX();
    otroWRD();
    Pulmonar_EP();
    Pulmonar_EP_Otra();
    Antes_Tratado();
    TB_VIH();
    Prueba_VIH_act();
//    ContactEdad();  // Desactivado por requerimiento de DO
    EdadEscolaridad();
    Egreso_excl();
    Egreso_fecha_seg_lin();
    Res_VIH();
    Ref_Inst();
    Ref_Transferencia();
    Ref_Trat_Fam();
    Res_med();
    Tab_VIH();
    Cul_Ctrl();
    
    cotrimoVIH_sin();
    TARVrefVIH_sin();
    TARViniVIH_sin();
    
    cotrimoVIH_con();
    TARViniVIH_con();
    
    label_MDR_message();
    Res_MDR();
    Res_Inmuno();
    Trat_Med_F1();
    Trat_Med_F2();
    Tooltip_field();
    individuo($("#drpTipoId").val(), $("#no_identificador").val());

    semanaOblig();
// Popup de búsqueda
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    

    
    
    
    $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 750,
        width: 1000,
        modal: true,
        position: 'center',
        buttons: {
            Salir: function() {
                borrarTabla();
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form" ).bind("dialogclose",function(){
        borrarTabla()
    });
    
    borrarTabla();

    
    $( "#notificacion_unidad" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#notificacion_unidad").val($("<div>").html(li.selectValue).text());
            $("#notificacion_id_un").val(li.extra[0]);
            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
    
    
    $( "#inst_salud_referencia" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#inst_salud_referencia").val($("<div>").html(li.selectValue).text());
            $("#id_inst_salud_referencia").val(li.extra[0]);
//            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
    
    $( "#inst_salud_transferencia" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#inst_salud_transferencia").val($("<div>").html(li.selectValue).text());
            $("#id_inst_salud_transferencia").val(li.extra[0]);
//            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
    
    $( "#lug_adm_TARV" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#lug_adm_TARV").val($("<div>").html(li.selectValue).text());
            $("#id_lug_adm_TARV").val(li.extra[0]);
//            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
    
    
    $( "#lug_adm_TAV_con" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#lug_adm_TAV_con").val($("<div>").html(li.selectValue).text());
            $("#lug_adm_TAV_con_id").val(li.extra[0]);
//            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });

   // Contactos - Menores de 5 anos
   
   $("#sinto_resp5min").change(function(){
       
       if ($("#identificados5min").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos identificados'");
           $("#sinto_resp5min").val("");
       }else
           {
              if (!($("#identificados5min").val() >= $("#sinto_resp5min").val()))
                 {
                     alert ("El 'Total de contactos sintomaticos respiratorios' tiene que ser mayor que 'Total de contactos identificados'");
                     $("#sinto_resp5min").val("");
                 } 
           }
   });
   
   $("#drpres_VIH").change(function(){
          drpres_VIH_func();
   });
   
    $("#evaluados5min").change(function(){
       if ($("#identificados5min").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos identificados'");
           $("#evaluados5min").val("");
       }else
           {
              if (!($("#identificados5min").val() >= $("#evaluados5min").val()))
                {
                 alert ("El 'Total de contactos evaluados' tiene que ser mayor que 'Total de contactos identificados'");
                 $("#evaluados5min").val("");
                }
           }
   });

    $("#quimioprofilaxis5min").change(function(){
       if ($("#identificados5min").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos identificados'");
           $("#quimioprofilaxis5min").val("");
       }else
           {
              if (!($("#identificados5min").val() >= $("#quimioprofilaxis5min").val()))
                {
                 alert ("El 'Total de contactos con Quimioprofilaxis' tiene que ser mayor que 'Total de contactos identificados'");
                 $("#quimioprofilaxis5min").val("");
                }
           }
   });
   
      $("#TB5min").change(function(){
       
       if ($("#sinto_resp5min").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos sintomáticos respiratorios'");
           $("#TB5min").val("");
       }else
           {
              if (!($("#sinto_resp5min").val() >= $("#TB5min").val()))
                 {
                     alert ("El 'Total de contactos con TB' tiene que ser mayor que 'Total de contactos sintomaticos respiratorios'");
                     $("#TB5min").val("");
                 } 
           }
   });

  // Contactos - 5 y mas anos
   
   $("#sinto_resp5pl").change(function(){
       
       if ($("#identificados5pl").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos identificados'");
           $("#sinto_resp5pl").val("");
       }else
           {
              if (!($("#identificados5pl").val() >= $("#sinto_resp5pl").val()))
                 {
                     alert ("El 'Total de contactos sintomaticos respiratorios' tiene que ser mayor que 'Total de contactos identificados'");
                     $("#sinto_resp5pl").val("");
                 } 
           }
   });
   
    $("#evaluados5pl").change(function(){
       if ($("#identificados5pl").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos identificados'");
           $("#evaluados5pl").val("");
       }else
           {
              if (!($("#identificados5pl").val() >= $("#evaluados5pl").val()))
                {
                 alert ("El 'Total de contactos evaluados' tiene que ser mayor que 'Total de contactos identificados'");
                 $("#evaluados5pl").val("");
                }
           }
   });

    $("#quimioprofilaxis5pl").change(function(){
       if ($("#identificados5pl").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos identificados'");
           $("#quimioprofilaxis5pl").val("");
       }else
           {
              if (!($("#identificados5pl").val() >= $("#quimioprofilaxis5pl").val()))
                {
                 alert ("El 'Total de contactos con Quimioprofilaxis' tiene que ser mayor que 'Total de contactos identificados'");
                 $("#quimioprofilaxis5pl").val("");
                }
           }
   });
   
      $("#TB5pl").change(function(){
       
       if ($("#sinto_resp5pl").val() == ""){
           alert ("Ingrese antes el valor de 'Total de contactos sintomáticos respiratorios'");
           $("#TB5pl").val("");
       }else
           {
              if (!($("#sinto_resp5pl").val() >= $("#TB5pl").val()))
                 {
                     alert ("El 'Total de contactos con TB' tiene que ser mayor que 'Total de contactos sintomaticos respiratorios'");
                     $("#TB5pl").val("");
                 } 
           }
   });


    // Activación Empleado Relación - AM
    $("#drpEmpleado").change(function(){
        relacionempleado();
    });
    
    // Grupo poblacional
    $("#drpPoblacional").change(function(){
        GrupoPoblacional();
    });
    
    // Cuando la profesion es otros - AM
    $("#drpProfesion").change(function(){
        if ($(this).val()==0){
             $("#labelOtrosProfesion" ).css( "display", "" );
             $("#inputOtrosProfesion" ).css( "display", "" );
        }else
            {
             $("#otrosProfesion").val("");
             $("#labelOtrosProfesion" ).css( "display", "none" );
             $("#inputOtrosProfesion" ).css( "display", "none" );          
            }
    });
    
    // cuando cambia el resultado de BK1 aparece Clasificacion BK1
    
    $("#drpres_BK1").change(function(){
        clasificacionBK1();
        clasificacionTB();
    });
    
    // cuando cambia el resultado de BK1 aparece Clasificacion BK1
    
    $("#drpres_BK2").change(function(){
        clasificacionBK2();
        clasificacionTB();
    });
    
    // cuando cambia el resultado de BK1 aparece Clasificacion BK1
    
    $("#drpres_BK3").change(function(){
        clasificacionBK3();
        clasificacionTB();
    });


    $("#drpres_cultivo").change(function(){
        clasificacionTB();
    });

    $("#drpres_WRD").change(function(){
        clasificacionTB();
    });

    $("#drpres_clinico").change(function(){
        clasificacionTB();
    });
    
    $("#drpres_R-X").change(function(){
        clasificacionTB();
    });

    $("#drpres_histopa").change(function(){
        clasificacionTB();
    });

    $("#fecha_control_BK").change(function(){
        resultadoBKControl();
    });

    $("#drpcontrol_BK").change(function(){
        clasificacionBKControl();
    });
    
    $("#fecha_cultivo_control").change(function(){
        resultadoCultivoControl();
    });
    
    $("#drpantespreso").change(function(){
        privacionLibertadAntes();
    });
    
    $("#drpcontacpreso").change(function(){
        clasificacionPresoControl();
    });
    
    $("#drpreac_adv").change(function(){
        ControlReaAdv();
    });
    
    $("#drpmetodo_WRD").change(function(){
        otroWRD();
    });

// Ancedentes del paciente

    $("#drppreso").change(function(){
        privacionLibertad();
    });
    
    $("#weight").change(function(){
        if ($(this).val() <= 0){
             alert("El valor de 'Peso al ingreso' no puede ser menor o igual a 0 Kg.");
             $(this).val("");
        }else
            {
                if ($(this).val() >= 251){
                    alert("El valor de 'Peso al ingreso' no puede ser mayor a 250 Kg.");       
                    $(this).val("");
                }
            }
    });
    
    $("#height").change(function(){
        if ($(this).val() <= 0){
             alert("El valor de la 'Talla de ingreso' no puede ser menor o igual a 0 metros");
             $(this).val("");
        }else
            {
                if ($(this).val() >= 2.4){
                    alert("El valor de la 'Talla de ingreso' no puede ser mayor a 2.3 metros");       
                    $(this).val("");
                }
            }
    });
    
    // Pulmonar - Extra Pulmonar
    
    $("#drplocanato").change(function(){
        Pulmonar_EP();
    });
    
    $("#drplugar_EP").change(function(){
        Pulmonar_EP_Otra();
    }); 
    
    
  //    Historia de tratamiento previo - Antes tratado  
    
    
    $("#postfracaso, #recaida, #perdsegui, #otros_antestratado").change(function(){
        
        $("#postfracaso, #recaida, #perdsegui, #otros_antestratado ").attr("checked",false);
        $(this).attr("checked",true);
    });
    
    // Antes tratado

    $("#nuevo").change(function(){
        Antes_Tratado();
    });       
    
    $("#antes_tratado").change(function(){
        Antes_Tratado();
    });    
    
    $("#historia_desconocida").change(function(){
        Antes_Tratado();
    });
    
    // TB_VIH
    
    $("#drpdiag_VIH").change(function(){
        TB_VIH();
        Res_VIH();
    });
    
    
    $("#drprealizada_VIH").change(function(){
        Prueba_VIH_act();
    });
    
    
    // Egreso
    
    $("#drpcond_egreso").change(function(){
        Egreso_excl();
    });
    $("#egr_trat_seg_linea").change(function(){
        Egreso_fecha_seg_lin();
    });


    $("input[id='MDR']").change(function(){
        Res_MDR();
    });
    
    $("input[id='inmunodepresor']").change(function(){
        Res_Inmuno();
    });
    
    $("input[id='trat_med_indF1']").change(function(){
        Trat_Med_F1();
    });
    
    $("input[id='trat_med_indF2']").change(function(){
        Trat_Med_F2();
    });
    
    // Clasi.
    
    $("#MonoR").change(function(){
        Res_med();
    });

    $("#ninguna").change(function(){
        Res_med();
    });
    
    $("#Res_MDR").change(function(){
        Res_med();
    });
    
    
    $("#XDR").change(function(){
        Res_med();
    });
  
    $("#TB-RR").change(function(){
        Res_med();
    });  

    $("#desconocida").change(function(){
        Res_med();
    });  
    
    $("#PoliR").change(function(){
        Res_med();
    });
    
    $('#fluoroquinolonas_PoliR').change(function(){
        Res_med();
    });

    $('#2linea_PoliR').change(function(){
        Res_med();
    });

// Validación VIH
    $('#drpsolicitud_VIH').change(function(){
        Tab_VIH();
    });


    $('#drptipo_edad').change(function(){
        sexoEmbarazo(); 
//        ContactEdad();  // Desactivado por requerimiento de DO
        EdadEscolaridad();
        LimitesEdades();
        fecha_nacimiento_dis();

    });
  
    $('#edad').change(function(){
        sexoEmbarazo(); 
//        ContactEdad(); // Desactivado por requerimiento de DO
        EdadEscolaridad();
        LimitesEdades();
        fecha_nacimiento_dis();
    });  
    
    // Tratamiento
    
    $('#drpreferido').change(function(){
        Ref_Inst();
    });
    
    $('#drptransferido').change(function(){
        Ref_Transferencia();
    });
    
    
    $('#drpadministracionF1').change(function(){
        Ref_Trat_Fam();
    });
    
    
    
    // Control
    $('#drpres_cul_contr').change(function(){
        Cul_Ctrl();
    });
    
    $('#drpcotrimoxazol').change(function(){
        cotrimoVIH_sin();
    });
    
    $('#drpTARV').change(function(){
        TARVrefVIH_sin();
    });

    $('#drpinicio_TARV').change(function(){
        TARViniVIH_sin();
    });

// VIH con prueba

    $('#drpcotrimoxazol_con').change(function(){
        cotrimoVIH_con();
    });
   

    $('#drpact_TARV_con').change(function(){
        TARViniVIH_con();
    });


    // Validación de semana gestacional
    
    $('#semana_gestacional').change(function(){
        if ($(this).val() >= 43) {
            alert ("La semana gestacional solamente puede tener valores entre 1 y 42");
            $(this).val("");
        }
    });
    
    $("input:checkbox[id='MDR']").change( function(){
        
        label_MDR_message();
    });
    
    $("input:checkbox[id='H_PoliR']").change( function(){        
        if ($("input:checkbox[id='R_PoliR']").is(":checked")) 
           alert ("Seleccionar la opcion MDR en 'Resistencia a los medicamentos'");
    });

    $("input:checkbox[id='R_PoliR']").change( function(){        
        if ($("input:checkbox[id='H_PoliR']").is(":checked")) 
           alert ("Seleccionar la opcion MDR en 'Resistencia a los medicamentos'");
    });
    
    $("#drpTipoId").change( function(){ 
    
        setTipoId();
    
    });
    
    setTipoId();
    
});

function setTipoId(){
    var tipo = $("#drpTipoId").val();
    if (tipo != 1){
        $("#divCedula1").show();
        $("#divCedula2").hide();
    }
    else{
        $("#divCedula1").hide();
        $("#divCedula2").show();
    }
}

function label_MDR_message(){
    VIH_true = false;
    MDR_group = $("input:checkbox[id='MDR']:checked");
    
    jQuery.each( MDR_group, function( i, MDR_element ) {
     if (MDR_element.value ==2) VIH_true=true;
    });
    
        if (MDR_group.length == 0 ){
            
            $("#alert_MDR").hide();
            $("#alert_MDR_VIH").hide();
            
        }else
        {   
            if (VIH_true == true ) {
                $("#alert_MDR").hide();
                $("#alert_MDR_VIH").show();
            } else {
                $("#alert_MDR_VIH").hide();
                $("#alert_MDR").show();
            }
            
        }    
            
}

function fecha_nacimiento_dis(){

        if ($('#drptipo_edad').val()!=""  && $('#edad').val() != "" ){
            $('#fecha_nacimiento').attr("disabled", "disabled");
            $("#fecha_nacimiento").next().css("display", "none");
            $('#fecha_nacimiento').val("");
        } else
        {
            $('#fecha_nacimiento').attr("disabled", "");
            $("#fecha_nacimiento").next().css("display", "");          
        }

}

function Cul_Ctrl(){
    Cul_Con = $('#drpres_cul_contr').val();
    if(Cul_Con != "2"){
        $( "#labelResCulCtr" ).css( "display", "none" );
        $( "#selectResCulCtr" ).css( "display", "none" );
        $( "#select2ResCulCtr" ).css( "display", "none" );
        $( "#drpcontrol_H" ).val("");
        $( "#drpcontrol_R" ).val("");
        $( "#drpcontrol_Z" ).val("");
        $( "#drpcontrol_E" ).val("");
        $( "#drpcontrol_S" ).val("");
        $( "#drpcontrol_fluoroquinolonas" ).val("");
        $( "#drpcontrol_2linea" ).val("");
    }
    else 
        {
        $( "#labelResCulCtr" ).css( "display", "" );
        $( "#selectResCulCtr" ).css( "display", "" );
        $( "#select2ResCulCtr" ).css( "display", "" );
        }
}
// Relación empleado - Profesión - AM

function relacionempleado(){
    relEmp = $('#drpEmpleado').val();
    if(relEmp != '1' ){
        $( "#labelProfesion_td" ).css( "display", "none" );
        $("#drpProfesion").val("");
        $("#otrosProfesion").val("");
        $( "#drpProfesion_td" ).css( "display", "none" );
        $( "#labelOtrosProfesion" ).css( "display", "none" );
        $( "#inputOtrosProfesion" ).css( "display", "none" );
    }
    else{
       $( "#labelProfesion_td" ).css( "display", "" );
        $( "#drpProfesion_td" ).css( "display", "" );
        if ($("#drpProfesion").val() == "0") {
            $( "#labelOtrosProfesion" ).css( "display", "" );
            $( "#inputOtrosProfesion" ).css( "display", "" );           
        }else
        {   
            $("#otrosProfesion").val("");
            $( "#labelOtrosProfesion" ).css( "display", "none" );
            $( "#inputOtrosProfesion" ).css( "display", "none" );
        }
    }
}

// Etnia combo

function GrupoPoblacional(){
    relGpo = $('#drpPoblacional').val();
    if(relGpo != '1' ){
        $( "#tdlabelEtnia" ).css( "visibility", "hidden" );
        $( "#tdselectEtnia" ).css( "visibility", "hidden" );
        $( "#drpEtnia" ).val("");
    }
    else{
        $( "#tdlabelEtnia" ).css( "visibility", "" );
        $( "#tdselectEtnia" ).css( "visibility", "" );
    }

}


// Clasificación BK1
function clasificacionBK1(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpres_BK1").val()=="1"){
             $("#label_clasiBK1" ).css( "visibility", "" );
             $("#drpcla_BK1" ).css( "visibility", "" );
             clasificacionTB();
        }else
            {
             $("#drpcla_BK1").val("");
             $("#label_clasiBK1" ).css( "visibility", "hidden" );
             $("#drpcla_BK1" ).css( "visibility", "hidden" );          
            }
            
            
}


// Clasificación BK2
function clasificacionBK2(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpres_BK2").val()=="1"){
             $("#label_clasiBK2" ).css( "visibility", ""  );
             $("#drpcla_BK2" ).css( "visibility", ""  );
             clasificacionTB();
        }else
            {
             $("#drpcla_BK2").val("");
             $("#label_clasiBK2" ).css( "visibility", "hidden" );
             $("#drpcla_BK2" ).css( "visibility", "hidden" );          
            }
}

// Clasificación BK3
function clasificacionBK3(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpres_BK3").val()=="1"){
             $("#label_clasiBK3" ).css( "visibility", ""  );
             $("#drpcla_BK3" ).css( "visibility", "" );
             clasificacionTB();
        }else
            {
             $("#drpcla_BK3").val("");
             $("#label_clasiBK3" ).css( "visibility", "hidden" );
             $("#drpcla_BK3" ).css( "visibility", "hidden" );          
            }
}

// Clasificación BK3
function clasificacionTB(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpres_WRD").val() == "2" && $("#drpmetodo_WRD").val() == "1" )
        {
            $("#tdMTBdet").show();
        } else
        {
            $("#tdMTBdet").hide();
            $("#drpres_rifampicina").val("");
        }
        
        
        if ($("#drpres_BK1").val()=="1" || 
            $("#drpres_BK2").val()=="1" || 
            $("#drpres_BK3").val()=="1" ||  
            $("#drpres_cultivo").val()=="2" ||
                    $("#drpres_WRD").val()=="1" ||
                    $("#drpres_WRD").val()=="2" ||
                    $("#drpres_WRD").val()=="3" ||
                    $("#drpres_WRD").val()=="4" ||
                    $("#drpres_WRD").val()=="5")
        {
                $("#conf_bacteriol" ).css( "display", ""  );
                $("#conf_clinico" ).css( "display", "none" );
                $("#tabs").tabs("enable",4)
        } 
        else if (   $("#drpres_clinico").val()=="1" ||
                    $("#drpres_R-X").val()=="1" ||
                    $("#drpres_histopa").val()=="1" 
                    )
        {
                $("#conf_bacteriol" ).css( "display", "none"  );
                $("#conf_clinico" ).css( "display", "" );
                $("#tabs").tabs("enable",4)
        }
            
        else{
                $("#conf_bacteriol" ).css( "display", "none"  );
                $("#conf_clinico" ).css( "display", "none" );
                $( "#tabs" ).tabs("disable", 4);
        }
}
// Resultado BK Control

function resultadoBKControl(){
    if ($("#fecha_control_BK").val() == "" ){
        $("#drpcontrol_BK").val("");
        $("#drpcontrol_BK").attr("disabled",true);
        clasificacionBKControl();
        
    }else{
        $("#drpcontrol_BK").attr("disabled",false);
    }
}

// Resultado Cultivo Control

function resultadoCultivoControl(){
    if ($("#fecha_cultivo_control").val() == "" ){
        $("#drpres_cul_contr").val("");
        $("#drpres_cul_contr").attr("disabled",true);
        
    }else{
        $("#drpres_cul_contr").attr("disabled",false);
    }
}

// Clasificación BK en control
function clasificacionBKControl(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpcontrol_BK").val()=="1"){
             $("#labelClaControl" ).css( "visibility", "" );
             $("#selectClaControl" ).css( "visibility", "" );
        }else
            {
            $("#labelClaControl" ).css( "visibility", "hidden" );
             $("#selectClaControl" ).css( "visibility", "hidden" );
             $("#drpclas_BK" ).val("");        
            }
            
            
}

// Clasificación BK1
function clasificacionPresoControl(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpcontacpreso").val()=="1"){
             $("#labelPrivadaPreso" ).css( "visibility", "" );
             $("#inputPrivadaPreso" ).css( "visibility", "" );
             $("#fecha_preso_contact" ).val("");
        }else
            {
            $("#labelPrivadaPreso" ).css( "visibility", "hidden" );
             $("#inputPrivadaPreso" ).css( "visibility", "hidden" );
             $("#fecha_preso_contact" ).val("");        
            }
            
            
}

// Reacción adversa en los controles
function ControlReaAdv(){
    
        // Cuando la profesion es otros - AM
        if ($("#drpreac_adv").val()=="1"){
             $("#labelReacAdv" ).css( "visibility", "" );
             $("#inputReacAdv" ).css( "visibility", "" );
             $("#tr2_reac_adv" ).css( "display", "" );
             $("#tr_reac_adv" ).css( "display", "" );
        }else
            {
            $("#labelReacAdv" ).css( "visibility", "hidden" );
             $("#inputReacAdv" ).css( "visibility", "hidden" );
             $("#tr2_reac_adv" ).css( "display", "none" );
             $("#tr_reac_adv" ).css( "display", "none" );
             $("#fecha_reac_adv" ).val("");        
            }
            
            
}



function crearTablaEnfermedades(){
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/enfermedadCronica.php',
        success: function(data)
        {
            $("#divEnfermedadCronica").html(data);
            enfermedades = $( "#globalEnfermedadesRelacionados" ).val().split("###");
            for(var i=0; i<enfermedades.length;i++){
                enfermedad = enfermedades[i].split("-");
                llenarEnfermedad(enfermedad);
            }
        }
    });
}

function llenarEnfermedad(enfermedad){
    var idEnfermedad = enfermedad[0];
    var idResEnfermedad = enfermedad[1];
    if(idResEnfermedad=='99')
        idResEnfermedad = '-1';
    var selectBox = "#drpResCronica"+idEnfermedad+" option[value="+idResEnfermedad+"]";
    //alert(selectBox);
    $(selectBox).attr('selected', 'selected');
}

function crearTablaVacunales(){
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/antecedentesVacunales.php',
        success: function(data)
        {
            $("#divAntecedenteVacunal").html(data);            
            vacunas = $( "#globalVacunasRelacionados" ).val().split("###");
            for(var i=0; i<vacunas.length;i++){
                vacuna = vacunas[i].split("-");
                //alert(vacuna);
                llenarVacuna(vacuna);
            }
        }
    });
}

function colocarCalendario(fecha){
    $( fecha ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
}

function llenarVacuna(vacuna){
    var idVacuna = vacuna[0];
    colocarCalendario("#fechaVac"+idVacuna);
    var dosis = vacuna[1];
    var idDesc = vacuna[2];
    var fecha = vacuna[3];
    //alert(fecha);
    if(idDesc=='99')
        idDesc = '-1';
    var selectBox = "#drpDesVacunal"+idVacuna+" option[value="+idDesc+"]";
    $(selectBox).attr('selected', 'selected');
    
    var dosisInput = "#numDosis"+idVacuna;
    $( dosisInput ).val(dosis);
    
    var fechaInput = "#fechaVac"+idVacuna;
    $( fechaInput ).val(fecha);
}

function llenarTipoMuestras(){
    if( $( "#globalMuestrastb" ).val() == "" )
        return;
    tipoMuestras = $( "#globalMuestrastb" ).val().split("###");
    for(var i=0; i<tipoMuestras.length;i++){
        tipoMuestra = tipoMuestras[i].split("-");
        var idTipoMuestra = tipoMuestra[0];
        var nombreTipoMuestra = tipoMuestra[1];
        var fecha_toma = tipoMuestra[2];
        var fecha_envio = tipoMuestra[3];
        var fecha_lab = tipoMuestra[4];
        var tmpReg = i;
        idTipoMuestra = (tmpReg==0) ? idTipoMuestra : "###"+idTipoMuestra;
        globalMuestrastb[tmpReg] = new Array(idTipoMuestra, nombreTipoMuestra, fecha_toma, fecha_envio, fecha_lab);
    
    }
    crearTablaMuestrasUceti();
}



function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function buscar(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form" ).dialog("open");
}

// Moverse al siguiente TAB de datos de la muestra
function siguienteTab()
{
    if(getSelectedTabIndex()==0)
    {
        $("#tabs").tabs('select', 1)
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==1)
    {
        $("#tabs").tabs('select', 2);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==2)
    {
        $("#tabs").tabs('select', 3);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==3)
    {
        $("#tabs").tabs('select', 4);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==4)
    {
        $("#tabs").tabs('select', 5);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==5)
    {
        $("#tabs").tabs('select', 6);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==6)
    {
        $("#tabs").tabs('select', 7);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==7)
    {
        $("#tabs").tabs('select', 8);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==8)
    {
        $("#tabs").tabs('select', 9);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==9)
    {
        $("#tabs").tabs('select', 10);
        $('#next').html("Inicio");
    }
    else 
    {
        $("#tabs").tabs('select', 0);
        $('#next').html("Siguiente");
    }
}

function getSelectedTabIndex() {
    return $("#tabs").tabs('option', 'selected');
}

function clearSearch()
{
    $('#formDialog').each(function() {
        this.reset();
    });
}

function buscarPersona()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/busquedaPersonatb.php',
        data: "id="+jQuery.trim($("#id").val()) + "&his="+jQuery.trim($("#his").val())
        + "&n="+jQuery.trim($("#n").val()) 
        + "&p="+jQuery.trim($("#p").val())
        + "&ed="+jQuery.trim($("#ed").val()) + "&ed2="+jQuery.trim($("#ed2").val())
        + "&ted="+($("#drpPopTipo").val()==0?"":$("#drpPopTipo").val()) + "&sx="+($("#drpsexoP").val()==0?"":$("#drpsexoP").val())
        + "&sistema="+$("#drpSistema").val()
        + "&pagina="+pagina,
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function refrescarResultados(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        validarPopup();
    }

}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function buscar(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form" ).dialog("open");
}

function validarPopupP()
{
    pagina = 1;
    validarPopup();
}


function validarPopup()
{
    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
        buscarPersona();
    else
    {
        var ed1 = parseInt($("#ed").val());
        var ed2 = parseInt($("#ed2").val());
        
        if(ed1 > ed2)
        {
            $("#pErrors").show();
            $("#pDetalle").html("La edad desde no debe ser mayor que la edad hasta.")
        }
        else
            buscarPersona();
    }
}

function individuo(tipoId,idP)
{
    borrarPaciente();
    //alert(idP);
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/datosPersonatb.php',
        async: "false",
        data: "tipo_id="+tipoId+"&id="+ idP,
        success: function(data)
        {
            var partes = data.toString().split('#');
            
            if(data.toString().length>0)
            {
                $("#drpTipoId").val(replace(partes[0]));
                $("#tipoId").val(replace(partes[0]));
//                $("#no_identificador").val(replace(partes[1]));
                if (partes[0]==1){
                    $arrayIdentificador = partes[1].split("-");
                    $("#no_identificador1").val($arrayIdentificador[0]);
                    $("#no_identificador2").val($arrayIdentificador[1]);
                    $("#no_identificador3").val($arrayIdentificador[2]);
                    $('#no_identificador1').attr('readonly', true);
                    $('#no_identificador1').attr('disabled', 'disabled');
                    $('#no_identificador2').attr('readonly', true);
                    $('#no_identificador2').attr('disabled', 'disabled');
                    $('#no_identificador3').attr('readonly', true);
                    $('#no_identificador3').attr('disabled', 'disabled');
                }
                else{    
                    $("#no_identificador").val(replace(partes[1]));
                    $('#no_identificador').attr('readonly', true);
                    $('#no_identificador').attr('disabled', 'disabled');
                }
                setTipoId();
                $("#primer_nombre").val(replace(partes[2]));
                $("#segundo_nombre").val(replace(partes[3]));
                $("#primer_apellido").val(replace(partes[4]));
                $("#segundo_apellido").val(replace(partes[5]));
                $("#casada_apellido").val(replace(partes[6]));
                
                $("#fecha_nacimiento").val((partes[7]==''?'':invFecha(1,partes[7])));
                $("#drptipo_edad").val(partes[8]);
                $("#edad").val(partes[9]);
                $("#drpsexo").val(partes[10]);
                 
//                ContactEdad(); // Desactivado por requerimiento de DO
                
                $("#nombre_responsable").val(partes[11]);
                
                $("#direccion_individuo").val(partes[16]);
                $("#otra_direccion").val(partes[19]);
                $("#telefono").val(partes[20]);
                
                $("#drpEtnia").val(partes[21]);
                $("#drpPoblacional").val(partes[22]);
                $("#drpEstadoCivil").val(partes[23]);
                $("#drpEscolaridad").val(partes[24]);
                $("#drpProfesion").val(partes[25]);
                
                
                idProvincia = partes[12];
                idRegion = partes[13];
                idDistrito = partes[14];
                idCorregimiento = partes[15];
                
                $("#idPro").val(idProvincia);
                $("#idReg").val(idRegion);
                $("#idDis").val(idDistrito);
                $("#idCor").val(idCorregimiento);
                
                $("#drpProIndividuo").val(idProvincia);
                setRegionPersona(idProvincia, idRegion);
                setDistritoPersona(idProvincia, idRegion, idDistrito);
                setCorregimientoPersona(idDistrito, idCorregimiento);
                
                $("#resultadosBusqueda").html('');
                $("#dialog-form").dialog('close');
                found = true;
                calcularEdad();
                sexoEmbarazo();
                EdadEscolaridad();
                
            }
            else
                found = false;
        }
    });    
}

function individuoSilab(tipoId,idP, silab)
{
    //    alert("Aqui");
    //alert(idP);
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/datosPersonaSilab.php',
        data: "tipo_id="+tipoId+"&id="+ idP+"&silab="+ silab,
        success: function(data)
        {
//            alert(data);
            var partes = data.toString().split('#');
            
            if(data.toString().length>0)
            {
                if(tipoId == 1){//Cedula
                    $("#drpTipoId").val(1);
                    $("#tipoId").val(1);
                }else if(tipoId == 2){//Pasaporte
                    $("#drpTipoId").val(4);
                    $("#tipoId").val(4);
                }else if(tipoId == 3){//Expediente
                    $("#drpTipoId").val(2);
                    $("#tipoId").val(2);
                }
                else if(tipoId == 4){//Codigo
//                    $("#drpTipoId").val(2);
//                    $("#tipoId").val(2);
                }
                //                $("#drpTipoId").val(replace(partes[0]));
                //                $("#tipoId").val(replace(partes[0]));
                $("#no_identificador").val(replace(partes[1]));
                
                $("#primer_nombre").val(replace(partes[2]));
                $("#segundo_nombre").val(replace(partes[3]));
                $("#primer_apellido").val(replace(partes[4]));
                $("#segundo_apellido").val(replace(partes[5]));
                
                $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
                $("#drptipo_edad").val(partes[7]);
                $("#edad").val(partes[8]);
                $("#drpsexo").val(partes[9]);
                
                //                $("#nombre_responsable").val(partes[10]);
                
                $("#direccion_individuo").val(partes[15]);
                //                $("#otra_direccion").val(partes[18]);
                $("#telefono").val(partes[19]);
                
                idProvincia = partes[11];
                idRegion = partes[12];
                idDistrito = partes[13];
                idCorregimiento = partes[14];
                
                $("#idPro").val(idProvincia);
                $("#idReg").val(idRegion);
                $("#idDis").val(idDistrito);
                $("#idCor").val(idCorregimiento);
                
                $("#drpProIndividuo").val(idProvincia);
                setRegionPersona(idProvincia, idRegion);
                setDistritoPersona(idProvincia, idRegion, idDistrito);
                setCorregimientoPersona(idDistrito, idCorregimiento);
                
                $("#resultadosBusqueda").html('');
                $("#dialog-form").dialog('close');
                found = true;
                calcularEdad();
                sexoEmbarazo();
            }
            else
                found = false;
        }
    });    
}

function borrarPaciente()
{
    //Falta arreglar las provincias y demas borrarle los datos
    found = false;
    $("#id_individuo").val(-1);
    // borra todos los datos de la pestaña de individuo
    
    $("#hospitalizadoSi").attr('checked',false);
    $("#hospitalizadoNo").attr('checked',false);
    $("#drpTipoPaciente").val(0);
    $("#drpHospitalizado").val(0);
    $("#drpHospitalizado").css( "display", "none" );
    // Datos personales
//    $("#aseguradoSi").attr('checked',false);
//    $("#aseguradoNo").attr('checked',false);
//    $("#aseguradoDesc").attr('checked',false);
    $("#drpTipoId").val(0);
    $("#no_identificador").val("");
    $("#no_identificador1").val("");
    $("#no_identificador2").val("");
    $("#no_identificador3").val("");
    setTipoId();
    
    $("#primer_nombre").val("");
    $("#segundo_nombre").val("");
    $("#primer_apellido").val("");
    $("#segundo_apellido").val("");
    
    $("#drptipo_edad").val(0);
    $("#edad").val("");
    $("#fecha_nacimiento").val("");
    $("#drpsexo").val(0);
    
    $("#nombre_responsable").val("");
    
    $("#drpProIndividuo").val(0);
    $("#idPro").val(0);
    $("#drpRegIndividuo").val(0);
    $("#idReg").val(0);
    $("#drpDisIndividuo").val(0);
    $("#idDis").val(0);
    $("#drpCorIndividuo").val(0);
    $("#idCor").val(0);
    $("#direccion_individuo").val("");
    $("#otra_direccion").val("");
    $("#telefono").val("");

//$("#no_direccion_individuo").attr('checked',false);
//clickNoDirIndividuo();
}

function clickNoDirIndividuo()
{   
    if ($("#no_direccion_individuo").is(":checked"))
    {
        $("#direccion_individuo").val("");
        $("#direccion_individuo").attr('disabled', true);
    }
    else
        $("#direccion_individuo").attr('disabled', false);  
}

function setRegionCascada(){
    
    setRegionPersona($("#drpProIndividuo").val(),-1);
    $("#drpRegIndividuo").val("");
}

function setRegionPersona(idProvincia, idRegion)
{
    $.getJSON(urlprefix + 'js/dynamic/regiones.php',{
        idProvincia: idProvincia,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idRegion)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }
        
        $("#drpRegIndividuo").html(options);
    })
}

function setDistritoCascada(){
    setDistritoPersona($("#drpProIndividuo").val(),$("#drpRegIndividuo").val(),-1);
}

function setDistritoPersona(idProvincia, idRegion, idDistrito)
{
    $.getJSON(urlprefix + 'js/dynamic/distritos.php',{
        idProvincia: idProvincia,
        idRegion:idRegion,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idDistrito)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }
        
        $("#drpDisIndividuo").html(options);
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDisIndividuo").val(),-1);
}

function setCorregimientoPersona(idDistrito, idCorregimiento)
{
    $.getJSON(urlprefix + 'js/dynamic/corregimientos.php',{
        idDistrito: idDistrito,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idCorregimiento)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }
        
        $("#drpCorIndividuo").html(options);
    })
}

function otraUnidad(){
    noDisponible = $('#unidad_disponible').is(':checked');
    if(noDisponible){
        $('#notificacion_unidad').val("");
        $('#notificacion_unidad').attr('readonly', true);
        $('#notificacion_unidad').attr('disabled', 'disabled');
    }
    else{
        $('#notificacion_unidad').attr('readonly', false);
        $('#notificacion_unidad').attr('disabled', '');
    }
}

function dirNoDisponible(){
    dirDisponible = $('#no_direccion_individuo').is(':checked');
    if(dirDisponible){
        $('#direccion_individuo').attr('readonly', true);
        $('#direccion_individuo').attr('disabled', 'disabled');
    }
    else{
        $('#direccion_individuo').attr('readonly', false);
        $('#direccion_individuo').attr('disabled', '');
    }
}
//Laboratorio

function semanaOblig(){
    embarazo = $('#drpEmbarazo').val();
    if(embarazo == '1'){
        $( "#semanaOblig" ).css( "display", "" );
        $( "#drpDivsemana" ).css( "display", "" );
    }
    else{
        $( "#semanaOblig" ).css( "display", "none" );
        $( "#drpDivsemana" ).css( "display", "none" );
        $("#semana_gestacional").val("");
    }
}


function estaHospitalizado(){
    hospitalizadoSi = $('#hospitalizadoSi').is(':checked');
    if(hospitalizadoSi){
        $( "#drpHospitalizado" ).css( "display", "" );
        $( "#fechaHospitOblig" ).css( "display", "" );        
    }
    else{ 
        $( "#drpHospitalizado" ).css( "display", "none" );
        $( "#fechaHospitOblig" ).css( "display", "none" );
    }
}

function tipoContactoOblig(){
    contacto = $('#drpContacto').val();
    if(contacto == '1'){
        $( "#tipoContactoOblig" ).css( "display", "" );
        $( "#lblContacto" ).css( "display", "" );
        $( "#drpContactoTipo" ).css( "display", "" );
    }
    else{
        $( "#tipoContactoOblig" ).css( "display", "none" );
        $( "#lblContacto" ).css( "display", "none" );
        $( "#drpContactoTipo" ).css( "display", "none" );
    }
}

function riesgoCual(){
    riesgo = $('#drpRiesgo').val();
    if(riesgo == '1')
        $( "#riesgoCual" ).css( "display", "" );
    else
        $( "#riesgoCual" ).css( "display", "none" );
}

function viajeDonde(){
    viaje = $('#drpViaje').val();
    if(viaje == '1')
        $( "#viajeDonde" ).css( "display", "" );
    else
        $( "#viajeDonde" ).css( "display", "none" );
}

function statusFechaInfluenza(status){
    if(status == 1)
        $("#fecha_ult_dosis").val("00/00/0000");
    else if(status == 2)
        $("#fecha_ult_dosis").val("99/99/9999");
}

function statusFechaInfluenzaAnioPrevio(status){
    if(status == 1)
        $("#fecha_anio_previo").val("00/00/0000");
    else if(status == 2)
        $("#fecha_anio_previo").val("99/99/9999");
}

function sexoEmbarazo(){
    sexoEmb = $('#drpsexo').val();
    if(sexoEmb != 'F' 
        || jQuery.trim($("#edad").val())== "" 
        || $("#drptipo_edad").val()!=3){
        $( "#labelsemana" ).css( "display", "none" );
        $("#drpEmbarazo").val("");
        $("#semana_gestacional").val("");
        $( "#labelEmbarazo" ).css( "display", "none" );
        $( "#drpDivsemana" ).css( "display", "none" );
        $( "#drpEmbarazo" ).css( "display", "none" );
    }
    else{
        if( jQuery.trim($("#edad").val()) >= 10 
            && jQuery.trim($("#edad").val()) <= 50){
            $( "#labelsemana" ).css( "display", "" );
            $( "#labelEmbarazo" ).css( "display", "" );
//            $( "#drpDivsemana" ).css( "display", "" );
            $( "#drpEmbarazo" ).css( "display", "" );
        }else{
            $( "#labelsemana" ).css( "display", "none" );
            $( "#labelEmbarazo" ).css( "display", "none" );
            $( "#drpDivsemana" ).css( "display", "none" );
            $( "#drpEmbarazo" ).css( "display", "none" );
        }
    }
}

// Fecha cotrimoxazol VIH
function cotrimoVIH_sin(){
    cotriVIH = $('#drpcotrimoxazol').val();
    if(cotriVIH != 1 || cotriVIH ==""){
        $( "#label_cotrimo_VIH" ).css( "display", "none" );
        $("#fecha_cotrimoxazol").val("");
        $( "#input_cotrimo_VIH" ).css( "display", "none" );
    }
    else{
        $( "#label_cotrimo_VIH" ).css( "display", "" );
        $( "#input_cotrimo_VIH" ).css( "display", "" );
    }
}

// Fecha referencia TARV VIH
function TARVrefVIH_sin(){
    TARVref_VIH = $('#drpTARV').val();
    if(TARVref_VIH != 1 || TARVref_VIH ==""){
        $( "#label_TARV_ref_VIH" ).css( "display", "none" );
        $("#fecha_TARV").val("");
        $( "#input_TARV_ref_VIH" ).css( "display", "none" );
    }
    else{
        $( "#label_TARV_ref_VIH" ).css( "display", "" );
        $( "#input_TARV_ref_VIH" ).css( "display", "" );
    }
}

// Fecha inicio TARV VIH
function TARViniVIH_sin(){
    TARVini_VIH = $('#drpinicio_TARV').val();
    if(TARVini_VIH != 1 || TARVini_VIH ==""){
        $( "#label_TARV_ini_VIH" ).css( "display", "none" );
        $("#fecha_inicio_TARV").val("");
        $( "#input_TARV_ini_VIH" ).css( "display", "none" );
        $( "#tr_lug_adm_TARV" ).css( "display", "none" );
        $("#lug_adm_TARV").val("");
        $("#id_lug_adm_TARV").val("");
        
    }
    else{
        $( "#label_TARV_ini_VIH" ).css( "display", "" );
        $( "#input_TARV_ini_VIH" ).css( "display", "" );
        $( "#tr_lug_adm_TARV" ).css( "display", "" );
    }
}


// Fecha cotrimoxazol VIH
function cotrimoVIH_con(){
    cotriVIH = $('#drpcotrimoxazol_con').val();
    if(cotriVIH != 1 || cotriVIH ==""){
        $( "#label_cotrimo_VIH_con" ).css( "display", "none" );
        $("#fecha_cotrimoxazol2_con").val("");
        $( "#input_cotrimo_VIH_con" ).css( "display", "none" );
    }
    else{
        $( "#label_cotrimo_VIH_con" ).css( "display", "" );
        $( "#input_cotrimo_VIH_con" ).css( "display", "" );
    }
}


// Fecha inicio TARV VIH
function TARViniVIH_con(){
    TARVini_VIH = $('#drpact_TARV_con').val();
    if(TARVini_VIH != 1 || TARVini_VIH ==""){
        $( "#label_TARV_ini_VIH_con" ).css( "display", "none" );
        $("#fecha_inicio_TARV2_con").val("");
        $( "#input_TARV_ini_VIH_con" ).css( "display", "none" );
        $( "#tr_lug_adm_TARV_con" ).css( "display", "none" );
        $("#lug_adm_TAV_con").val("");
        $("#lug_adm_TAV_con_id").val("");
        
    }
    else{
        $( "#label_TARV_ini_VIH_con" ).css( "display", "" );
        $( "#input_TARV_ini_VIH_con" ).css( "display", "" );
        $( "#tr_lug_adm_TARV_con" ).css( "display", "" );
    }
}


// Fecha de privación de libertad

function privacionLibertad(){
    priLibertad = $('#drppreso').val();
    if(priLibertad != 1 || priLibertad ==""){
        $( "#labelFechaPreso" ).css( "display", "none" );
        $("#drptiempopreso").val("");
        $( "#inputFechaPreso" ).css( "display", "none" );
    }
    else{
        $( "#labelFechaPreso" ).css( "display", "" );
        $( "#inputFechaPreso" ).css( "display", "" );
    }
}

function privacionLibertadAntes(){
    priLibertad = $('#drpantespreso').val();
    if(priLibertad != 1 || priLibertad ==""){
        $( "#labelFechaAntesPreso" ).css( "display", "none" );
        $("#fecha_antespreso").val("");
        $( "#inputFechaAntesPreso" ).css( "display", "none" );
    }
    else{
        $( "#labelFechaAntesPreso" ).css( "display", "" );
        $( "#inputFechaAntesPreso" ).css( "display", "" );
    }
}


// Pulmonar - Extra Pulmonar

function Pulmonar_EP(){

    if($('#drplocanato').val() == "2"){
        $( "#tr_EP" ).css( "display", "" );
    }
    else{
        $( "#tr_EP" ).css( "display", "none" );
        $( "#drplugar_EP" ).val("");
        
        
    }
}
function Pulmonar_EP_Otra(){

    if($('#drplugar_EP').val() == "2"){
        $( "#tr_EP_Otra" ).css( "display", "" );
    }
    else{
        $( "#tr_EP_Otra" ).css( "display", "none" );
        $( "#drplugar_EP_otra" ).val("");
        
        
    }
}

// Nuevo - Antes tratato - 

function Antes_Tratado(){
    pulmonar_EP = $('#antes_tratado').is(':checked');
    if(pulmonar_EP){
        $( "#table_antes_tratado" ).css( "display", "" );
    }
    else{
        $( "#table_antes_tratado" ).css( "display", "none" );
        $("#postfracaso, #recaida, #perdsegui, #otros_antestratado ").attr("checked",false);
        
    }
}

// Prueba VIH

function TB_VIH(){
    Prueba_VIH = $('#drpdiag_VIH').val();
    if(Prueba_VIH == ""){
        $( "#field_sin_prueba" ).css( "display", "none" );
        $( "#field_prueba" ).css( "display", "none" );
        $( "#field_prueba_no" ).css( "display", "" );
        $( "#drpsolicitud_VIH" ).val("");  
        $("#td_label_fecha_VIH, #td_fecha_VIH").hide();
        $("#fecha_diag_VIH").val("");
    }
    else if( Prueba_VIH == "1")
    {
        $( "#field_sin_prueba" ).css( "display", "none" );
        $( "#field_prueba" ).css( "display", "" );
        $( "#field_prueba_no" ).css( "display", "none" );
        $( "#drpsolicitud_VIH" ).val("");
        $("#td_label_fecha_VIH, #td_fecha_VIH").show();
        
        
    }
    else{
        $( "#field_sin_prueba" ).show();
        if (Prueba_VIH == 2) $("pruebaVIHLabelneg_des").text("Paciente con resultado desconocido o sin prueba de VIH")
        else $("pruebaVIHLabelneg_des").text("Paciente con prueba de VIH previa (negativa)");
        $( "#field_prueba" ).hide();
        $( "#field_prueba_no" ).hide();
        $("#td_label_fecha_VIH, #td_fecha_VIH").show();
        
    }
}


// Contacto según edad así despliega la información

function EdadEscolaridad(){
    Edad_Escolaridad = $('#edad').val();
    if(Edad_Escolaridad == "" || $("#drptipo_edad").val() != 3 || Edad_Escolaridad < 4){
        $( "#td_label_escolaridad" ).css( "display", "none" );
        $( "#td_select_escolaridad" ).css( "display", "none" );
        $( "#drpEscolaridad" ).val("");
    }
    else if( Edad_Escolaridad != "" && $("#drptipo_edad").val() == "3" && Edad_Escolaridad > 3 )
    {
        $( "#td_label_escolaridad" ).css( "display", "" );
        $( "#td_select_escolaridad" ).css( "display", "" );
    }
    
}


function LimitesEdades(){
    Limites_edades = $('#edad').val();
    
    if ($("#drptipo_edad").val() =="")
        {
        alert("Ingrese primero el tipo de edad");
        return;
        }
    
    if(Limites_edades != "" && $("#drptipo_edad").val() == 1 && Limites_edades > 29){
        $( "#edad" ).val("");
        alert ("La edad en d\xedas no puede ser mayor a 29 d\xedas")
    }

    if(Limites_edades != "" && $("#drptipo_edad").val() == 2 && Limites_edades > 11){
        $('#edad').val("");
        alert ("La edad en meses no puede ser mayor a 11 meses")
    }

    if(Limites_edades != "" && $("#drptipo_edad").val() == 3 && Limites_edades > 120){
        $('#edad').val("");
        alert ("La edad en a\xf1os no puede ser mayor a 120 a\xf1os")
    }
    
}

function Egreso_excl(){
    Egreso_e = $('#drpcond_egreso').val();
    
    if ($("#fecha_fin_tratF2").val() == "" ||  $("#fecha_fin_tratF1").val() == "" ){
        $("#egreso_fecha_trat").show();
    } else {
        $("#egreso_fecha_trat").hide();
    }
    
    if(Egreso_e == "7"){
        $( "#motivo_exclusion" ).css( "display", "" );  
        $( "#motivo_exclusion_drogo" ).hide();
        $( "#egr_trat_seg_linea" ).attr("selected",false);
        $( "#egr_trat_seg_linea" ).attr("disabled",true);
    }
    else 
        {
            $( "#motivo_exclusion" ).css( "display", "none" );
            $( "#drpmotivo_exclusion" ).val("");
            if (Egreso_e == "9"){
                $( "#motivo_exclusion_drogo" ).show();
                $( "#egr_trat_seg_linea" ).attr("disabled",false);
            } else{
                $( "#motivo_exclusion_drogo" ).hide();
                $( "#egr_trat_seg_linea" ).attr("selected",false);
                $( "#egr_trat_seg_linea" ).attr("disabled",true);
                
            }
            
        
        }
    
}

function Egreso_fecha_seg_lin(){
    
    if ($("#egr_trat_seg_linea").is(":checked")){
        $("#td_fecha_seg_lin_1").show();
        $("#td_fecha_seg_lin_2").show();
    } else
    {
        $("#td_fecha_seg_lin_1").hide();
        $("#td_fecha_seg_lin_2").hide();
        $("#egr_fecha_trat_seg_linea").val("");
        
    }
}

function Res_VIH(){
    Result_VIH = $('#drpdiag_VIH').val();
//    if(Result_VIH != "1"){
//        $( "#td_label_fecha_VIH" ).css( "visibility", "hidden" );
//        $( "#td_fecha_VIH" ).css( "visibility", "hidden" );
//    }
//    else 
//        {
//        $( "#td_label_fecha_VIH" ).css( "visibility", "" );
//        $( "#td_fecha_VIH" ).css( "visibility", "" );
//        }
    
}

function Prueba_VIH_act(){
    Result_VIH = $('#drprealizada_VIH').val();
    if(Result_VIH != "0"){
        $( "#fecha_muestra_VIH" ).attr( "disabled", false );
        $( "#drpres_VIH" ).attr( "disabled", false );
        $( "#drpaseso_VIH" ).attr( "disabled", false );
        $( "#drpTARV" ).attr( "disabled", false );
        $( "#fecha_TARV" ).attr( "disabled", false );
    }
    else 
        {
            $( "#fecha_muestra_VIH" ).val("");
            $( "#fecha_muestra_VIH" ).attr( "disabled", true );
            $( "#drpres_VIH" ).val("");
            $( "#drpres_VIH" ).attr( "disabled", true );
            $( "#drpaseso_VIH" ).val("");
            $( "#drpaseso_VIH" ).attr( "disabled", true );
            $( "#drpTARV" ).val("");
            $( "#drpTARV" ).attr( "disabled", true );
            $( "#fecha_TARV" ).val("");
            $( "#fecha_TARV" ).attr( "disabled", true );
            
        }
    
}

function Ref_Inst(){
    Ref_Insta = $('#drpreferido').val();
    if(Ref_Insta != "1"){
        $( "#labelInstaRef" ).css( "visibility", "hidden" );
        $( "#inputInstaRef" ).css( "visibility", "hidden" );
        $( "#inst_salud_referencia" ).val("");
        $( "#id_inst_salud_referencia" ).val("");
    }
    else 
        {
        $( "#labelInstaRef" ).css( "visibility", "" );
        $( "#inputInstaRef" ).css( "visibility", "" );
        }
    
}

function Ref_Transferencia(){
    Ref_Insta = $('#drptransferido').val();
    if(Ref_Insta != "1"){
        $( "#labelInstaTrans" ).css( "visibility", "hidden" );
        $( "#inputInstaTrans" ).css( "visibility", "hidden" );
        $( "#labelFechaTrans" ).css( "visibility", "hidden" );
        $( "#inputFechaTrans" ).css( "visibility", "hidden" );
        $( "#inst_salud_transferencia" ).val("");
        $( "#id_inst_salud_transferencia" ).val("");
        $( "#fecha_transf" ).val("");
    }
    else 
        {
        $( "#labelInstaTrans" ).css( "visibility", "" );
        $( "#inputInstaTrans" ).css( "visibility", "" );
        $( "#labelFechaTrans" ).css( "visibility", "" );
        $( "#inputFechaTrans" ).css( "visibility", "" );
        }
    
}

function Ref_Trat_Fam(){
    Ref_Trat = $('#drpadministracionF1').val();
    if(Ref_Trat == "2"){
        $( "#tr_adm_fam" ).show();
    } else {
        $( "#tr_adm_fam" ).hide();
    }
    
}


function drpres_VIH_func(){
    drpres_VIH_ = $('#drpres_VIH').val();
    if(drpres_VIH_ == "0"){
        $("#drpaseso_VIH").attr("disabled",true);
        $("#drpcotrimoxazol").val("");
        $("#drpcotrimoxazol").attr("disabled",true);
        $("#drpTARV").val("");
        $("#drpTARV").attr("disabled",true); 
        $("#drpinicio_TARV").val("");
        $("#drpinicio_TARV").attr("disabled",true);
    }else{
        $("#drpaseso_VIH").attr("disabled",false);
        $("#drpcotrimoxazol").attr("disabled",false);
        $("#drpTARV").attr("disabled",false);
        $("#drpinicio_TARV").attr("disabled",false);
        
    }
    
    
}

function Tab_VIH(){
    Tab_VIH_ = $('#drpsolicitud_VIH').val();
    
    if(Tab_VIH_ == "0"){
        $("#alert_VIH").css("display","")       
    }else{
        $("#alert_VIH").css("display","none");
    }
        
    if(Tab_VIH_ == "" || Tab_VIH_ == "0"){
        
        $( "#drpacepto_VIH" ).attr("disabled",true);
        $( "#drpacepto_VIH" ).val("");
        $( "#drprealizada_VIH" ).attr("disabled",true);
        $( "#drprealizada_VIH" ).val("");
        $( "#fecha_muestra_VIH" ).attr("disabled",true);
        $( "#fecha_muestra_VIH" ).val("");
        $( "#drpres_VIH" ).attr("disabled",true);
        $( "#drpres_VIH" ).val("");
        $( "#drpaseso_VIH" ).attr("disabled",true);
        $( "#drpaseso_VIH" ).val("");
        $( "#drpcotrimoxazol" ).attr("disabled",true);
        $( "#drpcotrimoxazol" ).val("");
        $( "#fecha_cotrimoxazol" ).attr("disabled",true);
        $( "#fecha_cotrimoxazol" ).val("");
        $( "#drpTARV" ).attr("disabled",true);
        $( "#drpTARV" ).val("");
        $( "#fecha_TARV" ).attr("disabled",true);
        $( "#fecha_TARV" ).val("");
        $( "#drpinicio_TARV" ).attr("disabled",true);
        $( "#drpinicio_TARV" ).val("");
        $( "#fecha_inicio_TARV" ).attr("disabled",true);
        $( "#fecha_inicio_TARV" ).val("");
        $( "#lug_adm_TARV" ).attr("disabled",true);
        $( "#lug_adm_TARV" ).val("");
        $( "#drpesq_TARV" ).attr("disabled",true);
        $( "#drpesq_TARV" ).val("");

    }
    else 
        {

        $( "#drpacepto_VIH" ).attr("disabled", false);
        $( "#drprealizada_VIH" ).attr("disabled", false);
//        $( "#fecha_muestra_VIH" ).attr("disabled", false);
//        $( "#drpres_VIH" ).attr("disabled", false);
//        $( "#drpaseso_VIH" ).attr("disabled", false);
//        $( "#drpcotrimoxazol" ).attr("disabled", false);
//        $( "#fecha_cotrimoxazol" ).attr("disabled", false);
//        $( "#drpTARV" ).attr("disabled", false);
//        $( "#fecha_TARV" ).attr("disabled", false);
//        $( "#drpinicio_TARV" ).attr("disabled", false);
//        $( "#fecha_inicio_TARV" ).attr("disabled", false);
//        $( "#lug_adm_TARV" ).attr("disabled", false);
//        $( "#drpesq_TARV" ).attr("disabled", false);        

        }
    
}


function Res_MDR(){
    if ($("input[id='MDR'][value='7']").is(":checked"))
    {
        $("#MDRotro").show();
    }else {
        $("#MDRotro").hide();
        $("#MDRotro").val("");
    }
        

}

function Res_Inmuno(){
    if ($("input[id='inmunodepresor'][value='3']").is(":checked"))
    {
        $("#inmunodepresorotro").show();
    }else {
        $("#inmunodepresorotro").hide();
        $("#inmunodepresorotro").val("");
    }
        

}

function Trat_Med_F1(){
    if ($("input[id='trat_med_indF1'][value='6']").is(":checked"))
    {
        $("#trat_med_indF1_otro_val").show();
    }else {
        $("#trat_med_indF1_otro_val").hide();
        $("#trat_med_indF1_otro_val").val("");
    }
        

}

function Trat_Med_F2(){
    if ($("input[id='trat_med_indF2'][value='4']").is(":checked"))
    {
        $("#trat_med_indF2_otro_val").show();
    }else {
        $("#trat_med_indF2_otro_val").hide();
        $("#trat_med_indF2_otro_val").val("");
    }
        

}

function Res_med(){

    if($('#MonoR').is(":checked")){
        $( "#tr_label_MonoR" ).show();
        $( "#tr_select_MonoR" ).show();
    }
    else 
        {
        $( "#tr_label_MonoR" ).hide();
        $( "#tr_select_MonoR" ).hide();
        $( "#drpesp_MonoR" ).val("");
        }
        
    if($('#PoliR').is(":checked")){
        $( "#tr_label_PoliR" ).css( "display", "" );
        $( "#tr_input_PoliR" ).css( "display", "" );
    }
    else 
        {
        $( "#tr_label_PoliR" ).css( "display", "none" );
        $( "#tr_input_PoliR" ).css( "display", "none" );
        $('#H_PoliR').attr('checked', false);
        $('#R_PoliR').attr('checked', false);
        $('#Z_PoliR').attr('checked', false);
        $('#E_PoliR').attr('checked', false);
        $('#S_PoliR').attr('checked', false);
        $('#fluoroquinolonas_PoliR').attr('checked', false);
        $('#2linea_PoliR').attr('checked', false);
        }
        
    if($('#fluoroquinolonas_PoliR').is(":checked")){
        $( "#tr_fluoro" ).css( "display", "" );
    }
    else 
        {
        $( "#tr_fluoro" ).css( "display", "none" );
        $("#drpfluoroquinolonas").val("");
        }
        
     if($('#2linea_PoliR').is(":checked")){
        $( "#tr_2linea" ).css( "display", "" );
    }
    else 
        {
        $( "#tr_2linea" ).css( "display", "none" );
        $("#drp2_linea").val("");
        }       
        
    if ($('#TB-RR').is(":checked") || $('#XDR').is(":checked") || $('#Res_MDR').is(":checked") || $('#2linea_PoliR').is(":checked") || $('#fluoroquinolonas_PoliR').is(":checked") || $('#PoliR').is(":checked") || $('#MonoR').is(":checked") )
    {
        $("#fecha_inicio_tratF1").val("");
        $("#fecha_fin_tratF1").val("");
        $("#trat_med_indF1").attr("checked",false);
        $("#drpadministracionF1").val("");
        $("#fecha_inicio_tratF2").val("");
        $("#fecha_fin_tratF2").val("");
        $("#trat_med_indF2").attr("checked",false);
        $("#drpadministracionF2").val("");
        
        $("#fecha_inicio_tratF1").attr("disabled",true);
        $("#fecha_fin_tratF1").attr("disabled",true);
        $("input:checkbox[id='trat_med_indF1']").attr("disabled",true);
        $("#drpadministracionF1").attr("disabled",true);
        $("#fecha_inicio_tratF2").attr("disabled",true);
        $("#fecha_fin_tratF2").attr("disabled",true);
        $("input:checkbox[id='trat_med_indF2']").attr("disabled",true);
        $("#drpadministracionF2").attr("disabled",true);
        
    } else
    {
        $("#fecha_inicio_tratF1").attr("disabled",false);
        $("#fecha_fin_tratF1").attr("disabled",false);
        $("input:checkbox[id='trat_med_indF1']").attr("disabled",false);
        $("#drpadministracionF1").attr("disabled",false);
        $("#fecha_inicio_tratF2").attr("disabled",false);
        $("#fecha_fin_tratF2").attr("disabled",false);
        $("input:checkbox[id='trat_med_indF2']").attr("disabled",false);
        $("#drpadministracionF2").attr("disabled",false);
    }

}


var visita = 1;
function agregarVisita(){

    var valDRPTIPO_VISITA = $("#drptipo_visita").val();
    var valFECHA_VISITA = $("#fecha_visita").val();
    var txtDRPTIPO_VISITA = $("#drptipo_visita option:selected").text();

    if ($("#drptipo_visita").val()=="" || $("#fecha_visita").val()==""){
        alert("Por favor ingrese datos en los dos campos de visitas para poder agregar una nueva");
        return;
    }

    var template = "<tr id=\"trVisita"+(visita)+"\">\n\
                            <td><input name=\"data[visita]["+(visita)+"][id_tb_visita]\" id=\"visita"+(visita)+"id_tb_visita\" type=\"hidden\" value=\""+"\"/>\n\
                                <input name=\"data[visita]["+(visita)+"][id_tipo_visita]\" id=\"visita"+(visita)+"id_tipo_visita\" type=\"hidden\" value=\""+$("#drptipo_visita").val()+"\"/>\n\
                                <input name=\"data[visita]["+(visita)+"][fecha_visita]\" id=\"visita"+(visita)+"fecha_visita\" type=\"hidden\" value=\""+$("#fecha_visita").val()+"\"/>\n\
                                <a href=\"#AVisita\" onmouseout=\"RollOut(this)\" onmouseover=\"RollOver(this)\" class=\"ui-state-default ui-corner-all ui-link-button\" title=\"Borrar\" onclick=\"if (confirm('¿Esta seguro que desea borrar esta entrada?')) removerVisita("+(visita)+");\"><span class=\"ui-icon ui-icon-trash\"></span></a></td>\n\
                            <td>" + $("#drptipo_visita option:selected").text() + "</td>\n\
                            <td>" + $("#fecha_visita").val() + "</td>"+"</tr>";

    $(template).appendTo("#tblVisitas tbody");    

    if ($("#drptipo_visita").val() == 1)
        {
             $("#drptipo_visita").find("option[value='1']").remove();
        }

    // Finalizar de agregar visitas
    $("#drptipo_visita").val("");
    $("#fecha_visita").val("");
    visita++;
    

 
}

function removerVisita(id){

        // Remover del comobo box para las pruebas
//        $("#muestra_asignada").find("option[value='"+id+"']").remove();
//        $("#muestra_asignada_conclusion").find("option[value='"+id+"']").remove();

//	idsMuestra = removeString(idsMuestra, valCodigo);
        if  ($('#trVisita' + id).find("#visita"+(id)+"id_tipo_visita").val()==1)
                $("#drptipo_visita option").eq(1).before($('<option value="1">Ingreso</option>'));

	$('#trVisita' + id).remove();
        
 visita--;
}

var control = 1;
var addcontrol=0;
function agregarControl(){

     if (addcontrol==0) {
         $("#insert_control").toggle("slow");
         $("#tdagregaControles").attr("align","center")
         $("#cancelar_Ctrl").css("display","");
         addcontrol++;
         return;
     }

     var Ctrl_v = validaragregarControl();

if (Ctrl_v){
       
      
     
    var template = '<tr id=\"trControl'+(control)+'">';
        template += '<td><input name="data[control]['+(control)+'][id_tb_control]" id="control'+(control)+'id_tb_control" type="hidden" value="'+'"/>';
        template += '<input id="control'+(control)+'fecha_control" name="data[control]['+(control)+'][fecha_control]"  type="hidden" value="'+$("#fecha_control").val()+'"/>';
        template += '<input id="control'+(control)+'control_peso" name="data[control]['+(control)+'][peso]"  type="hidden" value="'+$("#control_peso").val()+'"/>';
        template += '<input id="control'+(control)+'control_dosis" name="data[control]['+(control)+'][numero_dosis]" type="hidden" value="'+$("#control_dosis").val()+'"/>';
        template += '<input id="control'+(control)+'fecha_control_BK" name="data[control]['+(control)+'][fecha_BK]" type="hidden" value="'+$("#fecha_control_BK").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_BK" name="data[control]['+(control)+'][resultado_BK]" type="hidden" value="'+$("#drpcontrol_BK").val()+'"/>';
        template += '<input id="control'+(control)+'drpclas_BK" name="data[control]['+(control)+'][clas_BK]" type="hidden" value="'+$("#drpclas_BK").val()+'"/>';
        template += '<input id="control'+(control)+'fecha_cultivo_control" name="data[control]['+(control)+'][fecha_cultivo_control]"  type="hidden" value="'+$("#fecha_cultivo_control").val()+'"/>';
        template += '<input id="control'+(control)+'drpres_cul_contr" name="data[control]['+(control)+'][res_cul_contr]"  type="hidden" value="'+$("#drpres_cul_contr").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_H" name="data[control]['+(control)+'][control_H]"  type="hidden" value="'+$("#drpcontrol_H").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_R" name="data[control]['+(control)+'][control_R]"  type="hidden" value="'+$("#drpcontrol_R").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_Z" name="data[control]['+(control)+'][control_Z]"  type="hidden" value="'+$("#drpcontrol_Z").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_E" name="data[control]['+(control)+'][control_E]"  type="hidden" value="'+$("#drpcontrol_E").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_S" name="data[control]['+(control)+'][control_S]"  type="hidden" value="'+$("#drpcontrol_S").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_Otros" name="data[control]['+(control)+'][control_Otros]"  type="hidden" value="'+$("#drpcontrol_Otros").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_fluoroquinolonas" name="data[control]['+(control)+'][control_fluoroquinolonas]"  type="hidden" value="'+$("#drpcontrol_fluoroquinolonas").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_2linea" name="data[control]['+(control)+'][control_2linea]" type="hidden" value="'+$("#drpcontrol_2linea").val()+'"/>';
        template += '<input id="control'+(control)+'drpreac_adv" name="data[control]['+(control)+'][reac_adv]" type="hidden" value="'+$("#drpreac_adv").val()+'"/>';
        template += '<input id="control'+(control)+'fecha_reac_adv" name="data[control]['+(control)+'][fecha_reac_adv]" type="hidden" value="'+$("#fecha_reac_adv").val()+'"/>';
        
        template += '<input id="control'+(control)+'cutaneo" name="data[control]['+(control)+'][manif][cutaneo]" type="hidden" value="'+(($("#cutaneo").is(":checked")) ? $("#cutaneo").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'psiquico" name="data[control]['+(control)+'][manif][psiquico]" type="hidden" value="'+(($("#psiquico").is(":checked")) ? $("#psiquico").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'hepatico" name="data[control]['+(control)+'][manif][hepatico]" type="hidden" value="'+(($("#hepatico").is(":checked")) ? $("#hepatico").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'neurologico" name="data[control]['+(control)+'][manif][neurologico]" type="hidden" value="'+(($("#neurologico").is(":checked")) ? $("#neurologico").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'digestivo" name="data[control]['+(control)+'][manif][digestivo]" type="hidden" value="'+(($("#digestivo").is(":checked")) ? $("#digestivo").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'auditivo" name="data[control]['+(control)+'][manif][auditivo]" type="hidden" value="'+(($("#auditivo").is(":checked")) ? $("#auditivo").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'visual" name="data[control]['+(control)+'][manif][visual]" type="hidden" value="'+(($("#visual").is(":checked")) ? $("#visual").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'renal" name="data[control]['+(control)+'][manif][renal]" type="hidden" value="'+(($("#renal").is(":checked")) ? $("#renal").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'art_oseo" name="data[control]['+(control)+'][manif][art_oseo]" type="hidden" value="'+(($("#art_oseo").is(":checked")) ? $("#art_oseo").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'end_metab" name="data[control]['+(control)+'][manif][end_metab]" type="hidden" value="'+(($("#end_metab").is(":checked")) ? $("#end_metab").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'hematologico" name="data[control]['+(control)+'][manif][hematologico]" type="hidden" value="'+(($("#hematologico").is(":checked")) ? $("#hematologico").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'cardiovascular" name="data[control]['+(control)+'][manif][cardiovascular]" type="hidden" value="'+(($("#cardiovascular").is(":checked")) ? $("#cardiovascular").val()+'"/>': ""+'"/>');
        template += '<input id="control'+(control)+'otro" name="data[control]['+(control)+'][manif][otro]" type="hidden" value="'+(($("#otro").is(":checked")) ? $("#otro").val()+'"/>': ""+'"/>');
        
        template += '<input id="control'+(control)+'drpcontrol_clas" name="data[control]['+(control)+'][clasificacion]" type="hidden" value="'+$("#drpcontrol_clas").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_cond" name="data[control]['+(control)+'][conducta]" type="hidden" value="'+$("#drpcontrol_cond").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontrol_hosp" name="data[control]['+(control)+'][hospitalizado]" type="hidden" value="'+$("#drpcontrol_hosp").val()+'"/>';
        template += '<input id="control'+(control)+'drpcontacpreso" name="data[control]['+(control)+'][preso]" type="hidden" value="'+$("#drpcontacpreso").val()+'"/>';
        template += '<input id="control'+(control)+'fecha_preso_contact" name="data[control]['+(control)+'][fecha_preso]" type="hidden" value="'+$("#fecha_preso_contact").val()+'"/>';
        template += '<input id="control'+(control)+'drpusr_drogas" name="data[control]['+(control)+'][usr_drogas]" type="hidden" value="'+$("#drpusr_drogas").val()+'"/>';
        template += '<input id="control'+(control)+'drpalcoho_contact" name="data[control]['+(control)+'][alcoholismo]" type="hidden" value="'+$("#drpalcoho_contact").val()+'"/>';
        template += '<input id="control'+(control)+'drptabaquismo" name="data[control]['+(control)+'][tabaquismo]" type="hidden" value="'+$("#drptabaquismo").val()+'"/>';
        template += '<input id="control'+(control)+'drpmineria" name="data[control]['+(control)+'][mineria]" type="hidden" value="'+$("#drpmineria").val()+'"/>';
        template += '<input id="control'+(control)+'drphacimiento" name="data[control]['+(control)+'][hacinamiento]" type="hidden" value="'+$("#drphacimiento").val()+'"/>';
        template += '<input id="control'+(control)+'drpempleado" name="data[control]['+(control)+'][empleado]" type="hidden" value="'+$("#drpempleado").val()+'"/>';
        template += '<a href="#AControl" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Borrar" onclick="if (confirm(\'¿Esta seguro que desea borrar esta entrada?\')) removerControl('+(control)+');"><span class="ui-icon ui-icon-trash"></span></a></td>';
        template += '<td>'+$("#fecha_control").val()+'</td>';
        template += '<td>'+$("#control_peso").val()+'</td>';
        template += '<td>'+$("#control_dosis").val()+'</td>';
        template += '<td>'+$("#fecha_control_BK").val()+'</td>';
        template += '<td>'+$("#drpcontrol_BK  option:selected").text()+'</td>';
        template += '<td>'+$("#fecha_cultivo_control").val()+'</td>';
        template += '<td>'+$("#drpres_cul_contr  option:selected").text()+'</td>';
        template += "</tr>";

    $(template).appendTo("#tblControles tbody");    


    // Finalizar de agregar visitas
    control++;
    
    $("#insert_control").toggle("slow");
    addcontrol--;
    limpiarControl();
    $("#tdagregaControles").attr("align","right")
}
    
    
}

function cancelarControl(){
    $("#insert_control").toggle("slow");
    limpiarControl();
    $("#cancelar_Ctrl").css("display","none");
    addcontrol--;

}

function removerControl(id){

	$('#trControl' + id).remove();
        limpiarControl();
        visita--;
}

function validaragregarControl(){
    
    
    
    	if ($('#fecha_control').val() == ""){
            alert ("Debe ingresar la Fecha de control M\xe9dico");
            return false;
        }
        
        
        if ($('#control_dosis').val()==""){
            alert ("Debe ingresar N\xfamero de dosis a la fecha del control");
            return false;
        }
        
          if ($('#drpclas_BK').val()=="" && $('#drpcontrol_BK').val()=="1" ){
            alert ("Debe ingresar Clasificaci\xf3n BK control");
            return false;
        }
        

    return true;
}


function limpiarControl(){

	$('#fecha_control').val("");
        $('#control_peso').val("");
        $('#control_dosis').val("");
        $('#fecha_control_BK').val("");
        $('#drpcontrol_BK').val("");
        $('#drpclas_BK').val("");
        $('#fecha_cultivo_control').val("");
        $('#drpres_cul_contr').val("");
        $('#drpcontrol_H').val("");
        $('#drpcontrol_R').val("");
        $('#drpcontrol_Z').val("");
        $('#drpcontrol_E').val("");
        $('#drpcontrol_S').val("");
        $('#drpcontrol_fluoroquinolonas').val("");
        $('#drpcontrol_2linea').val("");
        $('#drpreac_adv').val("");
        $('#fecha_reac_adv').val("");
        $('#cutaneo').attr("checked",false);
        $('#psiquico').attr("checked",false);
        $('#hepatico').attr("checked",false);
        $('#neurologico').attr("checked",false);
        $('#digestivo').attr("checked",false);
        $('#auditivo').attr("checked",false);
        $('#visual').attr("checked",false);
        $('#renal').attr("checked",false);
        $('#art_oseo').attr("checked",false);
        $('#end_metab').attr("checked",false);
        $('#hematologico').attr("checked",false);
        $('#cardiovascular').attr("checked",false);
        $('#otro').attr("checked",false); 
	$('#drpcontrol_clas').val("");
        $('#drpcontrol_cond').val("");
        $('#drpcontrol_hosp').val("");
        $('#drpcontacpreso').val("");
        $('#fecha_preso_contact').val("");
        $('#drpusr_drogas').val("");
        $('#drpalcoho_contact').val("");
        $('#drptabaquismo').val("");
        $('#drpmineria').val("");
        $('#drphacimiento').val("");
        $('#drpempleado').val("");        
}



function calcularEdadtb(){
    calcularEdad();
    sexoEmbarazo();
    EdadEscolaridad();
}



function otroWRD(){
    otrorx = $('#drpmetodo_WRD').val();
    if(otrorx == ''){
        $( "#tdResWRD" ).css( "visibility", "hidden" );
        $("#drpres_WRD, #fecha_WRD, #drpres_rifampicina").val("");
        $("#drpres_rifampicina").hide();
    }
    else{
        if (otrorx == "1" && $("#drpres_WRD").val() == "2") { 
            $("#tdMTBdet").show();
            $("#drpres_rifampicina").show();
        } else
        {
            $("#drpres_rifampicina").val("");
            $("#tdMTBdet").hide(); 
        }
        $( "#tdResWRD" ).css( "visibility", "" );
    }
}


function otroRX(){
    otrorx = $('#drpResultadoOtro').val();
    if(otrorx == '1'){
        $( "#lblOtroNombre" ).css( "display", "" );
        $( "#resultadoOtroNombre" ).css( "display", "" );
    }
    else{
        $( "#lblOtroNombre" ).css( "display", "none" );
        $( "#resultadoOtroNombre" ).css( "display", "none" );
    }
}


function otroHallazgo(){
    otroH= $('#drpHallazgoOtro').val();
    if(otroH == '1'){
        $( "#lblOtroHallazgo" ).css( "display", "" );
        $( "#hallazgoOtroNombre" ).css( "display", "" );
    }
    else{
        $( "#lblOtroHallazgo" ).css( "display", "none" );
        $( "#hallazgoOtroNombre" ).css( "display", "none" );
    }
}

function validartb(){
    var Message = '';
    var ErroresN = '';
    var ErroresI = '';
    var ErroresA = '';
    var ErroresM = '';
    var ErroresCl = '';
    var ErroresT = '';
    var ErroresV = '';
    var ErroresCo = '';
    var ErroresCon = '';
    var ErroresVi = '';
    var ErroresE = '';
    var ErroresNotificacion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Ubicaci&oacute;n:';
    var ErroresIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Dat. del pac.:';
    var ErroresAntecedentes ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;F. Riesgo:';
    var ErroresMetDiag ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;M&eacute;todo diag.:';
    var ErroresClasifica ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Clasifica.:';
    var ErroresTrat ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Trat.:';
    var ErroresTB_VIH ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;TB/VIH:';
    var ErroresContacto='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Contac.:';
    var ErroresControles ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Controles:';
    var ErroresVisitas='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Visitas.:';
    var ErroresEgreso='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Egreso.:';
    
    //Notificacion
    noDisponible = $('#unidad_disponible').is(':checked');
    if(!noDisponible){
        if(jQuery.trim($("#notificacion_unidad").val())=="")
            ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la instalaci&oacute;n de salud.";
    }
    
    if(jQuery.trim($("#nombreInvestigador").val())=="")
            ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del investigador.";  
    

        if(jQuery.trim($("#fecha_notificacion").val())=="")
            {
                ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha de notificaci&oacute;n.";
            }
            else
            {
                if(!isDate($("#fecha_notificacion").val().toString()))
                    ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de notificaci&oacute;n no tiene el formato adecuado.";
                else{
                    if(comparacionFechas($("#fecha_notificacion").val().toString(),fechaActualString()))
                        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de notificaci&oacute;n no puede ser una fecha futura.";
                }
            }

    
    //Individuo
    
    aseguradoSi = $('#aseguradoSi').is(':checked');
    aseguradoNo = $('#aseguradoNo').is(':checked');
    aseguradoDesc = $('#aseguradoDesc').is(':checked');
    if(!(aseguradoSi || aseguradoNo || aseguradoDesc) ){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona est&aacute; asegurado."; 
    }
    if($("#drpTipoId").val()==0){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de identificador."; 
    }    

    
   if($("#drpTipoId").val() == 1){
        if(jQuery.trim($("#no_identificador1").val())=="" || jQuery.trim($("#no_identificador2").val())=="" || jQuery.trim($("#no_identificador3").val())=="" )
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador en las 3 casillas.";
    }
    else{
        if(jQuery.trim($("#no_identificador").val())=="")
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
    }
    
//    if(jQuery.trim($("#no_identificador").val())=="")
//        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
//    else{
//        if($("#drpTipoId").val()== 1 || $("#drpTipoId").val()== 5){
//            if(!validarCedula(jQuery.trim($("#no_identificador").val())))
//                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La c&eacute;dula paname&ntilde;a no tiene el formato esperado, debe tener por los menos dos guiones '-' y deben ser n&uacute;meros, ej: 10-123-4567 o 8-123-4567 ";
//        }
//    }
    
    if(jQuery.trim($("#primer_nombre").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer nombre.";
    
//    if(jQuery.trim($("#segundo_nombre").val())=="")
//        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el segundo nombre.";
    
    if(jQuery.trim($("#primer_apellido").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer apellido.";

//    if(jQuery.trim($("#segundo_apellido").val())=="")
//        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer apellido.";

    if($("#drpProIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia de la persona.";
    if($("#drpRegIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n de la persona.";
    if($("#drpDisIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito de la persona.";
    if($("#drpCorIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento de la persona.";

    if(jQuery.trim($("#fecha_nacimiento").val())=="")
    {
        if(jQuery.trim($("#edad").val())=="")
        {
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de nacimiento.";
        }
    }
    else
    {
        if(!isDate($("#fecha_nacimiento").val().toString()))
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_nacimiento").val().toString(),fechaActualString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser una fecha futura.";
        }
    }
    
    if(jQuery.trim($("#edad").val())=="")
    {
        if(jQuery.trim($("#fecha_nacimiento").val())=="")
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la edad de la persona.";
    }    
    
    if($("#drpsexo").val()=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el sexo de la persona.";

    if($('#drpsexo').val() == 'F'){
        if($("#drpEmbarazo").val()==-1 && jQuery.trim($("#edad").val()) >= 10 && jQuery.trim($("#edad").val()) <= 50 && $("#drptipo_edad").val() == "3")
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona esta embarazada.";
        if($("#drpEmbarazo").val()==1){
            if($("#semana_gestacional").val()=="")
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la semana gestacional de embarazo.";
        }
    }

    if($("#drpPoblacional").val()=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el grupo poblacional.";

    if($("#drpEtnia").val()=="" && $("#drpPoblacional").val()=="1")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la etnia.";
    
    if($("#drpEmpleado").val()=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si actualmente se encuentra empleado.";

    if($("#drpEstadoCivil").val()=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el estado civil actual del paciente.";

    if($("#drpEscolaridad").val()=="" && $("#drptipo_edad").val() == "3" && Edad_Escolaridad > 3)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la escolaridad del paciente.";

    if($("#direccion_individuo").val()=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la direcci&oacute;n del paciente.";
    
    
    //Antecedentes  -- AM

    if($("#drpdiab").val()=="")
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de Diabetes.";

// Desactivado por requerimiento de DO

//    inmunodepresor = $("input:checkbox[id='inmunodepresor']:checked");
//    
//    if (inmunodepresor.length == 0) {
//        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci&oacute;n de 'Otro evento inmunodepresor'.";
//    }
    
    if($("#drppreso").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Persona privada de libertad'.";
    
    if($("#drppreso").val()=="1" && $("#drptiempopreso").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la 'Ultima privaci&oacute;n de libertad'.";
    
    if($("#drpdrug").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Usuario de droga'.";    

    if($("#drpalcoholism").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Alcoholismo'.";

    if($("#drpsmoking").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Tabaquismo'.";

    if($("#drpmining").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Miner&iacute;a'.";

    if($("#drpovercrowding").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Hacinamiento'.";

    if($("#drpindigence").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Indigencia'.";

    if($("#drpdrinkable").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Acceso a agua potable'.";

    if($("#drpsanitation").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Acceso a saneamiento b&aacute;sico'.";

    if($("#drpcontactposi").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Contacto de caso positivo'.";

    if($("#drpBCG").val()=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n de 'Cicatriz BCG'.";

    if(jQuery.trim($("#weight").val())=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el 'Peso al ingreso (Kg)'.";

    if(jQuery.trim($("#height").val())=="")
    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el 'Talla al ingreso (m)'.";
    
// Desactivado por requerimiento de DO
    
    MDR_ = $("input:checkbox[id='MDR']:checked");
    
    if (MDR_.length == 0) {
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci&oacute;n de 'Grupo de riesgo MDR'.";
    }    


//    Metodo de diagnostico  -> ErroresM 

    if(  $("#drpres_cultivo").val()=="" && $("#drpres_clinico").val()=="" && $("#drpres_WRD").val()=="" && $("#drpres_R-X").val()==""  && $("#drpres_histopa").val()=="" && $("#fecha_BK1").val()=="" )
    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar por lo menos un m&eacute;todo de diagn&oacute;stico.";

//    if($("#drpres_BK1").val()!=""){
//        if(jQuery.trim($("#fecha_BK1").val())=="")
//        {
//            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha BK1.";
//        }
//        else
//        {
//            if(!isDate($("#fecha_BK1").val().toString()))
//                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha BK1 no tiene el formato adecuado.";
//            else{
//                if(comparacionFechas($("#fecha_BK1").val().toString(),fechaActualString()))
//                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha BK1 no puede ser una fecha futura.";
//            }
//        }
//        
//        if($("#drpres_BK1").val()=="1" && $("#drpcla_BK1").val()=="" )
//        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la clasificaci&oacute;n BK1.";
//      }
      
//    if($("#drpres_BK2").val()!=""){
//        if(jQuery.trim($("#fecha_BK2").val())=="")
//        {
//            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha BK1.";
//        }
//        else
//        {
//            if(!isDate($("#fecha_BK2").val().toString()))
//                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha BK1 no tiene el formato adecuado.";
//            else{
//                if(comparacionFechas($("#fecha_BK2").val().toString(),fechaActualString()))
//                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha BK1 no puede ser una fecha futura.";
//            }
//        }
//        
//        if($("#drpres_BK2").val()=="1" && $("#drpcla_BK2").val()=="" )
//        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la clasificaci&oacute;n BK1.";
//      }

//    if($("#drpres_BK3").val()!=""){
//        if(jQuery.trim($("#fecha_BK3").val())=="")
//        {
//            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha BK1.";
//        }
//        else
//        {
//            if(!isDate($("#fecha_BK3").val().toString()))
//                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha BK1 no tiene el formato adecuado.";
//            else{
//                if(comparacionFechas($("#fecha_BK3").val().toString(),fechaActualString()))
//                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha BK1 no puede ser una fecha futura.";
//            }
//        }
//        
//        if($("#drpres_BK3").val()=="1" && $("#drpcla_BK3").val()=="" )
//        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la clasificaci&oacute;n BK1.";
//      }


 
    if($("#drpres_cultivo").val()!="" )
    { 
        if(jQuery.trim($("#fecha_cultivo_basilo").val())=="")
            {
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha de cultivo.";
            }
            else
            {
                if(!isDate($("#fecha_cultivo_basilo").val().toString()))
                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha cultivo no tiene el formato adecuado.";
                else{
                    if(comparacionFechas($("#fecha_cultivo_basilo").val().toString(),fechaActualString()))
                        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de cultivo no puede ser una fecha futura.";
                }
            }
    } 

    if($("#drpmetodo_WRD").val()!="")
    { 
        if($("#drpres_WRD").val()=="")
        {
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el resultado de otro metodo de diagnostico - WRD.";   
        }
        else
            {
                if(jQuery.trim($("#fecha_WRD").val())=="")
                {
                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha del resultado de otro metodo de diagnostico.";
                }
                else
                {
                    if(!isDate($("#fecha_WRD").val().toString()))
                        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La Fecha del resultado de otro metodo de diagnostico no tiene el formato adecuado.";
                    else{
                        if(comparacionFechas($("#fecha_WRD").val().toString(),fechaActualString()))
                            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La Fecha del resultado de otro metodo de diagnostico no puede ser una fecha futura.";
                    }
                }
            }          
    }

    if($("#drpres_clinico").val()!=""){
        if(jQuery.trim($("#fecha_clinico").val())=="")
        {
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha del metodo de diagnostico cl&iacute;nico.";
        }
        else
        {
            if(!isDate($("#fecha_clinico").val().toString()))
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del diagnostico cl&iacute;nico no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_clinico").val().toString(),fechaActualString()))
                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del diagnostico cl&iacute;nico no puede ser una fecha futura.";
            }
        }
    }

    
    if($("#drpres_R-X").val()!=""){
        if(jQuery.trim($("#fecha_R-X").val())=="")
        {
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha de R-X.";
        }
        else
        {
            if(!isDate($("#fecha_R-X").val().toString()))
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de R-X no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_R-X").val().toString(),fechaActualString()))
                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de R-X no puede ser una fecha futura.";
            }
        }
    }

    if($("#drpres_histopa").val()!=""){
        if(jQuery.trim($("#fecha_histopa").val())=="")
        {
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha del resultado de histopalog&iacute;a.";
        }
        else
        {
            if(!isDate($("#fecha_histopa").val().toString()))
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del resultado de histopalog&iacute;a.";
            else{
                if(comparacionFechas($("#fecha_histopa").val().toString(),fechaActualString()))
                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del resultado de histopalog&iacute;a.";
            }
        }
    }

//    Clasificacion -> ErroresCl 

     if ($("#drplocanato").val() == "" )
       ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe escoger una de las opciones de Localizaci&oacute;n Anat&oacute;mica ";  
     
     if ($("#drplocanato").val() == "2" && $("#drplugar_EP").val()=="")
       ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una opci&oacute;n en Lugar EP";  

     if (!($("#nuevo").is(":checked")) && !($("#antes_tratado").is(":checked")) && !($("#historia_desconocida").is(":checked")))
       ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe escoger una de las opciones de Historia de tratamiento previo ";  
   
     if ($("#antes_tratado").is(":checked"))
         {
                  if ($("#drprecaida").val()=="")
                        ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una opci&oacute;n en Reca&iacute;da";
                  if ($("#drppostfracaso").val()=="")
                        ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una opci&oacute;n en Tratado post fracaso";
                  if ($("#drpperdsegui").val()=="")
                        ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una opci&oacute;n en Despu&eacute;s de p&eacute;rdida en el seguimiento";
                  if ($("#drpotros_antestratado").val()=="")
                        ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una opci&oacute;n en Otros";
         }

//     if ($("#drpdiag_VIH").val()=="")
//      {
//          ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una opci&oacute;n en Condici&oacute;n de VIH en Resultado";
//      } 
//     else if(jQuery.trim($("#fecha_diag_VIH").val())=="" && $("#drpdiag_VIH").val()=="1")
//        {
//            ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha del resultado de VIH.";
//        }
//        else
//        {
//            if ($("#drpdiag_VIH").val()=="1") {
//               if(!isDate($("#fecha_diag_VIH").val().toString()))
//                    ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del resultado de VIH no es un formato aceptado.";
//                else{
//                    if(comparacionFechas($("#fecha_diag_VIH").val().toString(),fechaActualString()))
//                        ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del resultado de VIH no puede ser una fecha futura.";
//                }
//            }
//            
//        }
        
    Resist_Med = $("input:radio[name='data[clasificacion][met_diag]']:checked");
    
    if (Resist_Med.length == 0) {
        ErroresCl+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci&oacute;n de 'Resistencia a los medicamentos (al ingreso)'.";
    }

//    Tratamiento -> ErroresT 

if (jQuery.trim($("#action").val()) == "M" || jQuery.trim($("#action").val()) == "R"){

    if(jQuery.trim($("#fecha_inicio_tratF1").val())=="")
    {
        ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha inicio de Datos del tratamiento en la fase 1.";
    }
    else
    {
        if(!isDate($("#fecha_inicio_tratF1").val().toString()))
            ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Ingrese La Fecha inicio de Datos del tratamiento en la fase 1 en formato correcto.";
        else{
            if(comparacionFechas($("#fecha_inicio_tratF1").val().toString(),fechaActualString()))
                ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La Fecha inicio de Datos del tratamiento en la fase 1 no puede ser una fecha futura.";
        }
    }

// Desactivado requerimiento DO

//    if(jQuery.trim($("#fecha_fin_tratF1").val())=="")
//    {
//        ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha Fin  de Datos del tratamiento en la fase 1.";
//    }
//    else
//    {
//        if(!isDate($("#fecha_fin_tratF1").val().toString()))
//            ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Ingrese La Fecha Fin de Datos del tratamiento en la fase 1 en formato correcto.";
//        else{
//            if(comparacionFechas($("#fecha_fin_tratF1").val().toString(),fechaActualString()))
//                ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La Fecha Fin de Datos del tratamiento en la fase 1 no puede ser una fecha futura.";
//        }
//    }
    
    if($("#drpadministracionF1").val()=="")
    ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el dato de administraci&oacute;n en la fase 1.";
   
    Med_Ind = $("input:checkbox[id='trat_med_indF1']:checked");
    
    if (Med_Ind.length == 0) {
        ErroresT+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci&oacute;n de 'Medicamentos indicados'.";
    }   
   
}

//    TB/VIH -> ErroresV 




//    Controles ->  ErroresCo 


//   Contacto -> ErroresCon 

    if (jQuery.trim($("#action").val()) == "M" || jQuery.trim($("#action").val()) == "R") {
        if ($("#identificados5min").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos identificados.";
        if ($("#sinto_resp5min").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos sintom&aacute;ticos respiratorios .";
        if ($("#evaluados5min").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos evaluados.";
        if ($("#quimioprofilaxis5min").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos con Quimioprofilaxis.";
        if ($("#TB5min").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos con TB.";
        if ($("#identificados5pl").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos identificados.";
        if ($("#sinto_resp5pl").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos sintom&aacute;ticos respiratorios .";
        if ($("#evaluados5pl").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos evaluados.";
        if ($("#quimioprofilaxis5pl").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos con Quimioprofilaxis.";
        if ($("#TB5pl").val() == "")
            ErroresCon += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Total de contactos con TB.";

    }

//   Visitas -> ErroresVi 



//Egreso  -> ErroresE

//     if(jQuery.trim($("#fecha_egreso").val())=="" &&  (jQuery.trim($("#action").val()) == "R" || jQuery.trim($("#action").val()) == "M") )
//         {
//             ErroresE+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la 'Fecha de egreso' y la 'Condici&oacute;n de egreso' obligatoriamente cuando modifica un registro.";
//         }

//&& jQuery.trim($("#fecha_fin_tratF2").val())!=""
//&& jQuery.trim($("#fecha_fin_tratF2").val())!=""

    if (jQuery.trim($("#action").val()) == "M" || jQuery.trim($("#action").val()) == "R") {

        if ($("#fecha_fin_tratF2").val() != "") {
            if (jQuery.trim($("#fecha_egreso").val()) == "")
            {
                ErroresE += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha de egreso.";
            }
            else
            {
                if (!isDate($("#fecha_egreso").val().toString()))
                    ErroresE += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de egreso no tiene el formato adecuado.";
                else {
                    if (comparacionFechas($("#fecha_egreso").val().toString(), fechaActualString()) && jQuery.trim($("#fecha_fin_tratF2").val()) != "")
                        ErroresE += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de egreso no puede ser una fecha futura.";
                }
            }

            if (jQuery.trim($("#drpcond_egreso").val()) == "" && jQuery.trim($("#fecha_egreso").val()) != "")
                ErroresE += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Condici&oacute;n de egreso.";


            if (jQuery.trim($("#drpcond_egreso").val()) == "7" && jQuery.trim($("#drpmotivo_exclusion").val()) == "")
                ErroresE += "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el Motivo de Exclusi&oacute;n.";
        }
    }
    
    (ErroresN=="")? ErroresNotificacion="": ErroresNotificacion = ErroresNotificacion+ErroresN + "<br/>";
    (ErroresI=="")? ErroresIndividuo="": ErroresIndividuo = ErroresIndividuo+ErroresI + "<br/>";
    (ErroresA=="")? ErroresAntecedentes="": ErroresAntecedentes = ErroresAntecedentes+ErroresA + "<br/>";
    (ErroresM=="")? ErroresMetDiag="": ErroresMetDiag = ErroresMetDiag+ErroresM + "<br/>";
    (ErroresCl=="")? ErroresClasifica="": ErroresClasifica = ErroresClasifica+ErroresCl + "<br/>";
    (ErroresT=="")? ErroresTrat="": ErroresTrat = ErroresTrat+ErroresT + "<br/>";
    (ErroresV=="")? ErroresTB_VIH="": ErroresTB_VIH = ErroresTB_VIH+ErroresV + "<br/>";
    (ErroresCo=="")? ErroresControles="": ErroresControles = ErroresControles+ErroresCo + "<br/>";
    (ErroresCon=="")? ErroresContacto="": ErroresContacto = ErroresContacto+ErroresCon + "<br/>";
    (ErroresVi=="")? ErroresVisitas="": ErroresVisitas = ErroresVisitas+ErroresVi + "<br/>";
    (ErroresE=="")? ErroresEgreso="": ErroresEgreso = ErroresEgreso+ErroresE + "<br/>";
    Message = ErroresNotificacion + ErroresIndividuo + ErroresAntecedentes + ErroresMetDiag + ErroresClasifica + ErroresTrat + ErroresTB_VIH + ErroresControles + ErroresContacto + ErroresVisitas + ErroresEgreso;
    
    //Message= "";
    if(Message!="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#guardarPrevio").val('1');
        $("#dSummaryErrors").css('display','none');
        $('#nombreRegistra').attr('readonly', false);
        $('#nombreRegistra').attr('disabled', '');
        $('#drpTipoId').attr('readonly', false);
        $('#drpTipoId').attr('disabled', '');
        $('#no_identificador1').attr('readonly', false);
        $('#no_identificador1').attr('disabled', '');
        $('#no_identificador2').attr('readonly', false);
        $('#no_identificador2').attr('disabled', '');
        $('#no_identificador3').attr('readonly', false);
        $('#no_identificador3').attr('disabled', '');        
        $('#no_identificador').attr('readonly', false);
        $('#no_identificador').attr('disabled', '');
        
        var param = '';
        
        for(i=0; i<globalMuestrastb.length;i++){
            if(__isset(globalMuestrastb[i])){
                param+=globalMuestrastb[i][0]+"-"
                +globalMuestrastb[i][2]+"-" //Fecha toma
                +globalMuestrastb[i][3]+"-" //Fecha envio
                +globalMuestrastb[i][4];//Fecha recibo Lab
            }
        }
        $('#globalMuestrastb').val(param);
        //        alert($('#globalMuestrastb').val());
        
        var nuevo = '';
        if($('#action').val()=='M'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de Tuberculosis, \xbfdesea continuar?';
            if ($("#drpcond_egreso").val()=="1"){
                nuevo += "\n\n Advertencia: \n\ La condicion de Egreso es 'Curado', por favor verifique si la condicion de egreso esta acorde con los resultados bacteriologicos de control.";}
        }
        else {
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de Tuberculosis, \xbfdesea continuar?';
            if ($("#drpcond_egreso").val()=="1"){
                        nuevo += "\n\n Advertencia: \n\ La condicion de Egreso es 'Curado', por favor verifique si la condicion de egreso esta acorde con los resultados bacteriologicos de control.";
            }
        }
        
        if(confirm(nuevo)){
            //            alert($("#globalMuestras").val());
//            $("#globalMuestras").val($("#globalMuestras").val()+globalMuestrasSilab);
//            $("#globalPruebas").val($("#globalPruebas").val()+globalPruebasSilab);
            //            alert($("#globalMuestras").val());
            
            
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}

function pruebadeguardar() {
        $('#nombreRegistra').attr('readonly', false);
        $('#nombreRegistra').attr('disabled', '');
        $('#drpTipoId').attr('readonly', false);
        $('#drpTipoId').attr('disabled', '');
        $('#no_identificador').attr('readonly', false);
        $('#no_identificador').attr('disabled', '');
        
        $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
}


function calcularSemanaEpi(){
    if (jQuery.trim($("#fecha_inicio_sintomas").val())!='')
    {
        if(isDate($("#fecha_inicio_sintomas").val()))
        {
            var unidad = $("#fecha_inicio_sintomas").val().split("/");
            var dia = unidad[0];
            var mes = unidad[1];
            var anio = unidad[2];
            var fsintomas = new Date(anio,mes - 1,dia);
            semanaEpi = fsintomas.getWeek(0);
            
            //$("#semana_epi").html(semanaEpi);
            $("#semana_epi").val(semanaEpi);
            $("#anio").val(anio);
        }
        else{
            $("#semana_epi").val('');
            $("#anio").val('');
        }
    }
}

function Tooltip_field(){
    $('#fecha_ult_dosis').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'La fecha de la pen&uacute;ltima dosis Anti Influenza puede ser tambi&eacute;n:<br/>00/00/0000: No recibida<br/>99/99/9999: se desconoce',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    
    $('#fecha_anio_previo').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'La fecha de dosis Anti Influenza a&ntilde;o previo puede ser tambi&eacute;n:<br/>00/00/0000: No recibida<br/>99/99/9999: se desconoce',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    
    $('#datosClinicosCheckGripal').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Recuerde que si selecciona sindrome gripal el paciente no puede estar hospitalizado por IRAG',
        show: 'mouseover',
        hide: 'mouseout'
    });
    
    $('#datosClinicosCheckNeumonia').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Al seleccionar una neumon&iacute;a bacteriana, puede seleccionar opcionalmente una IRAG, solo si el tipo de evento lo aplica.',
        show: 'mouseover',
        hide: 'mouseout'
    });
    
    $('#no_identificador').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Recuerde que el formato para la c&eacute;dula paname&ntilde;a es el siguiente:<br/>xx-xxx-xxxx ej: 10-123-4567<br/> x-xxx-xxxx ej: 8-123-4567',
        show: 'mouseover',
        hide: 'mouseout'
    });
}
