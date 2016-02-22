sisvigApp.controller('notificacionContactosController', ['$scope', 'NotificacionContactos', 'notificacionContactoDeleteService',
    function($scope, NotificacionContactos, notificacionContactoDeleteService) {

        $scope.start = function(){
            NotificacionContactos.query({}, function(contactos) {
                $scope.contactos = contactos;
            });
        }

        $scope.start();

        $scope.borrar = function(contacto){
            if (confirm("Esta seguro que desea borrar el contacto \"" + contacto.nombre + "\"")){
                notificacionContactoDeleteService.remove({id: contacto.id_notificacion_contacto}, function(result){
                    $scope.start();
                });
            }
        }

        $scope.editar = function(contacto){
            window.location = 'contacto.php#?id='+contacto.id_notificacion_contacto;
        }
    }]);