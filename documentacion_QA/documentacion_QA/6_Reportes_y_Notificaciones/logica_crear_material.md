La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/materiales.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador MaterialController.php?accion=crear. Dentro de este controlador, el sistema pasa por la accion o funcion llamada crear.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Material.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la insercion correspondiente en la base de datos.

El controlador recibe nombre, tipo, descripcion, cantidad, unidad, stock_minimo y precio por POST. Prepara un array con nombre, tipo, descripcion, cantidad, unidad, stock_minimo, precio y estado (siempre activo). Llama al metodo crear() del modelo que hace el INSERT en la tabla materiales con los campos Nombre, Tipo, Descripcion, Cantidad, Unidad, StockMinimo, Precio y Estado.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/materiales.php, mostrando un mensaje flotante con el resultado de la operacion.
