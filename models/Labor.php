<?php
class Labor {
    private $conn;
    private $tabla = "asignaciondelabor";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT a.*, l.Nombre as LoteNombre 
                FROM " . $this->tabla . " a
                LEFT JOIN lote l ON a.IDlote = l.IDlote
                ORDER BY a.Fechainicio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " 
                    (Descripcionlabor, Tarea, Fechainicio, Fechafin, Estado, IDadministrador, IDtrabajador, IDlote) 
                    VALUES (:descripcion, :tarea, :inicio, :fin, :estado, :admin, :trabajador, :lote)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":descripcion", $datos['descripcion']);
            $stmt->bindParam(":tarea", $datos['tarea']);
            $stmt->bindParam(":inicio", $datos['inicio']);
            $stmt->bindParam(":fin", $datos['fin']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":admin", $datos['idadmin']);
            $stmt->bindParam(":trabajador", $datos['idtrabajador']);
            $stmt->bindParam(":lote", $datos['idlote']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear labor: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET 
                    Descripcionlabor = :descripcion, 
                    Tarea = :tarea, 
                    Fechainicio = :inicio, 
                    Fechafin = :fin, 
                    Estado = :estado, 
                    IDtrabajador = :trabajador, 
                    IDlote = :lote 
                    WHERE IDasignaciondelabor = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":descripcion", $datos['descripcion']);
            $stmt->bindParam(":tarea", $datos['tarea']);
            $stmt->bindParam(":inicio", $datos['inicio']);
            $stmt->bindParam(":fin", $datos['fin']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":trabajador", $datos['idtrabajador']);
            $stmt->bindParam(":lote", $datos['idlote']);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar labor: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstado($id, $estado) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET Estado = :estado WHERE IDasignaciondelabor = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar estado de labor: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE IDasignaciondelabor = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar labor: " . $e->getMessage());
            return false;
        }
    }
}
?>
