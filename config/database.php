<?php
// ============================================================
//  CONFIGURACIÓN DE BASE DE DATOS
//  Edita las credenciales de producción antes de subir.
// ============================================================

$httpHost   = isset($_SERVER['HTTP_HOST'])   ? $_SERVER['HTTP_HOST']   : '';
$serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';

$esLocal = ($serverName === 'localhost'
        || $serverName === '127.0.0.1'
        || $serverName === '::1'
        || strpos($httpHost, 'localhost') !== false
        || strpos($httpHost, '127.0.0.1') !== false);

if ($esLocal) {
    // ── ENTORNO LOCAL (Laragon) ──────────────────────────
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', '3320');
    define('DB_NAME', 'siprof');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // ── ENTORNO PRODUCCIÓN (byethost) ────────────────────
    // Reemplaza con los datos de tu panel de byethost
    define('DB_HOST', 'sql200.byethost.com');   // host MySQL de byethost
    define('DB_PORT', '3306');                   // puerto estándar
    define('DB_NAME', 'b17_XXXXXXX_siprof');    // nombre de tu BD
    define('DB_USER', 'b17_XXXXXXX');            // usuario de tu BD
    define('DB_PASS', 'TU_PASSWORD_AQUI');       // contraseña
}

function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $opciones = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        );
        $conn = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        return $conn;

    } catch (PDOException $e) {
        $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $esLocal  = (strpos($httpHost, 'localhost') !== false || strpos($httpHost, '127.0.0.1') !== false);

        if ($esLocal) {
            die("<div style='color:red;padding:20px;border:1px solid red;font-family:sans-serif;'>"
            . "<h3>Error de conexion</h3>"
            . "<p>" . htmlspecialchars($e->getMessage()) . "</p>"
            . "<p>Verifica que MySQL este encendido en Laragon (puerto " . DB_PORT . ").</p>"
            . "</div>");
        } else {
            die("<div style='color:red;padding:20px;font-family:sans-serif;'>"
            . "<h3>Error de conexion a la base de datos.</h3>"
            . "<p>Verifica las credenciales en config/database.php</p>"
            . "</div>");
        }
    }
}
?>
