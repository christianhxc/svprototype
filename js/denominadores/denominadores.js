var fecha_del_server;

$(document).ready(function() {
    var strFechaServer=$("#fecha_server").val().split("-");
    fecha_del_server=new Date(parseInt(strFechaServer[2],10),parseInt(strFechaServer[1],10)-1,parseInt(strFechaServer[0],10))
    
	$('form input').keypress(function(e){
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)){
			validarFicha();
			e.preventDefault();
		}
	});

    calcularTotales();
    
    	$("#fecha_notificacion").datepicker({
//		showOn: 'button',buttonImage: urlprefix + 'images/calendar.png',buttonImageOnly: true,dateFormat: 'dd/mm/yy'
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                showOn: "both",
                buttonImage: urlprefix+"img/calendar.gif",
                buttonImageOnly: true,
                showAnim: "slideDown"
	});

    $( "#nombre_un" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_id.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#nombre_un").val($("<div>").html(li.selectValue).text());
            $("#id_un").val(li.extra[0]);
            globalIdPro = parseInt(li.extra[1]);
            globalIdReg = parseInt(li.extra[2]);
            globalIdDis = parseInt(li.extra[3]);
            globalIdCor = parseInt(li.extra[4]);
        },
        autoFill:false
    });

// Suma de casillas de los totales Hospitalizaciones
    var rowCount = $('#tGrupoEdad tr').length;
    $(".hospital_row").change(function(){
        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#hosp_m_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_grupo_"+i).val());
            var val2=jQuery.trim($("#hosp_f_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totalhosp_grupo_"+i).val(val1 + val2);
        }

        var totalHospm=0;
        var totalHospf=0;
        for(i=1; i<=rowCount;i++){
            totalHospm+=jQuery.trim($("#hosp_m_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_grupo_"+i).val());
            totalHospf+=jQuery.trim($("#hosp_f_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_grupo_"+i).val());
        }
        $("#totalhospm").val(totalHospm);
        $("#totalhospf").val(totalHospf);
        $("#totalhospmf").val(totalHospm+totalHospf);
    });
    // Suma de casillas de las IRAG Hospitalizaciones
    $(".hospital_row").change(function(){
        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#hosp_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_irag_grupo_"+i).val());
            var val2=jQuery.trim($("#hosp_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_irag_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totalhosp_irag_grupo_"+i).val(val1 + val2);
        }

        var totalHospm_irag=0;
        var totalHospf_irag=0;
        for(i=1; i<=rowCount;i++){
            totalHospm_irag+=jQuery.trim($("#hosp_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_irag_grupo_"+i).val());
            totalHospf_irag+=jQuery.trim($("#hosp_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_irag_grupo_"+i).val());
        }
        $("#totalhospm_irag").val(totalHospm_irag);
        $("#totalhospf_irag").val(totalHospf_irag);
        $("#totalhospmf_irag").val(totalHospm_irag+totalHospf_irag);
    });    

    // Suma de casillas de los totales UCI

    $(".uci_row").change(function(){
        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#uci_m_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_grupo_"+i).val());
            var val2=jQuery.trim($("#uci_f_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaluci_grupo_"+i).val(val1 + val2);
        }

        var totalUcim=0;
        var totalUcif=0;
        for(i=1; i<=rowCount;i++){
            totalUcim+=jQuery.trim($("#uci_m_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_grupo_"+i).val());
            totalUcif+=jQuery.trim($("#uci_f_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_grupo_"+i).val());
        }
        $("#totalucim").val(totalUcim);
        $("#totalucif").val(totalUcif);
        $("#totalucimf").val(totalUcim+totalUcif);
    });
    
    // Suma de casillas de los totales UCI IRAG
    
    $(".uci_row").change(function(){
        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#uci_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_irag_grupo_"+i).val());
            var val2=jQuery.trim($("#uci_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_irag_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaluci_irag_grupo_"+i).val(val1 + val2);
        }

        var totalUcim_irag=0;
        var totalUcif_irag=0;
        for(i=1; i<=rowCount;i++){
            totalUcim_irag+=jQuery.trim($("#uci_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_irag_grupo_"+i).val());
            totalUcif_irag+=jQuery.trim($("#uci_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_irag_grupo_"+i).val());
        }
        $("#totalucim_irag").val(totalUcim_irag);
        $("#totalucif_irag").val(totalUcif_irag);
        $("#totalucimf_irag").val(totalUcim_irag+totalUcif_irag);
    });

