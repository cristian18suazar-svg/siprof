<?php
class Lote {
    private $conn;
    private $tabla = "lote";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM " . $this->tabla . " ORDER BY Nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE IDlote = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " (Nombre, Ubicacion, Area, Estado) VALUES (:nombre, :ubicacion, :area, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":ubicacion", $datos['ubicacion']);
            $stmt->bindParam(":area", $datos['area']);
            $stmt->bindParam(":estado", $datos['estado']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear lote: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET Nombre = :nombre, Ubicacion = :ubicacion, Area = :area, Estado = :estado WHERE IDlote = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":ubicacion", $datos['ubicacion']);
            $stmt->bindParam(":area", $datos['area']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar lote: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE IDlote = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar lote: " . $e->getMessage());
            return false;
        }
    }

    public function toggleEstado($id, $estado) {
        try {
            $nuevoEstado = ($estado == 1) ? 'Activo' : 'cancelado';
            $sql = "UPDATE " . $this->tabla . " SET Estado = :estado WHERE IDlote = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":estado", $nuevoEstado);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al cambiar estado de lote: " . $e->getMessage());
            return false;
        }
    }
}
?>
