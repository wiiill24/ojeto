<?php
require_once __DIR__ . '/../core/Model.php';

class User extends Model {
    
    public function findByCode($codigo_estudiante) {
        // CORREGIDO: Usar la tabla 'usuario' y seleccionar 'id' y 'rol_id'
        $sql = "SELECT id, nombre, apellido, codigo_estudiante, contrasena_hash, carrera_id, rol_id 
                FROM usuario 
                WHERE codigo_estudiante = ?";
        
        $stmt = $this->query($sql, [$codigo_estudiante]);
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    public function create($nombre, $apellido, $codigo_estudiante, $contrasena, $id_carrera = 1) {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        // Nuevo usuario es un estudiante (rol_id = 1, según la BD)
        $rol_id = 1;
        
        // CORREGIDO: Usar la tabla 'usuario' y sus columnas correctas
        $sql = "INSERT INTO usuario (nombre, apellido, codigo_estudiante, contrasena_hash, carrera_id, rol_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->query($sql, [$nombre, $apellido, $codigo_estudiante, $contrasena_hash, $id_carrera, $rol_id]);
        
        if ($stmt->affected_rows > 0) {
            return $this->db->insert_id;
        }
        
        return false;
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function codeExists($codigo_estudiante) {
        // CORREGIDO: Usar la tabla usuario
        $sql = "SELECT COUNT(*) as count FROM usuario WHERE codigo_estudiante = ?";
        $stmt = $this->query($sql, [$codigo_estudiante]);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
}
?>