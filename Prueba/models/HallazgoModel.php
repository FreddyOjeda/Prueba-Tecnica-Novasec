<?php
// models/HallazgoModel.php
require_once 'config.php';

class HallazgoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($sede_id = null) {
        // Consulta SQL básica para obtener los hallazgos y sus relaciones
        $query = "
            SELECT h.*, e.nombre as estado_nombre, u.nombre as usuario_nombre, s.nombre as sede_nombre
            FROM Hallazgo h
            LEFT JOIN Estado e ON h.id_estado = e.id
            LEFT JOIN Usuario u ON h.id_usuario = u.id
            LEFT JOIN Sede s ON h.sede_id = s.id
        ";
    
        // Agregar filtro de sede si $sede_id está definido
        if ($sede_id) {
            $query .= " WHERE h.sede_id = :sede_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['sede_id' => $sede_id]);
        } else {
            $stmt = $this->pdo->query($query);
        }
    
        $hallazgos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener procesos para cada hallazgo
        foreach ($hallazgos as &$hallazgo) {
            $hallazgo['procesos'] = $this->getProcesos($hallazgo['id']);
        }
    
        return $hallazgos;
    }
    

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT h.*, e.nombre as estado_nombre, u.nombre as usuario_nombre, s.nombre as sede_nombre
            FROM Hallazgo h
            LEFT JOIN Estado e ON h.id_estado = e.id
            LEFT JOIN Usuario u ON h.id_usuario = u.id
            LEFT JOIN Sede s ON h.sede_id = s.id
            WHERE h.id = ?
        ");
        $stmt->execute([$id]);
        $hallazgo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($hallazgo) {
            $hallazgo['procesos'] = $this->getProcesos($hallazgo['id']);
        }
        return $hallazgo;
    }

    public function insert($titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_sede) {
        $stmt = $this->pdo->prepare("INSERT INTO Hallazgo (titulo, descripcion, id_estado, id_usuario, sede_id) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$titulo, $descripcion, $id_estado, $id_usuario, $id_sede]);

        if ($result) {
            $hallazgo_id = $this->pdo->lastInsertId();
            $this->updateProcesos($hallazgo_id, $proceso_ids);
            return true;
        }
        return false;
    }

    public function update($id, $titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_sede) {
        $stmt = $this->pdo->prepare("UPDATE Hallazgo SET titulo = ?, descripcion = ?, id_estado = ?, id_usuario = ?, sede_id = ? WHERE id = ?");
        $result = $stmt->execute([$titulo, $descripcion, $id_estado, $id_usuario, $id_sede, $id]);

        if ($result) {
            $this->updateProcesos($id, $proceso_ids);
            return true;
        }
        return false;
    }

    public function updateEstado($id_hallazgo, $estado_id) {
        $sql = "UPDATE hallazgo SET id_estado = :estado_id WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['estado_id' => $estado_id, 'id' => $id_hallazgo]);
    }    

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Hallazgo WHERE id = ?");
        return $stmt->execute([$id]);
    }
	
	private function updateProcesos($hallazgo_id, $proceso_ids) {
        // Eliminar procesos existentes
        $stmt = $this->pdo->prepare("DELETE FROM Hallazgo_Proceso WHERE id_hallazgo = ?");
        $stmt->execute([$hallazgo_id]);

        // Insertar procesos seleccionados
        foreach ($proceso_ids as $proceso_id) {
            $stmt = $this->pdo->prepare("INSERT INTO Hallazgo_Proceso (id_hallazgo, id_proceso) VALUES (?, ?)");
            $stmt->execute([$hallazgo_id, $proceso_id]);
        }
    }

    public function getProcesos($hallazgo_id) {
        $stmt = $this->pdo->prepare("SELECT p.* FROM Proceso p INNER JOIN Hallazgo_Proceso hp ON p.id = hp.id_proceso WHERE hp.id_hallazgo = ?");
        $stmt->execute([$hallazgo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>