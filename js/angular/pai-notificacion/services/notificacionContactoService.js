sisvigServices.factory('NotificacionContactos', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacioncontacto/get.php');
}]);