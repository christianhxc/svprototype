sisvigServices.factory('ConfiguracionReporteVacunasUpdate', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/configuracion/delete.php');
}]);