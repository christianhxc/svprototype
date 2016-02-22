sisvigServices.factory('ConfiguracionReporteVacunas', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/configuracion/get.php');
}]);