sisvigApp.controller('notificacionGrupoEditController', ['$scope', '$location','notificacionGrupoNewService', 'NotificacionGrupos', 'notificacionGrupoUpdateService',
    function($scope, $location, notificacionGrupoNewService, NotificacionGrupos, notificacionGrupoUpdateService) {

        $scope.form = {};
        $scope.contactos = [];
        $scope.procesando = false;
        $scope.cargando = false;

        $scope.start = function(){
            var id = $location.search().id;
            if (id){
                $scope.cargando = true;
                NotificacionGrupos.query({id:id}, function(grupo) {
                    $scope.form = grupo[0];
                    _.forEach(grupo[0].contactos, function(contacto) {
                        var key = "con" + contacto.id_notificacion_contacto;
                        $scope.contactos.push({
                            data: contacto,
                            key: key
                        });
                    });
                    $scope.cargando = false;
                });
            } else {
                $scope.form.status = 1;
            }
        };

        $scope.start();

        $scope.agregar = function(){
            var contacto = $scope.contacto.originalObject;

            var key = "con" + contacto.id_notificacion_contacto;
            var existe = _.find($scope.contactos, function(rec) {
                return rec.key == key;
            });

            if (existe){
                alert("Ya se ha seleccionado este contacto")
                return false;
            }

            $scope.contactos.push({
                key: key,
                data: contacto
            });

            $scope.contacto = null;
            $("#contactoSelected_value").val("");
        };

        $scope.borrar = function(filtro){
            if (confirm("\xbfEsta seguro de quitar el contacto del grupo?")){
                var key = "con" + filtro.data.id_notificacion_contacto;
                _.remove($scope.contactos, function(registro) {
                    return registro.key == key;
                });
            }
        }

        $scope.guardar = function(){
            if ($scope.procesando) return;
            $scope.procesando = true;

            $scope.errores = [];
            var form = $scope.form;

            if (esTextoVacio(form.nombre)) $scope.errores.push("Debe ingresar el nombre del grupo");
            if (!$scope.contactos || $scope.contactos.length <= 0)
                $scope.errores.push("Debe ingresar al menos un contacto al grupo");

            if ($scope.errores.length <= 0){
                if (!form.status) form.status = 0;

                var contactos = [];
                _.forEach($scope.contactos, function(contacto) {
                    contactos.push({id_notificacion_contacto: contacto.data.id_notificacion_contacto});
                });

                var data = { form: form, contactos: contactos };

                if (form.id_notificacion_grupo){
                    delete data.form.contactos;
                    notificacionGrupoUpdateService.save(data, function() {
                        window.location = 'index.php';
                        $scope.procesando = false;
                    });
                } else {
                    notificacionGrupoNewService.save(data, function() {
                        window.location = 'index.php';
                        $scope.procesando = false;
                    });
                }
            } else {
                $scope.procesando = false;
            }
        }
    }]);