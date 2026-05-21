La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/labores.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador LaborController.php?accion=editar. Dentro de este controlador, el sistema pasa por la accion o funcion llamada editar.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Labor.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la actualizacion correspondiente en la base de datos.

El controlador recibe id_labor, descripcion, tarea, inicio, fin, estado, id_trabajador e id_lote por POST. Valida que el ID sea mayor a 0. Prepara un array con descripcion, tarea, inicio, fin, estado, idtrabajador e idlote. Llama al metodo actualizar() del modelo que hace el UPDATE en la tabla asignaciondelabor actualizando Descripcionlabor, Tarea, Fechainicio, Fechafin, Estado, IDtrabajador e IDlote WHERE IDasignaciondelabor igual al id recibido.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/labores.php, mostrando un mensaje flotante con el resultado de la operacion.
