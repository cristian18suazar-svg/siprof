La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/pagos.php.

Para realizar esta accion, el usuario hace clic en un boton que apunta a PagoController.php?accion=aprobar&id=X.

Al momento de acceder a esta URL, el controlador detecta el parametro GET y ejecuta la funcion de aprobar pago.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Pago.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la actualizacion correspondiente en la base de datos.

El controlador recibe id por GET. Valida que el ID sea mayor a 0. Llama al metodo actualizarEstado() del modelo pasando el id y el estado Pagado. El modelo ejecuta UPDATE pago SET Estado igual a Pagado WHERE IDpago igual al id recibido.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/pagos.php, mostrando un mensaje flotante con el resultado de la operacion.