// Suma de casillas de los totales Defunciones

    $(".defuncion_row").change(function(){
        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#defunc_m_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_grupo_"+i).val());
            var val2=jQuery.trim($("#defunc_f_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaldefunc_grupo_"+i).val(val1 + val2);
        }

        var totalDefuncm=0;
        var totalDefuncf=0;
        for(i=1; i<=rowCount;i++){
            totalDefuncm+=jQuery.trim($("#defunc_m_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_grupo_"+i).val());
            totalDefuncf+=jQuery.trim($("#defunc_f_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_grupo_"+i).val());
        }
        $("#totaldefuncm").val(totalDefuncm);
        $("#totaldefuncf").val(totalDefuncf);
        $("#totaldefuncmf").val(totalDefuncm+totalDefuncf);

    });
    
    // Suma de casillas de los totales Defunciones IRAG
        $(".defuncion_row").change(function(){
        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#defunc_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_irag_grupo_"+i).val());
            var val2=jQuery.trim($("#defunc_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_irag_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaldefunc_irag_grupo_"+i).val(val1 + val2);
        }

        var totalDefuncm_irag=0;
        var totalDefuncf_irag=0;
        for(i=1; i<=rowCount;i++){
            totalDefuncm_irag+=jQuery.trim($("#defunc_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_irag_grupo_"+i).val());
            totalDefuncf_irag+=jQuery.trim($("#defunc_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_irag_grupo_"+i).val());
        }
        $("#totaldefuncm_irag").val(totalDefuncm_irag);
        $("#totaldefuncf_irag").val(totalDefuncf_irag);
        $("#totaldefuncmf_irag").val(totalDefuncm_irag+totalDefuncf_irag);

    });
    
});

