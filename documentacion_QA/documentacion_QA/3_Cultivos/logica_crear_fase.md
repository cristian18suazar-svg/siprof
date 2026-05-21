La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/fases.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador FaseController.php?accion=crear. Dentro de este controlador, el sistema pasa por la accion o funcion llamada crear.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Fase.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la insercion correspondiente en la base de datos.

El controlador recibe nombre, descripcion y duracion por POST. Valida que el nombre no este vacio, si esta vacio muestra alerta de campo requerido. Prepara un array con nombre, descripcion y duracion. Llama al metodo crear() del modelo que hace el INSERT en la tabla fase con los campos Nombre, Descripcion y Duracion.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/fases.php, mostrando un mensaje flotante con el resultado de la operacion.
