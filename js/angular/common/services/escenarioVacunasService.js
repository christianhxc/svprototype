sisvigServices.factory('EscenarioVacunas', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/vacunas.php', {}, {
        query: {method:'GET', params:{}, isArray:true}
    });
}]);