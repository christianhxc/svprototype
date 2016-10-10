// LOAD
var flagMenu = 0;

$(document).ready(function() {
    $('#dSummaryErrors').hide();
    $('#divEno').hide();
    $('#divNotic').hide();
    $('#divInvCaso').hide();
    $('#divRae').hide();
    $('#divVigmor').hide();
    $('#divFluReg').hide();
    $('#divFluLab').hide();
    $('#divVicIts').hide();
    $('#divMal').hide();
    $('#divVih').hide();
    $('#divMat').hide();
    $('#divtb').hide();
    
    $("#eno").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divEno').show();
            flagMenu = 1;
        }
    });
    $("#notic").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divNotic').show();
            flagMenu = 1;
        }
    });
    $("#invCaso").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divInvCaso').show();
            flagMenu = 1;
        }
    });
    $("#rae").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divRae').show();
            flagMenu = 1;
        }
    });
    $("#vigmor").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divVigmor').show();
            flagMenu = 1;
        }
    });
    $("#mal").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divMal').show();
            flagMenu = 1;
        }
    });
    $("#vih").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divVih').show();
            flagMenu = 1;
        }
    });
    $("#fluReg").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divFluReg').show();
            flagMenu = 1;
        }
    });
    
    $("#vicIts").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divVicIts').show();
            flagMenu = 1;
        }
    });
    
    $("#mat").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divMat').show();
            flagMenu = 1;
        }
    });
    
    $("#fluLab").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divFluLab').show();
            flagMenu = 1;
        }
    });
    $("#tb").click(function(){
        if(flagMenu == 1){
            hideMenus();
            flagMenu = 0;
        }
        else{
            $('#divtb').show();
            flagMenu = 1;
        }
    });
});

// Función principal de validación de campos ingresados
function hideMenus(){
    $('#divEno').hide();
    $('#divNotic').hide();
    $('#divInvCaso').hide();
    $('#divRae').hide();
    $('#divVigmor').hide();
    $('#divFluReg').hide();
    $('#divFluLab').hide();
    $('#divVicIts').hide();
    $('#divMal').hide();
    $('#divVih').hide();
    $('#divMat').hide();
    $('#divtb').hide();
}
