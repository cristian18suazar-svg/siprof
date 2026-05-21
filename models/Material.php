<?php
class Material {
    private $conn;
    private $tabla = "materiales";

    public function __construct($db) {
        $this->conn = $db;
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
                    (Nombre, Tipo, Descripcion, Cantidad, Unidad, StockMinimo, Precio, Estado) 
                    VALUES (:nombre, :tipo, :descripcion, :cantidad, :unidad, :stock, :precio, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":descripcion", $datos['descripcion']);
            $stmt->bindParam(":cantidad", $datos['cantidad']);
            $stmt->bindParam(":unidad", $datos['unidad']);
            $stmt->bindParam(":stock", $datos['stock_minimo']);
            $stmt->bindParam(":precio", $datos['precio']);
            $stmt->bindParam(":estado", $datos['estado']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear material: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET 
                    Nombre = :nombre, 
                    Tipo = :tipo, 
                    Descripcion = :descripcion, 
                    Cantidad = :cantidad, 
                    Unidad = :unidad, 
                    StockMinimo = :stock, 
                    Precio = :precio, 
                    Estado = :estado 
                    WHERE IDmateriales = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":descripcion", $datos['descripcion']);
            $stmt->bindParam(":cantidad", $datos['cantidad']);
            $stmt->bindParam(":unidad", $datos['unidad']);
            $stmt->bindParam(":stock", $datos['stock_minimo']);
            $stmt->bindParam(":precio", $datos['precio']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar material: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE IDmateriales = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar material: " . $e->getMessage());
            return false;
        }
    }

    public function modificarStock($id, $cantidad, $tipo = 'sumar') {
        try {
            $operador = ($tipo === 'sumar') ? '+' : '-';
            $sql = "UPDATE " . $this->tabla . " SET Cantidad = Cantidad $operador :cantidad WHERE IDmateriales = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
