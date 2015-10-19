
// Relaciona los calendarios con los campos
//$(function() {$( "#fecha_nacimiento" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
//$(function() {$( "#fecha_toma" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
//$(function() {$( "#fecha_recepcion" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});


// Reemplaza htmlentities por su equivalente de javascript
function replace(objetivo)
{
    if (typeof objetivo === "undefined") 
        return objetivo;
    objetivo = objetivo.toString().replace(/&aacute;/,"\xe1");
    objetivo = objetivo.toString().replace(/&eacute;/,"\xe9");
    objetivo = objetivo.toString().replace(/&iacute;/,"\xed");
    objetivo = objetivo.toString().replace(/&oacute;/,"\xf3");
    objetivo = objetivo.toString().replace(/&uacute;/,"\xfa");
    objetivo = objetivo.toString().replace(/&Aacute;/,"\xc1");
    objetivo = objetivo.toString().replace(/&Eacute;/,"\xc9");
    objetivo = objetivo.toString().replace(/&Iacute;/,"\xcd");
    objetivo = objetivo.toString().replace(/&Oacute;/,"\xd3");
    objetivo = objetivo.toString().replace(/&Uacute;/,"\xda");
    objetivo = objetivo.toString().replace(/&ntilde;/,"\xf1");
    objetivo = objetivo.toString().replace(/&Ntilde;/,"\xd1");
    return objetivo;
}

// Retorna true si es válido
function compararFechasMuestra(a, b)
{
    var partesa = a.toString().split("/");
    var uno = new Date(partesa[2], parseInt(partesa[1])-1, partesa[0]);

    var partesb = b.toString().split("/");
    var dos = new Date(partesb[2], parseInt(partesb[1])-1, partesb[0]);

    if(uno > dos)
        return false;
    else
        return true;
}

// Diferencia en dias de fechas
function diferenciaFechas(fechaA, fechaB)
{
    var partesA = fechaA.toString().split("/");
    var fecha1 = new Date(partesA[2], parseInt(partesA[1])-1, partesA[0]);

    var partesB = fechaB.toString().split("/");
    var fecha2 = new Date(partesB[2], parseInt(partesB[1])-1, partesB[0]);

    var milisegundos = (fecha2-fecha1); // difference in milliseconds
    var segundos = milisegundos/1000;
    var minutos = segundos/60;
    var horas = minutos/60;
    var dias = horas/24;
    return dias;
}

function  validarFechas(fecha2,fecha){
        var xDia=fecha.substring(0, 2);
        var xMes=fecha.substring(3, 5);
        var xAnio=fecha.substring(6, 10);
        var yDia=fecha2.substring(0, 2);
        var yMes=fecha2.substring(3, 5);
        var yAnio=fecha2.substring(6, 10);
        if (xAnio > yAnio){
            return(true);
        }else{
            if (xAnio == yAnio){
                if (xMes > yMes)
                    return(true);
                if (xMes == yMes){
                    if (xDia >= yDia)
                        return(true);
                    else
                        return(false);
                }else
                    return(false);
            }else
                return(false);
        }
    }

// Retorna true si es válido
function compararFechas(otraString){
    var actual = new Date();
    var partes = otraString.toString().split("/");
    var otra = new Date(partes[2], parseInt(partes[1])-1, partes[0]);

    if(otra > actual)
        return false;
    else
        return true;
}

// Retorna true si es válido
function comparacionFechas(string1, string2){
    var partes1 = string1.toString().split("/");
    var otra1 = new Date(partes1[2], parseInt(partes1[1])-1, partes1[0]);
    
    var partes2 = string2.toString().split("/");
    var otra2 = new Date(partes2[2], parseInt(partes2[1])-1, partes2[0]);

    if(otra1 > otra2)
        return true;
    else
        return false;
}

function fechaActualString()
{
    var date = new Date();
    return (date.getDate()+"/"+(date.getMonth()+1) +"/"+date.getFullYear());
}

function fechaString(date)
{
    return (date.getDate()+"/"+(date.getMonth()+1) +"/"+date.getFullYear());
}

