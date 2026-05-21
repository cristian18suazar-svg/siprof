Esta funcionalidad esta pendiente de implementacion en el sistema. Actualmente no existe un controlador especifico para manejar la subida de fotos de perfil.

Para implementarla se necesitaria crear un controlador que maneje la subida de archivos con enctype multipart/form-data. El controlador deberia validar el tipo de archivo (jpg, png, gif) y el tamano maximo (por ejemplo 2MB). Luego guardar la imagen en la carpeta img/perfiles/ con un nombre unico y actualizar el campo FotoPerfil en la tabla usuario con el nombre del archivo.

El modelo Usuario necesitaria un metodo actualizarFoto() que ejecute UPDATE usuario SET FotoPerfil igual al nombre del archivo WHERE IDusuario igual al id del usuario. La tabla usuario necesitaria agregar el campo FotoPerfil de tipo VARCHAR.
