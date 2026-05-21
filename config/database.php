<?php
// Configuración de la base de datos
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'siprof');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3320');

function getConnection() {
    try {
        $dsn_init = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
        $conn = new PDO($dsn_init, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE      => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $conn->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        $conn->exec("USE `" . DB_NAME . "`;");

        // CORREGIDO: database.php está en la raíz, sql/ también está en la raíz
        $stmt = $conn->query("SHOW TABLES LIKE 'usuario'");
        if ($stmt->rowCount() == 0) {
            $sql_path = __DIR__ . '/../sql/siprof.sql';
            if (file_exists($sql_path)) {
                $conn->exec(file_get_contents($sql_path));
            }
        }

        return $conn;

    } catch (PDOException $e) {
        $error_msg  = "<div style='color:red; padding:20px; border:1px solid red; font-family:sans-serif;'>";
        $error_msg .= "<h3>Error de conexión a la base de datos</h3>";
        $error_msg .= "<p>" . $e->getMessage() . "</p>";
        $error_msg .= "<p>Verifica que MySQL esté encendido en Laragon usando el puerto " . DB_PORT . ".</p>";
        $error_msg .= "</div>";
        die($error_msg);
    }
}
?>