// Insertar 0´s necesarios para que cifra tenga 7 dígitos
function completarCifra(dato)
{
    var diferencia = 7 - (dato.toString()).length;
    var stringDato = dato.toString();
    var ceros = '';

    for(i=0; i<diferencia; i++)
        ceros +='0';
    return (ceros + stringDato);
//return stringDato;
}


// Calcula la edad a partir de la fecha de nacimiento
function calcularEdad()
{
    var edad=0;
    var fechaActual = new Date();

    if(jQuery.trim($("#fecha_nacimiento").val())!="")
    {
        if(isDate($("#fecha_nacimiento").val()))
        {
            if(compararFechas($("#fecha_nacimiento").val()))
            {
                var fechaNacimiento = $("#fecha_nacimiento").val().toString().split("/");
                var diaNac = fechaNacimiento[0];
                var mesNac = fechaNacimiento[1];
                var anioNac = fechaNacimiento[2];

                var anioAct = fechaActual.getFullYear();
                var mesAct = fechaActual.getMonth()+1;
                var diaAct = fechaActual.getDate();

                // Calcula años
                edad = anioAct - anioNac;

                if(edad!=0)
                {
                    if (mesNac > mesAct )
                        edad--;
                    else if(mesNac == mesAct)
                    {
                        if(diaAct < diaNac)
                            edad--;
                    }
                }

                // 0 años
                if(edad>0)
                {
                    $("#edad").val(edad);
                    $("#drptipo_edad").val(3);
                }
                else
                {
                    // Comparar meses
                    if(mesAct == mesNac)
                    {
                        if(diaAct == diaNac)
                        {
                            // 0 días
                            $("#edad").val(0);
                            $("#drptipo_edad").val(1);
                            $("#fecha_nacimiento").val(fechaActualString());
                        }
                        else if(diaNac < diaAct)
                        {
                            // hoy menor que fecha actual días
                            $("#edad").val(diaAct - diaNac);
                            $("#drptipo_edad").val(1);
                        }
                    }
                    else if (mesNac <mesAct)
                    {
                        if(diaNac < diaAct || diaNac == diaAct)
                        {
                            $("#edad").val(mesAct - mesNac);
                            $("#drptipo_edad").val(2);
                        }
                        else
                        {
                            if((mesAct - mesNac - 1)==0){
                                $("#edad").val(30 - diaNac + diaAct);
                                $("#drptipo_edad").val(1);
                            }
                            else
                            {
                                $("#edad").val(mesAct - mesNac - 1);
                                $("#drptipo_edad").val(2);
                            }
                        }
                    }
                }
            }
            else
            {
                $("#fecha_nacimiento").val(fechaActualString());
                calcularEdad();
            }
        }
        else
        {
                $("#fecha_nacimiento").val(fechaActualString());
                calcularEdad();
        }
        $("#drpsexo").focus();
    }
}



// Calcula la fecha de nacimiento a partir de la edad
function calcularFechaNacimiento()
{
    var actual = new Date();
    var yrActual = actual.getFullYear();
    var dActual = actual.getDate();
    var mActual = actual.getMonth()+1;

    if(jQuery.trim($("#edad").val())!="")
    {

        // Años
        if($("#drptipo_edad").val()==3)
        {
            if(jQuery.trim($("#edad").val())!="")
            {
                yrActual -= parseInt($("#edad").val());
                $("#fecha_nacimiento").val(ponCero(dActual.toString())+"/" +ponCero(mActual.toString()) +"/"+ yrActual.toString());
            }
        }
        // Meses
        else if($("#drptipo_edad").val()==2)
        {
            if(jQuery.trim($("#edad").val())!="")
            {
                var edadMeses = parseInt($("#edad").val());

                if(edadMeses > mActual)
                {
                    yrActual = yrActual -1;
                    mActual = 12 - (edadMeses - mActual);
                }
                else if(edadMeses == mActual)
                {
                    mActual= 12;
                    yrActual--;
                }
                else
                {
                     mActual= mActual - edadMeses
                }

                $("#fecha_nacimiento").val(ponCero(dActual.toString())+"/" +ponCero(mActual.toString()) +"/"+ yrActual.toString());
            }
        }
        // Dias
        else
        {
            if(jQuery.trim($("#edad").val())!="")
            {
                var edadDias = parseInt($("#edad").val());

                if(edadDias < dActual)
                    dActual -= edadDias;
                else if (edadDias==dActual)
                {
                    dActual = 30;
                    if(mActual==1)
                    {
                        mActual=12;
                        yrActual--;
                    }
                    else
                        mActual--;
                }
                else
                {
                    dActual = 30 - (edadDias - dActual);
                    if(mActual==1)
                    {
                        mActual=12;
                        yrActual--;
                    }
                    else
                        mActual--;
                }
                $("#fecha_nacimiento").val(ponCero(dActual.toString())+"/" +ponCero(mActual.toString()) +"/"+ yrActual.toString());
            }
        }
        $("#drpsexo").focus();
    }
}

