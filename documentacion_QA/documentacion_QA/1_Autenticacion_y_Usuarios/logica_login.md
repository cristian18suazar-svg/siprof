La logica de esta funcionalidad se maneja principalmente desde la vista usuarios/login.php.

Para realizar esta accion, el usuario interactua con el formulario o boton en la interfaz. El javascript de la pagina se encarga de mostrar el modal o procesar los datos iniciales si es necesario.

Al momento de enviar los datos, el formulario en su atributo action esta conectado directamente con el controlador AuthController.php. Dentro de este controlador, el sistema pasa por la accion o funcion llamada la funcion principal.

Cabe recordar que este controlador incluye los archivos de configuracion como database.php para tener permisos en la base de datos, y se apoya en los modelos necesarios para interactuar con las tablas. Una vez que el controlador valida los datos, realiza la insercion, actualizacion o eliminacion correspondiente en la base de datos.

El controlador recibe correo y password por POST. Valida que ambos campos no esten vacios. Hace un SELECT en la tabla usuario buscando por Correo y Estado Activo. Si no encuentra el usuario muestra error de usuario no encontrado o inactivo. Verifica la contrasena, el sistema soporta dos tipos: las hasheadas con bcrypt se verifican con password_verify(), las de texto plano se comparan directo pero si coincide las actualiza automaticamente a bcrypt. Si la contrasena es correcta, regenera el ID de sesion con session_regenerate_id(true) y guarda en $_SESSION['usuario'] el id, nombre, correo y rol del usuario. Segun el rol redirige a admin.php, mayordomo.php o trabajador.php.

Al finalizar el proceso con exito o si ocurre algun error, el mismo controlador se encarga de hacer el redireccionamiento para devolver al usuario a la vista correspondiente, mostrando un mensaje flotante con el resultado de la operacion.
