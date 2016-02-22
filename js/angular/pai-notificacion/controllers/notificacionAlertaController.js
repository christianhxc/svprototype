sisvigApp.controller('notificacionAlertasController', ['$scope', 'NotificacionAlertas', 'notificacionAlertaDeleteService',
    function($scope, NotificacionAlertas, notificacionAlertaDeleteService) {

        $scope.start = function(){
            NotificacionAlertas.query({}, function(alertas) {
                $scope.alertas = alertas;
            });
        }

        $scope.start();

        $scope.borrar = function(alerta){
            if (confirm("Esta seguro que desea borrar esta alerta \"" + alerta.nombre + "\"")){
                notificacionAlertaDeleteService.remove({id: alerta.id_notificacion}, function(result){
                    $scope.start();
                });
            }
        }

        $scope.editar = function(alerta){
            window.location = 'alerta.php#?id='+alerta.id_notificacion;
        }
    }]);