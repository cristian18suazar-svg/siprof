La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/control_cultivos.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador ControlCultivoController.php?accion=editar. Dentro de este controlador, el sistema pasa por la accion o funcion llamada editar.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo ControlCultivo.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la actualizacion correspondiente en la base de datos.

El controlador recibe id_control, tipo, valor, descripcion, estado, fechareporte, fechasolucion, idcultivo e idfase por POST. Valida que el ID sea mayor a 0. Prepara un array con tipo, valor, descripcion, estado, fechareporte, fechasolucion, idcultivo e idfase. Llama al metodo actualizar() del modelo que hace el UPDATE en la tabla controldecultivo actualizando Tipocontrol, Valorregistrado, Descripcion, Estado, Fechareporte, Fechasolucion, IDcultivo e IDfase WHERE IDcontroldecultivo igual al id recibido.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/control_cultivos.php, mostrando un mensaje flotante con el resultado de la operacion.
