sisvigServices.factory('ConfiguracionReporteVacunasNew', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/configuracion/post.php');
}]);