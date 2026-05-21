La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/pagos.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador PagoController.php?accion=registrar. Dentro de este controlador, el sistema pasa por la accion o funcion llamada registrar.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Pago.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la insercion correspondiente en la base de datos.

El controlador recibe fecha, monto, tipo e id_trabajador por POST. Prepara un array con fecha (por defecto fecha actual), monto, tipo, estado (siempre Pendiente) e idtrabajador. Llama al metodo crear() del modelo que hace el INSERT en la tabla pago con los campos Fechapago, Monto, Tipopago, Estado e IDtrabajador.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/pagos.php, mostrando un mensaje flotante con el resultado de la operacion.