// Calcula la edad a partir de la fecha de nacimiento
function calcularEdadVih(fechaActual)
{
    var edad=0;

    if(jQuery.trim($("#fecha_nacimiento").val())!="")
    {
        if(isDate($("#fecha_nacimiento").val()))
        {
            if(compararFechas($("#fecha_nacimiento").val()))
            {
                var fechaNacimiento = $("#fecha_nacimiento").val().toString().split("/");
                var diaNac = fechaNacimiento[0];
                var mesNac = fechaNacimiento[1];
                var anioNac = fechaNacimiento[2];
                
                var fecActual = fechaActual.split("/");
                var diaAct = fecActual[0];
                var mesAct = fecActual[1];
                var anioAct = fecActual[2];

                // Calcula años
                edad = anioAct - anioNac;

                if(edad!=0)
                {
                    if (mesNac > mesAct )
                        edad--;
                    else if(mesNac == mesAct)
                    {
                        if(diaAct < diaNac)
                            edad--;
                    }
                }
                
                return edad;
            }
        }
    }
}


// Límites para cada tipo de edad
function validarEdad()
{
    if($("#drptipo_edad").val()==1)
    {
        if(parseInt($("#edad").val())>30)
        {
            $("#edad").focus();
            $("#edad").val(30);
        }
    }
    else if($("#drptipo_edad").val()==2)
    {
        if(parseInt($("#edad").val())>11)
        {
            $("#edad").focus();
            $("#edad").val(11);
        }
    }
    else if($("#drptipo_edad").val()==3)
    {
        if(parseInt($("#edad").val())>115)
        {
            $("#edad").focus();
            $("#edad").val(115);
        }
    }
    calcularFechaNacimiento();
}

// Límites para cada tipo de edad
function validarEdadPop()
{
    if($("#drpPopTipo").val()==0)
    {
        if(parseInt($("#ed").val())>115)
        {
            $("#ed").focus();
            $("#ed").val(115);
        }

        if(parseInt($("#ed2").val())>115)
        {
            $("#ed2").focus();
            $("#ed2").val(115);
        }
    }
    else if($("#drpPopTipo").val()==1)
    {
        if(parseInt($("#ed").val())>30)
        {
            $("#ed").focus();
            $("#ed").val(30);
        }

        if(parseInt($("#ed2").val())>30)
        {
            $("#ed2").focus();
            $("#ed2").val(30);
        }
    }
    else if($("#drpPopTipo").val()==2)
    {
        if(parseInt($("#ed").val())>11)
        {
            $("#ed").focus();
            $("#ed").val(11);
        }

        if(parseInt($("#ed2").val())>11)
        {
            $("#ed2").focus();
            $("#ed2").val(11);
        }
    }
    else if($("#drpPopTipo").val()==3)
    {
        if(parseInt($("#ed").val())>115)
        {
            $("#ed").focus();
            $("#ed").val(115);
        }

        if(parseInt($("#ed2").val())>115)
        {
            $("#ed2").focus();
            $("#ed2").val(115);
        }
    }
}
//fecha1: VIH fecha2: Sida
function calcularTiempoDosFechas(fecha1, fecha2){
        var fecha_1 = fecha1.split('/');
        var fecha_2 = fecha2.split('/');
        //calcular la diferencia
        var dif = FechaDif(fecha_1[0], fecha_1[1], fecha_1[2], fecha_2[0], fecha_2[1], fecha_2[2]);
        //mostrar la diferencia
        return dif + ' dias';
}
		
