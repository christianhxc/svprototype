sisvigApp.controller('reportesController', ['$scope', 'Escenario', 'EscenarioVacunas', 'EscenarioVacunasDosis', 'GruposEdadVacunas', 'ConfiguracionReporteVacunas', 'ConfiguracionReporteVacunasNew', 'ConfiguracionReporteVacunasUpdate', 'ConfiguracionReporteVacunasGetToEditService',
    function($scope, Escenario, EscenarioVacunas, EscenarioVacunasDosis, GruposEdadVacunas, ConfiguracionReporteVacunas, ConfiguracionReporteVacunasNew, ConfiguracionReporteVacunasUpdate, ConfiguracionReporteVacunasGetToEditService) {

        $scope.form = {};
        $scope.vacunas = [];
        $scope.procesando = false;

        $scope.start = function(){
            Escenario.query({}, function(escenarios) {
                $scope.escenarios = escenarios;
            });

            GruposEdadVacunas.query({}, function(gruposEdad) {
                $scope.gruposEdad = gruposEdad;
            });

            var id = getParameterByName("id");
            if(id != null){
                ConfiguracionReporteVacunasGetToEditService.query({id: id}, function(response) {
                    var cabecera = response.cabecera[0];
                    var detalle = response.detalle;
                    if(cabecera != null){
                        $scope.form.nombre = cabecera.nombre;
                        $scope.form.id_grupo_edad = cabecera.id_grupo_edad;
                        $scope.form.id_grupo_especial = cabecera.id_grupo_especial;
                        $scope.form.tasa = cabecera.tasa;
                        $scope.vacunas = detalle;
                    }
                });
            }

        }

        $scope.start();

        $scope.cargarVacunas = function(){
            if (!$scope.escenario) {
                $scope.catVacunas = null;
                $scope.catDosis = null;
                return false;
            }

            EscenarioVacunas.query({ escenario: $scope.escenario.id_esquema }, function(vacunas) {
                $scope.catVacunas = vacunas;
            });
        }

        $scope.cargarDosis = function(){
            if (!$scope.escenario || !$scope.vacuna) {
                $scope.catDosis = null;
                return false;
            }

            EscenarioVacunasDosis.query({ escenario: $scope.escenario.id_esquema, vacuna: $scope.vacuna.id_vacuna }, function(dosis) {
                $scope.catDosis = dosis;
            });
        }

        $scope.agregarVacuna = function(){
            if (!$scope.vacuna || !$scope.dosis){
                alert("Debe seleccionar una vacuna y una dosis");
                return false;
            }

            var key = "vac" + $scope.dosis.id_vacuna + "_do" + $scope.dosis.id_dosis;
            var existe = _.find($scope.vacunas, function(vacuna) {
                return vacuna.key == key;
            });

            if (existe){
                alert("Ya se ha seleccionado esta vacuna")
                return false;
            }

            $scope.vacunas.push({
                key: key,
                dosis: $scope.dosis
            });
        }

        $scope.borrarVacuna = function(filtro){
            if (confirm("\xbfEsta seguro de borrar la vacuna/dosis para la configuracion?")){
                var key = "vac" + filtro.dosis.id_vacuna + "_do" + filtro.dosis.id_dosis;
                _.remove($scope.vacunas, function(registro) {
                    return registro.key == key;
                });
            }
        }

        $scope.guardar = function(){
            if ($scope.procesando) return;
            $scope.procesando = true;

            $scope.errores = [];
            var form = $scope.form;

            if (esTextoVacio(form.nombre)) $scope.errores.push("Debe ingresar el nombre del reporte");
            if ($scope.vacunas.length <= 0) $scope.errores.push("Debe agregar al menos una vacuna/dosis");
            if (!$scope.grupoEdad) $scope.errores.push("Debe seleccionar un denominador");
            if (!form.tasa) $scope.errores.push("Debe seleccionar si desea incluir la tasa de desercion");

            if ($scope.errores.length <= 0){
                form.id_grupo_edad = $scope.grupoEdad.id_grupo_edad;
                form.id_grupo_especial = $scope.grupoEdad.id_grupo_especial;

                var data = { form: form, vacunas: [] };
                _.forEach($scope.vacunas, function(vacuna) {
                    data.vacunas.push({
                        id_vacuna: vacuna.dosis.id_vacuna,
                        id_dosis: vacuna.dosis.id_dosis
                    });
                });

                ConfiguracionReporteVacunasNew.save(data, function() {
                    window.location = 'configuraciones.php?info=La configuracion se creo correctamente';
                    $scope.procesando = false;
                });
            } else {
                $scope.procesando = false;
            }
        }
}]);
function getParameterByName(name){
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}