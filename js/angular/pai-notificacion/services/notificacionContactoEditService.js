sisvigServices.factory('notificacionContactoNewService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacioncontacto/post.php');
}]);

sisvigServices.factory('notificacionContactoUpdateService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacioncontacto/put.php');
}]);

sisvigServices.factory('notificacionContactoDeleteService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacioncontacto/delete.php');
}]);