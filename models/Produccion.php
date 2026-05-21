<?php
class Produccion {
    private $conn;
    private $tabla = "produccion";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT p.*, u.Nombre as UsuarioNombre, c.Nombre as CultivoNombre 
                FROM " . $this->tabla . " p
                LEFT JOIN usuario u ON p.IDusuario = u.IDusuario
                LEFT JOIN cultivo c ON p.IDcultivo = c.IDcultivo
                ORDER BY p.Fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Alias para compatibilidad con vistas que llamen obtenerTodas()
    public function obtenerTodas() {
        return $this->obtenerTodos();
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " 
                    (Fecha, Cantidad, Costo, Tipo, IDusuario, IDcultivo) 
                    VALUES (:fecha, :cantidad, :costo, :tipo, :idusuario, :idcultivo)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":fecha", $datos['fecha']);
            $stmt->bindParam(":cantidad", $datos['cantidad']);
            $stmt->bindParam(":costo", $datos['costo']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":idusuario", $datos['idusuario']);
            $stmt->bindParam(":idcultivo", $datos['idcultivo']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear produccion: " . $e->getMessage());
            return false;
        }
    }
}
?>
