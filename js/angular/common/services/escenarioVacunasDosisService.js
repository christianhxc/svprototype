sisvigServices.factory('EscenarioVacunasDosis', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/reportes/vacunasdosis.php', {}, {
        query: {method:'GET', params:{}, isArray:true}
    });
}]);