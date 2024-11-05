<?php
// models/IncidenteModel.php
require_once 'config.php';

class IncidenteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($id = null) {
        // Consulta SQL básica para obtener los incidentes y sus relaciones
        $query = "
            SELECT i.*, e.nombre as estado_nombre, u.nombre as usuario_nombre
            FROM Incidente i
            LEFT JOIN Estado e ON i.id_estado = e.id
            LEFT JOIN Usuario u ON i.id_usuario = u.id
        ";
    
        // Agregar filtro de ID si $id está definido
        if ($id) {
            $query .= " WHERE i.id LIKE :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['id' => $id . '%']);
        } else {
            $stmt = $this->pdo->query($query);
        }
    
        // Recuperar todos los incidentes
        $incidentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $incidentes;
    }
    

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, e.nombre as estado_nombre, u.nombre as usuario_nombre
            FROM Incidente i
            LEFT JOIN Estado e ON i.id_estado = e.id
            LEFT JOIN Usuario u ON i.id_usuario = u.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($descripcion, $fecha_ocurrencia, $id_estado, $id_usuario) {
        $stmt = $this->pdo->prepare("INSERT INTO Incidente (descripcion, fecha_ocurrencia, id_estado, id_usuario) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$descripcion, $fecha_ocurrencia, $id_estado, $id_usuario]);
    }

    public function update($id, $descripcion, $fecha_ocurrencia, $id_estado, $id_usuario) {
        $stmt = $this->pdo->prepare("UPDATE Incidente SET descripcion = ?, fecha_ocurrencia = ?, id_estado = ?, id_usuario = ? WHERE id = ?");
        return $stmt->execute([$descripcion, $fecha_ocurrencia, $id_estado, $id_usuario, $id]);
    }
}
