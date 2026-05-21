La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/lotes.php.

Para realizar esta accion, el usuario hace clic en un boton o switch que apunta a LoteController.php?accion=toggleEstado&id=X&estado=Y.

Al momento de acceder a esta URL, el controlador detecta los parametros GET y ejecuta la funcion de cambio de estado.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Lote.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la actualizacion correspondiente en la base de datos.

El controlador recibe id y estado por GET. Valida que el ID sea mayor a 0. Llama al metodo toggleEstado() del modelo que convierte el valor numerico a texto: si estado es 1 pone Activo, si es 0 pone cancelado. Ejecuta UPDATE lote SET Estado igual al nuevo estado WHERE IDlote igual al id recibido.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/lotes.php, mostrando un mensaje flotante con el resultado de la operacion.
