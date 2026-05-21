La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/labores.php.

Para realizar esta accion, el usuario hace clic en un boton que apunta a LaborController.php?accion=completar&id=X.

Al momento de acceder a esta URL, el controlador detecta el parametro GET y ejecuta la funcion de completar labor.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Labor.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la actualizacion correspondiente en la base de datos.

El controlador recibe id por GET. Valida que el ID sea mayor a 0. Llama al metodo actualizarEstado() del modelo pasando el id y el estado Completada. El modelo ejecuta UPDATE asignaciondelabor SET Estado igual a Completada WHERE IDasignaciondelabor igual al id recibido.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/labores.php, mostrando un mensaje flotante con el resultado de la operacion.
