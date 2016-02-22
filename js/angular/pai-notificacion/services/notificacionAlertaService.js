sisvigServices.factory('NotificacionAlertas', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificacion/get.php');
}]);