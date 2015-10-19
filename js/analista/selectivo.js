var pagina = 1;

// LOAD
$(document).ready(function()
{
    traerMuestras(pagina);
});


function traerMuestras(pagina)
{
       $.ajax({
       type: "POST",
       url: urlprefix + 'js/dynamic/selectivoMuestras.php',
       data: "id="+$("#informe").val()+"&pagina="+pagina,
       success: function(data){
           $("#muestras").html(data);
       }
     });
}

function devolverVentanilla(muestra)
{
    if(confirm("\xbfEst\xe1 seguro que desea devolver esta muestra a ventanilla?"))
    {
       $.ajax({
           type: "POST",
           url: urlprefix + 'js/dynamic/devolver.php',
           data: "i="+ $("#informe").val()+ "&m="+ muestra,
           success: function(data)
           {
                if(data==1)
                {
                    var a = jQuery.trim($("#g"+muestra).html());
                    alert("Muestra C\xf3digo Global: "+jQuery.trim($("#g"+muestra).text())+ " C\xf3digo Correlativo: "+jQuery.trim($("#c"+muestra).html())+" devuelta a ventanilla");

                    $.ajax({
                        type: "POST",
                        url: urlprefix + 'js/dynamic/cantidad.php',
                        data: "i="+ $("#informe").val()+ "&m="+ muestra,
                        success: function(data)
                        {
                        }
                    });

                    traerMuestras(pagina);
                }
                else
                {
                   alert("Por favor intente nuevamente.");
                }
           }
        });        
    }
}

function recibirMuestra(muestra)
{

}