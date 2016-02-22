sisvigServices.factory('notificacionGrupoNewService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificaciongrupo/post.php');
}]);

sisvigServices.factory('notificacionGrupoUpdateService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificaciongrupo/put.php');
}]);

sisvigServices.factory('notificacionGrupoDeleteService', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificaciongrupo/delete.php');
}]);