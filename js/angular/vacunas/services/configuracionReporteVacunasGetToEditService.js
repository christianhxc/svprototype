sisvigServices.factory('ConfiguracionReporteVacunasGetToEditService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/configuracion/getToEdit.php', {}, {
        query: {method:'GET', params:{}}
    });
}]);