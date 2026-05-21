Documentacion QA - Sistema Fincario SIPROF

Esta documentacion esta organizada por modulos y funcionalidades del sistema. Cada archivo describe la logica de una funcionalidad especifica, el flujo de ejecucion, validaciones y archivos involucrados.

Estructura de la documentacion:

1. Autenticacion y Usuarios
Gestion de usuarios, autenticacion y perfiles.
- logica_login.md
- logica_logout.md
- logica_registro.md
- logica_crear_usuario.md
- logica_editar_usuario.md
- logica_eliminar_usuario.md
- logica_toggle_estado_usuario.md

2. Lotes y Asignaciones
Gestion de lotes y areas de cultivo.
- logica_crear_lote.md
- logica_editar_lote.md
- logica_eliminar_lote.md
- logica_toggle_estado_lote.md

3. Cultivos
Gestion de cultivos y fases de crecimiento.
- logica_crear_fase.md
- logica_editar_fase.md
- logica_eliminar_fase.md
- logica_crear_cultivo.md
- logica_editar_cultivo.md
- logica_eliminar_cultivo.md

4. Actividades y Cosechas
Gestion de labores, controles fitosanitarios y actividades.
- logica_crear_labor.md
- logica_editar_labor.md
- logica_eliminar_labor.md
- logica_completar_labor.md
- logica_crear_control_cultivo.md
- logica_editar_control_cultivo.md
- logica_eliminar_control_cultivo.md

5. Fotos y Multimedia
Gestion de imagenes y archivos multimedia.
- logica_subir_foto_perfil.md (pendiente de implementacion)

6. Reportes y Notificaciones
Gestion de pagos, materiales y reportes.
- logica_crear_pago.md
- logica_editar_pago.md
- logica_eliminar_pago.md
- logica_aprobar_pago.md
- logica_crear_material.md

Arquitectura del sistema:

El sistema sigue el patron Modelo-Vista-Controlador:
- Modelos (models/) - Interaccion con la base de datos
- Vistas (views/) - Interfaz de usuario
- Controladores (controllers/) - Logica de negocio

Base de datos:
- Motor: MySQL/MariaDB
- Configuracion: config/database.php
- Script SQL: sql/siprof.sql

Sesiones:
- Gestion de sesiones PHP para autenticacion
- Almacenamiento de alertas en $_SESSION['alert']
- Informacion de usuario en $_SESSION['usuario']

Roles del sistema:
1. Administrador - Acceso completo al sistema
2. Mayordomo - Gestion de labores y trabajadores
3. Trabajador - Visualizacion de labores asignadas

Uso de esta documentacion:

Esta documentacion esta disenada para desarrolladores que necesiten entender la logica del sistema para mantenimiento o extension, para QA que necesiten conocer el flujo esperado para pruebas, y para estudiantes que necesiten recuperar funcionalidades eliminadas durante evaluaciones.

Notas importantes:

Todos los controladores verifican la sesion activa antes de ejecutar acciones. Las contrasenas se hashean con bcrypt PASSWORD_BCRYPT. Las alertas se muestran usando SweetAlert2. Los formularios usan el metodo POST para envio de datos. Las eliminaciones son permanentes sin papelera de reciclaje.
