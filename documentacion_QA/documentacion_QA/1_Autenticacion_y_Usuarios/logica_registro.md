La logica de esta funcionalidad se maneja principalmente desde la vista usuarios/registre.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador AuthController.php?accion=registrar. Dentro de este controlador, el sistema pasa por la accion o funcion llamada registrar.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en el modelo Usuario.php para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la insercion correspondiente en la base de datos.

El controlador recibe nombre, apellido, correo, telefono, password y rol por POST. Concatena nombre y apellido en nombreCompleto. Valida que nombre, apellido, email y password no esten vacios, si falta algo muestra error. Verifica con el modelo que el correo no exista usando existeCorreo(), si ya existe muestra error de correo ya registrado. Hashea la contrasena con password_hash usando PASSWORD_BCRYPT. Llama al metodo crear() del modelo que hace el INSERT en la tabla usuario con los campos Nombre, Correo, Celular, Contrasena, Niveldeacceso y Estado (siempre Activo). Si todo sale bien redirige al login con mensaje de cuenta creada exitosamente.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista correspondiente, mostrando un mensaje flotante con el resultado de la operacion.
