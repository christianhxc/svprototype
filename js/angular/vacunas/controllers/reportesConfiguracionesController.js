sisvigApp.controller('reportesConfiguracionesController', ['$scope', 'ConfiguracionReporteVacunas', 'ConfiguracionReporteVacunasUpdate',
    function($scope, ConfiguracionReporteVacunas, ConfiguracionReporteVacunasUpdate) {

        $scope.start = function(){
            ConfiguracionReporteVacunas.query({}, function(configuraciones) {
                $scope.configuraciones = configuraciones;
            });
        }

        $scope.start();
        $scope.borrar = function(data){
            if (confirm("Desea elminar configuración?")) {
                ConfiguracionReporteVacunasUpdate.save({id: data.id_configuracion}, function() {
                    window.location = 'configuraciones.php?info=La configuracion se elimino correctamente';
                    $scope.procesando = false;
                });
            }
        }
}]);