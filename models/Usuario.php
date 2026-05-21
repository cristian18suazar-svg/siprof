<?php
class Usuario {
    private $conn;
    private $tabla = "usuario";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function existeCorreo($email) {
        $sql = "SELECT IDusuario FROM " . $this->tabla . " WHERE Correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":correo", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function obtenerPorEmail($email) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE Correo = :correo AND Estado = 'Activo' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":correo", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM " . $this->tabla . " ORDER BY Nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " 
                (Nombre, Correo, Celular, Contrasena, Niveldeacceso, Estado) 
                VALUES 
                (:nombre, :correo, :celular, :contrasena, :rol, 'Activo')";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":correo", $datos['correo']);
            $stmt->bindParam(":celular", $datos['celular']);
            $stmt->bindParam(":contrasena", $datos['contrasena']);
            $stmt->bindParam(":rol", $datos['rol']);
            
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET 
                Nombre = :nombre, 
                Correo = :correo, 
                Celular = :celular, 
                Niveldeacceso = :rol";
            
            // Agregar contraseña solo si se proporciona
            if (!empty($datos['contrasena'])) {
                $sql .= ", Contrasena = :contrasena";
            }
            
            $sql .= " WHERE IDusuario = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":correo", $datos['correo']);
            $stmt->bindParam(":celular", $datos['celular']);
            $stmt->bindParam(":rol", $datos['rol']);
            $stmt->bindParam(":id", $id);
            
            // Agregar contraseña solo si se proporciona
            if (!empty($datos['contrasena'])) {
                $stmt->bindParam(":contrasena", $datos['contrasena']);
            }
            
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function toggleEstado($id, $estado) {
        try {
            $nuevoEstado = ($estado == 1) ? 'Activo' : 'Inactivo';
            $sql = "UPDATE " . $this->tabla . " SET Estado = :estado WHERE IDusuario = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":estado", $nuevoEstado);
            $stmt->bindParam(":id", $id);
            
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error al cambiar estado: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE IDusuario = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>