<?php
class Cultivo {
    private $conn;
    private $tabla = "cultivo";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT c.*, f.Nombre as FaseNombre, l.Nombre as LoteNombre 
                FROM " . $this->tabla . " c
                LEFT JOIN fase f ON c.IDfase = f.IDfase
                LEFT JOIN lote l ON c.IDlote = l.IDlote
                ORDER BY c.Nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " 
                    (Nombre, Fechainicio, Fechacosecha, Estado, IDfase, IDlote) 
                    VALUES (:nombre, :inicio, :cosecha, :estado, :idfase, :idlote)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":inicio", $datos['inicio']);
            $stmt->bindParam(":cosecha", $datos['cosecha']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":idfase", $datos['idfase']);
            $stmt->bindParam(":idlote", $datos['idlote']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear cultivo: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET 
                    Nombre = :nombre, 
                    Fechainicio = :inicio, 
                    Fechacosecha = :cosecha, 
                    Estado = :estado, 
                    IDfase = :idfase, 
                    IDlote = :idlote 
                    WHERE IDcultivo = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":inicio", $datos['inicio']);
            $stmt->bindParam(":cosecha", $datos['cosecha']);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":idfase", $datos['idfase']);
            $stmt->bindParam(":idlote", $datos['idlote']);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar cultivo: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        // La eliminación en cascada se maneja desde el controlador
        $sql = "DELETE FROM " . $this->tabla . " WHERE IDcultivo = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function eliminarDependencias($idCultivo) {
        $this->conn->prepare("DELETE FROM controldecultivo WHERE IDcultivo = :id")->execute(array(':id' => $idCultivo));
        $this->conn->prepare("DELETE FROM seguimiento WHERE IDcultivo = :id")->execute(array(':id' => $idCultivo));
        $this->conn->prepare("DELETE FROM produccion WHERE IDcultivo = :id")->execute(array(':id' => $idCultivo));
    }
}
?>
