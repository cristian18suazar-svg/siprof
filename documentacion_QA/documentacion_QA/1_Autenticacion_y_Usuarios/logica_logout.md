La logica de esta funcionalidad se maneja principalmente desde cualquier dashboard.

Para realizar esta accion, el usuario hace clic en el enlace de cerrar sesion que apunta a AuthController.php?accion=logout.

Al momento de acceder a esta URL, el controlador detecta el parametro accion con valor logout y ejecuta session_destroy() que elimina todos los datos de la sesion actual. Luego redirige al usuario a la pagina de login con header Location hacia usuarios/login.php y exit().

No hay validaciones adicionales ni mensajes, el logout es instantaneo.
