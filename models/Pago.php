<?php
class Pago {
    private $conn;
    private $tabla = "pago";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT p.*, u.Nombre as TrabajadorNombre 
                FROM " . $this->tabla . " p
                LEFT JOIN usuario u ON p.IDtrabajador = u.IDusuario
                ORDER BY p.Fechapago DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " 
                    (Fechapago, Monto, Tipopago, Estado, IDtrabajador) 
                    VALUES (:fecha, :monto, :tipo, :estado, :idtrabajador)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":fecha", $datos['fecha']);
            $stmt->bindParam(":monto", $datos['monto']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":idtrabajador", $datos['idtrabajador']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear pago: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET 
                    Fechapago = :fecha, 
                    Monto = :monto, 
                    Tipopago = :tipo, 
                    Estado = :estado, 
                    IDtrabajador = :idtrabajador 
                    WHERE IDpago = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":fecha", $datos['fecha']);
            $stmt->bindParam(":monto", $datos['monto']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":idtrabajador", $datos['idtrabajador']);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar pago: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstado($id, $estado) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET Estado = :estado WHERE IDpago = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar estado de pago: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE IDpago = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar pago: " . $e->getMessage());
            return false;
        }
    }
}
?>
