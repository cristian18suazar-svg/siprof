La logica de esta funcionalidad se maneja principalmente desde la vista dashboards/admin.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador AdminUsuarioController.php. Dentro de este controlador, el sistema pasa por la accion o funcion llamada crear.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Usuario.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la insercion correspondiente en la base de datos.

El controlador recibe nombres, apellidos, email, celular, password y rol por POST. Concatena nombres y apellidos en una variable nombre. Valida que nombre, correo y contrasena no esten vacios, si falta algo muestra alerta de campos incompletos. Verifica con el modelo que el correo no exista usando existeCorreo(), si ya existe muestra alerta de correo duplicado. Hashea la contrasena con password_hash usando PASSWORD_BCRYPT. Llama al metodo crear() del modelo que hace el INSERT en la tabla usuario con los campos Nombre, Correo, Celular, Contrasena, Niveldeacceso y Estado (siempre Activo).

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista dashboards/admin.php, mostrando un mensaje flotante con el resultado de la operacion.
