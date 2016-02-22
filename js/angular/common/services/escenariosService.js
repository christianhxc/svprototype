sisvigServices.factory('Escenario', ['$resource', function($resource){
    return $resource('/sisvig2/api/common/escenarios.php', {}, {
        query: {method:'GET', params:{}, isArray:true}
    });
}]);