sisvigServices.factory('notificacionAlertaNewService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacion/post.php');
}]);

sisvigServices.factory('notificacionAlertaUpdateService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacion/put.php');
}]);

sisvigServices.factory('notificacionAlertaDeleteService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacion/delete.php');
}]);