sisvigApp.controller('notificacionContactoEditController', ['$scope', '$location','notificacionContactoNewService', 'NotificacionContactos', 'notificacionContactoUpdateService',
    function($scope, $location, notificacionContactoNewService, NotificacionContactos, notificacionContactoUpdateService) {

        $scope.form = {};
        $scope.procesando = false;
        $scope.cargando = false;

        $scope.start = function(){
            var id = $location.search().id;
            if (id){
                $scope.cargando = true;
                NotificacionContactos.query({id:id}, function(contacto) {
                    $scope.form = contacto[0];
                    $scope.cargando = false;
                });
            } else {
                $scope.form.status = 1;
            }
        };

        $scope.start();

        $scope.guardar = function(){
            if ($scope.procesando) return;
            $scope.procesando = true;

            $scope.errores = [];
            var form = $scope.form;

            if (esTextoVacio(form.nombre)) $scope.errores.push("Debe ingresar el nombre del contacto");
            if (esTextoVacio(form.email)) $scope.errores.push("Debe ingresar el email del contacto");
            if (!esEmail(form.email)) $scope.errores.push("Debe ingresar un email valido");
            if (esTextoVacio(form.telefono)) $scope.errores.push("Debe ingresar el telefono del contacto");

            if ($scope.errores.length <= 0){
                if (!form.status) form.status = 0;

                var data = { form: form };

                if (form.id_notificacion_contacto){
                    notificacionContactoUpdateService.save(data, function() {
                        window.location = 'index.php';
                        $scope.procesando = false;
                    });
                } else {
                    notificacionContactoNewService.save(data, function() {
                        window.location = 'index.php';
                        $scope.procesando = false;
                    });
                }
            } else {
                $scope.procesando = false;
            }
        }
    }]);