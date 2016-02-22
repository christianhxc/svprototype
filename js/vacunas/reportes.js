$(document).ready(function() {
    $("#form-action").val("http://173.201.187.40/sisvig2/vacunas/reportes/generareporte.php");
    $("#drpReporteTipo").change(function(){
        var tiporeporte = $( "#drpReporteTipo option:selected").val();
        var action = "";
        switch(tiporeporte) {
            case "Cobertura":
                action = "/sisvig2/vacunas/reportes/generareporte.php"
                break;
            case "Produccion":
                action = "/sisvig2/vacunas/reportes/generareporte.php"
                break;
            case "CoberturaProduccion":
                action = "/sisvig2/vacunas/reportes/generareporteproduccionvscobertura.php"
                break;
            case "Denominadores":
                action = "/sisvig2/vacunas/reportes/generareportedenominadores.php"
                break;
            case "Vacunacion":
                action = "/sisvig2/vacunas/reportes/generareportevacunacion.php"
                break;
            case "Programacion":
                action = "/sisvig2/vacunas/reportes/generareportevacunacion.php"
                break;
            case "Inasistentes":
                action = "/sisvig2/vacunas/reportes/generareportevacunacion.php"
                break;
            case "RegistroDiario":
                action = "/sisvig2/vacunas/reportes/generareporteregistrodiario.php"
                break;
            default :
                action = "/sisvig2/vacunas/reportes/generareporte.php"
        }
        $("#form-action").val(action);
    });
    $("#generar-reporte").click(function(){
        var elements = $("#formReportes").serialize();
        $("#reporte-resultado").html("Procesando...");
        $.ajax({
            type: "POST",
            url: $("#form-action").val(),
            data: elements,
            success: function(result){
                $("#reporte-resultado").html(result);
            },
            dataType: "html"
        });
    });
    $(".botonExcel").live("click", function(event) {
        $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
        $("#FormularioExportacion").attr("action", "http://173.201.187.40/sisvig2/vacunas/reportes/reporteexcel.php")
        $("#FormularioExportacion").submit();
    });
    $(".botonPdf").live("click", function(event) {
        $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
        $("#FormularioExportacion").attr("action", "http://173.201.187.40/sisvig2/vacunas/reportes/reportepdf.php")
        $("#FormularioExportacion").submit();
    });
    $("#grabar-cierre").click(function(){
        var parameters = $("#formCierre").serialize();
        $.ajax({
            type: "POST",
            url: "http://173.201.187.40/sisvig2/vacunas/reportes/grabarcierre.php",
            data: parameters,
            success: function(result){
                $("#result-cierremensual").css({ "display": "block"});
                $("#historial-cierres > tbody").prepend(result.historial);
                $("#exitoGuardar").html(result.mensaje);
            },
            dataType: "json"
        });
    })
    $(".revertir-cierre").live("click", function(){
        var id = $(this).attr("cierre-id");
        var year = $(this).attr("cierre-year");
        var month = $(this).attr("cierre-month");
        var element = $(this).parent().parent();
        if(id > 0 ){
            $.ajax({
                type: "POST",
                url: "http://173.201.187.40/sisvig2/vacunas/reportes/revertircierre.php",
                data: "id="+id+"&year="+year+"&month="+month,
                success: function(result){
                    $("#result-cierremensual").css({ "display": "block"});
                    $("#exitoGuardar").html(result.mensaje);
                    element.remove();
                },
                dataType: "json"
            });
        }
    });
    $("#drpReporteTipo").change(function(){
        var selectedValue = $(this).val();
        if(selectedValue == "Programacion" || selectedValue == "Inasistentes" || selectedValue == "RegistroDiario" || selectedValue == "ExportacionVariables"){
            $("#filtroReporte, #filtroCierre, #filtroGeografico, #filtroProvincia, #filtroDistrito, #filtroUnidad").hide();
        }else{
            $("#filtroReporte, #filtroCierre, #filtroGeografico, #filtroProvincia, #filtroDistrito, #filtroUnidad").show();
        }
    });
});