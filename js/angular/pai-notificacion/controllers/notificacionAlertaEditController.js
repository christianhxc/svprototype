sisvigApp.controller('notificacionAlertaEditController', ['$scope', '$location','notificacionAlertaNewService',
    'NotificacionAlertas', 'notificacionAlertaUpdateService', 'TipoAlerta', 'Escenario', 'EscenarioVacunas',
    function($scope, $location, notificacionAlertaNewService, NotificacionAlertas,
             notificacionAlertaUpdateService, TipoAlerta, Escenario, EscenarioVacunas) {

        $scope.form = {};
        $scope.contactos = [];
        $scope.tipos = [];
        $scope.procesando = false;
        $scope.cargando = false;

        $scope.vacunas = [];

        $scope.start = function(){
            var id = $location.search().id;

            Escenario.query({}, function(escenarios) {
                $scope.escenarios = escenarios;
            });

            TipoAlerta.query({}, function(tipos) {
                $scope.tipos = tipos;
            });

            if (id){
                $scope.cargando = true;
                NotificacionAlertas.query({id:id}, function(alerta) {
                    $scope.form = alerta[0];
                    $scope.tipo = $scope.form.id_notificacion_tipo;
                    $scope.contactoInit = $scope.form.contacto;
                    $scope.grupoInit = $scope.form.grupo;
                    $scope.insumoInit = $scope.form.insumo;
                    $scope.vacunaInit = $scope.form.vacuna;
                    $scope.cargando = false;
                });
            } else {
                $scope.form.status = 1;
            }

            $("#fecha_envio").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                showOn: "both",
                buttonImage: urlprefix+"img/calendar.gif",
                buttonImageOnly: true,
                showAnim: "slideDown"
            });
        };

        $scope.start();

        $scope.cargarVacunas = function(){
            if (!$scope.escenario) {
                $scope.catVacunas = null;
                $scope.catDosis = null;
                return false;
            }

            EscenarioVacunas.query({ escenario: $scope.escenario.id_esquema }, function(vacunas) {
                $scope.catVacunas = vacunas;
            });
        }

        $scope.guardar = function(){
            if ($scope.procesando) return;
            $scope.procesando = true;

            $scope.errores = [];
            var form = $scope.form;
            form.fecha_envio = $("#fecha_envio").val();

            if (!$scope.tipo) $scope.errores.push("Debe seleccionar un tipo de alerta");
            if (esTextoVacio(form.nombre)) $scope.errores.push("Debe ingresar el nombre de la alerta");

            if ($scope.tipo && $scope.tipo != 1) {
                if (!$scope.contacto && !form.id_notificacion_contacto && !$scope.grupo && !form.id_notificacion_grupo)
                    $scope.errores.push("Debe seleccionar un contacto o un grupo");
            }

            if ($scope.tipo && $scope.tipo == 2) {
                if (!$scope.insumo && !form.id_insumo) $scope.errores.push("Debe seleccionar un insumo");
            }

            if ($scope.tipo && $scope.tipo == 1) {
                if (!$scope.vacuna && !form.id_vacuna) $scope.errores.push("Debe seleccionar una vacuna");
            }

            if ($scope.tipo && $scope.tipo == 3) {
                if (esTextoVacio(form.no_lote)) $scope.errores.push("Debe ingresar el numero de lote");
                if (esTextoVacio(form.anticipacion_dias)) $scope.errores.push("Debe ingresar el numero de dias de anticipacion");
            }

            if ($scope.tipo && $scope.tipo == 4) {
                if (!form.fecha_envio) $scope.errores.push("Debe ingresar una fecha de envio");
                if (form.fecha_envio && !isDate(form.fecha_envio)) $scope.errores.push("Debe ingresar una fecha de envio valida");
                if (esTextoVacio(form.mensaje)) $scope.errores.push("Debe ingresar un mensaje");
            }

            if ($scope.errores.length <= 0){
                form.id_notificacion_tipo = $scope.tipo;
                if (!form.status) form.status = 0;
                if ($scope.contacto) form.id_notificacion_contacto = $scope.contacto.originalObject.id_notificacion_contacto;
                if ($scope.grupo) form.id_notificacion_grupo = $scope.grupo.originalObject.id_notificacion_grupo;
                if ($scope.insumo) form.id_insumo = $scope.insumo.originalObject.id_insumo;
                if ($scope.vacuna) form.id_vacuna = $scope.vacuna.originalObject.id_vacuna;

                delete form.tipo;
                delete form.contacto;
                delete form.grupo;
                delete form.insumo;
                delete form.vacuna;

                var data = { form: form };

                if (form.id_notificacion){
                    notificacionAlertaUpdateService.save(data, function() {
                        window.location = 'index.php';
                        $scope.procesando = false;
                    });
                } else {
                    notificacionAlertaNewService.save(data, function() {
                        window.location = 'index.php';
                        $scope.procesando = false;
                    });
                }
            } else {
                $scope.procesando = false;
            }
        }
    }]);