function FechaDif(dia1,mes1,anio1,dia2,mes2,anio2){
	/* Meses con 31: Enero(1) Marzo(3) Mayo(5) Julio(7) Agosto(8) Octubre(10) Diciembre(12)
	*  Meses con 30:Abril(4) Junio(6) Setiembre(9) Noviembre(11)
	*  Meses con 28:Febrero(2)
	*/
	var dias1,dias2,dif;
	//convertir a numeros
        dia1 = parseInt(dia1,10);
        mes1 = parseInt(mes1,10);
        anio1 = parseInt(anio1,10);
        dia2 = parseInt(dia2,10);
        mes2 = parseInt(mes2,10);
        anio2 = parseInt(anio2,10);

	//Chequear valores.
	if((mes1>12)||(mes2>12)){return -1;}
	
	if((mes1==1)||(mes1==3)||(mes1==5)||(mes1==7)||(mes1==8)||(mes1==10)||(mes1==12)){
		if(dia1>31){
			return -1;}
        }
	if((mes2==1)||(mes2==3)||(mes2==5)||(mes2==7)||(mes2==8)||(mes2==10)||(mes2==12)){
		if(dia2>31){
			return -1;}
        }
	if((mes1==4)||(mes1==6)||(mes1==9)||(mes1==11)){
		if(dia1>30){
			return -1;}
        }
	if((mes2==4)||(mes2==6)||(mes2==9)||(mes2==11)){
		if(dia2>30){
			return -1;}
        }
	if(mes1==2 && dia1>29){
                return -1;}
	if(mes2==2 && dia2>29){
                return -1;}
	
	dias1 = FechaADias(dia1,mes1,anio1);
	dias2 = FechaADias(dia2,mes2,anio2);
	//devolver la diferencia positiva
	dif = dias2 - dias1;
	if(dif<0){
		return ((-1*dif));}
	return dif;
}
		
function FechaADias(dia, mes, anno){
        /*Devuelve la cantidad de días desde el 1/01/1904
        *	No verifica datos. Llamada desde FechaDif()
        *	intervalo permitido: 1904-2099
        */
	
        dia = parseInt(dia,10);
        mes = parseInt(mes,10);
        anno = parseInt(anno,10);
	var cant_bic,cant_annos,cant_dias, no_es_bic;

	
	//verificar la cantidad de biciestos en el periodo (div entera)
	//+1 p/contar 1904
	cant_bic = parseInt((anno-1904)/4 + 1);
	no_es_bic = parseInt((anno % 4));
	//calcular dias transcurridos hasta el 31 de dic del año anterior
	cant_annos = parseInt(anno-1904);
	cant_dias = parseInt(cant_annos*365 + cant_bic);
	
	//calcular dias transcurridoes desde el 31 de dic del año anterior
	//hasta el mes anterior al ingresado
	var i;
	for(i=1;i<=mes;i++){
	if((i==1)||(i==3)||(i==5)||(i==7)||(i==8)||(i==10)||(i==12)){
	cant_dias+=31;}
	if((i==4)||(i==6)||(i==9)||(i==11)){
	cant_dias+=30;}
	if(i==2)
	{
	if(no_es_bic){
		cant_dias+=28;}
	else{
		cant_dias+=29;}
	}
	}	
	//sumarle los dias transcurridos en el mes
	cant_dias+=dia;
	return cant_dias;
}

function validarCedula(cedula){  
    //formato valido xx-xxx-xxxx
    var partes = cedula.toString().split("-");
    if(partes.length == 3){
        var parte1 = partes[0];
        var parte2 = partes[1];
        var parte3 = partes[2];
        if(!isNumber(parte1))
            return false;
        if(!isNumber(parte2))
            return false;
        if(!isNumber(parte3))
            return false;
        if(parte1.toString().length > 2)
            return false;
        else if(parte2.toString().length > 4)
            return false;
        else if(parte3.toString().length > 4)
            return false;
        else 
            return true;
    }
    return false;
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function setHoras(hora) {
    if (hora > 12)
        return 12;
}

function setMinutos(min) {
    if (min > 59)
        return 59;
}