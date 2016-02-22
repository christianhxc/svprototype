sisvigServices.factory('GruposEdadVacunas', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/denominadores.php', {}, {
        query: {method:'GET', params:{}, isArray:true}
    });
}]);