function calcularTotales(){

    var rowCount = $('#tGrupoEdad tr').length;

        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#hosp_m_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_grupo_"+i).val());
            var val2=jQuery.trim($("#hosp_f_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totalhosp_grupo_"+i).val(val1 + val2);
        }

        var totalHospm=0;
        var totalHospf=0;
        for(i=1; i<=rowCount;i++){
            totalHospm+=jQuery.trim($("#hosp_m_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_grupo_"+i).val());
            totalHospf+=jQuery.trim($("#hosp_f_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_grupo_"+i).val());
        }
        $("#totalhospm").val(totalHospm);
        $("#totalhospf").val(totalHospf);
        $("#totalhospmf").val(totalHospm+totalHospf);
    
    // Totales de las columnas de IRAG

        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#hosp_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_irag_grupo_"+i).val());
            var val2=jQuery.trim($("#hosp_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_irag_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totalhosp_irag_grupo_"+i).val(val1 + val2);
        }

        var totalHospm_irag=0;
        var totalHospf_irag=0;
        for(i=1; i<=rowCount;i++){
            totalHospm_irag+=jQuery.trim($("#hosp_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_m_irag_grupo_"+i).val());
            totalHospf_irag+=jQuery.trim($("#hosp_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#hosp_f_irag_grupo_"+i).val());
        }
        $("#totalhospm_irag").val(totalHospm_irag);
        $("#totalhospf_irag").val(totalHospf_irag);
        $("#totalhospmf_irag").val(totalHospm_irag+totalHospf_irag);

    // Totales de las columnas de UCI normal

        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#uci_m_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_grupo_"+i).val());
            var val2=jQuery.trim($("#uci_f_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaluci_grupo_"+i).val(val1 + val2);
        }

        var totalUcim=0;
        var totalUcif=0;
        for(i=1; i<=rowCount;i++){
            totalUcim+=jQuery.trim($("#uci_m_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_grupo_"+i).val());
            totalUcif+=jQuery.trim($("#uci_f_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_grupo_"+i).val());
        }
        $("#totalucim").val(totalUcim);
        $("#totalucif").val(totalUcif);
        $("#totalucimf").val(totalUcim+totalUcif);

// Totales de las columnas de UCI IRAG

        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#uci_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_irag_grupo_"+i).val());
            var val2=jQuery.trim($("#uci_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_irag_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaluci_irag_grupo_"+i).val(val1 + val2);
        }

        var totalUcim_irag=0;
        var totalUcif_irag=0;
        for(i=1; i<=rowCount;i++){
            totalUcim_irag+=jQuery.trim($("#uci_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_m_irag_grupo_"+i).val());
            totalUcif_irag+=jQuery.trim($("#uci_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#uci_f_irag_grupo_"+i).val());
        }
        $("#totalucim_irag").val(totalUcim_irag);
        $("#totalucif_irag").val(totalUcif_irag);
        $("#totalucimf_irag").val(totalUcim_irag+totalUcif_irag);

// Totales de las columnas de Defunciones totales


        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#defunc_m_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_grupo_"+i).val());
            var val2=jQuery.trim($("#defunc_f_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaldefunc_grupo_"+i).val(val1 + val2);
        }

        var totalDefuncm=0;
        var totalDefuncf=0;
        for(i=1; i<=rowCount;i++){
            totalDefuncm+=jQuery.trim($("#defunc_m_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_grupo_"+i).val());
            totalDefuncf+=jQuery.trim($("#defunc_f_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_grupo_"+i).val());
        }
        $("#totaldefuncm").val(totalDefuncm);
        $("#totaldefuncf").val(totalDefuncf);
        $("#totaldefuncmf").val(totalDefuncm+totalDefuncf);

// Totales de las columnas de Defunciones IRAG

        for(i=1; i<=rowCount;i++){
            var val1=jQuery.trim($("#defunc_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_irag_grupo_"+i).val());
            var val2=jQuery.trim($("#defunc_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_irag_grupo_"+i).val());
            if((val1+val2)!=0)
            $("#totaldefunc_irag_grupo_"+i).val(val1 + val2);
        }

        var totalDefuncm_irag=0;
        var totalDefuncf_irag=0;
        for(i=1; i<=rowCount;i++){
            totalDefuncm_irag+=jQuery.trim($("#defunc_m_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_m_irag_grupo_"+i).val());
            totalDefuncf_irag+=jQuery.trim($("#defunc_f_irag_grupo_"+i).val())=="" ? 0 : parseInt($("#defunc_f_irag_grupo_"+i).val());
        }
        $("#totaldefuncm_irag").val(totalDefuncm_irag);
        $("#totaldefuncf_irag").val(totalDefuncf_irag);
        $("#totaldefuncmf_irag").val(totalDefuncm_irag+totalDefuncf_irag);

    
}

function validarFicha(){

    if ($("#hdGuardando").val() == 1){
        return;
    }

	var General = "";	
        
    if($("#semana_epidemiologica").val()==0){
        General += "- Debe ingresar la semana epidemiológica a reportar.<br>";
    }

    if($("#anio_epidemiologico").val()==0){
        General += "- Debe ingresar el año a reportar.<br>";
    }

    if($("#id_un").val()==0 || $("#id_un").val()==""){
        General += "- Debe seleccionar el area de salud.<br>";
    }

    if(jQuery.trim($('#fecha_notificacion').val())==""){
        General += "- Debe ingresar La fecha de notificacion<br>";
    }
    
    if(isDate($('#fecha_notificacion').val()) && $('#fecha_notificacion').datepicker('getDate')>fecha_del_server){
        General += "- La fecha de notificacion no puede ser mayor que la fecha de hoy<br>";
    }

    if(jQuery.trim($("#responsable").val())==""){
        General += "- Debe ingresar el nombre del responsable<br>";
    }
    
	if (General != ""){
		$('#dSummaryErrors').show();
		$('#pSummaryErrors').html(General);
	}else{
            // Activar flag para evitar el doble click
            $("#hdGuardando").val(1);
            
            $('form:frmContenido').submit();
	}
}
