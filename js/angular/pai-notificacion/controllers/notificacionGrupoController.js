sisvigApp.controller('notificacionGruposController', ['$scope', 'NotificacionGrupos', 'notificacionGrupoDeleteService',
    function($scope, NotificacionGrupos, notificacionGrupoDeleteService) {

        $scope.start = function(){
            NotificacionGrupos.query({}, function(grupos) {
                $scope.grupos = grupos;
            });
        }

        $scope.start();

        $scope.borrar = function(grupo){
            if (confirm("Esta seguro que desea borrar el grupo \"" + grupo.nombre + "\"")){
                notificacionGrupoDeleteService.remove({id: grupo.id_notificacion_grupo}, function(result){
                    $scope.start();
                });
            }
        }

        $scope.editar = function(grupo){
            window.location = 'grupo.php#?id='+grupo.id_notificacion_grupo;
        }
    }]);