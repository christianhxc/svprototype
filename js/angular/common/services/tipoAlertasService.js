sisvigServices.factory('TipoAlerta', ['$resource', function($resource){
    return $resource('/sisvig2/api/common/tipoalertas.php', {}, {
        query: {method:'GET', params:{}, isArray:true}
    });
}]);