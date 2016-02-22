sisvigServices.factory('NotificacionGrupos', ['$resource', function($resource){
    return $resource('/sisvig2/api/modulos/vacunas/notificaciongrupo/get.php');
}]);