<?php
class ControlCultivo {
    private $conn;
    private $tabla = "controldecultivo";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT cc.*, 
                       c.Nombre  AS CultivoNombre,
                       f.Nombre  AS FaseNombre,
                       u.Nombre  AS UsuarioNombre
                FROM {$this->tabla} cc
                LEFT JOIN cultivo  c ON cc.IDcultivo = c.IDcultivo
                LEFT JOIN fase     f ON cc.IDfase    = f.IDfase
                LEFT JOIN usuario  u ON cc.IDusuario = u.IDusuario
                ORDER BY cc.Fechareporte DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear(array $datos): bool {
        try {
            $sql = "INSERT INTO {$this->tabla}
                        (Tipocontrol, Valorregistrado, Descripcion, Estado,
                         Fechareporte, Fechasolucion, IDcultivo, IDfase, IDusuario)
                    VALUES
                        (:tipo, :valor, :descripcion, :estado,
                         :fechareporte, :fechasolucion, :idcultivo, :idfase, :idusuario)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tipo',          $datos['tipo']);
            $stmt->bindParam(':valor',         $datos['valor']);
            $stmt->bindParam(':descripcion',   $datos['descripcion']);
            $stmt->bindParam(':estado',        $datos['estado']);
            $stmt->bindParam(':fechareporte',  $datos['fechareporte']);
            $stmt->bindParam(':fechasolucion', $datos['fechasolucion']);
            $stmt->bindParam(':idcultivo',     $datos['idcultivo']);
            $stmt->bindParam(':idfase',        $datos['idfase']);
            $stmt->bindParam(':idusuario',     $datos['idusuario']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al crear control: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, array $datos): bool {
        try {
            $sql = "UPDATE {$this->tabla} SET
                        Tipocontrol     = :tipo,
                        Valorregistrado = :valor,
                        Descripcion     = :descripcion,
                        Estado          = :estado,
                        Fechareporte    = :fechareporte,
                        Fechasolucion   = :fechasolucion,
                        IDcultivo       = :idcultivo,
                        IDfase          = :idfase
                    WHERE IDcontroldecultivo = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tipo',          $datos['tipo']);
            $stmt->bindParam(':valor',         $datos['valor']);
            $stmt->bindParam(':descripcion',   $datos['descripcion']);
            $stmt->bindParam(':estado',        $datos['estado']);
            $stmt->bindParam(':fechareporte',  $datos['fechareporte']);
            $stmt->bindParam(':fechasolucion', $datos['fechasolucion']);
            $stmt->bindParam(':idcultivo',     $datos['idcultivo']);
            $stmt->bindParam(':idfase',        $datos['idfase']);
            $stmt->bindParam(':id',            $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar control: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool {
        try {
            $sql  = "DELETE FROM {$this->tabla} WHERE IDcontroldecultivo = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar control: " . $e->getMessage());
            return false;
        }
    }
}
