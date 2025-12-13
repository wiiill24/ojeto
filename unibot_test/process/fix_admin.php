<?php
// fix_admin.php
require_once('../config/database.php');

try {
    $conn = getConnection();
    
    // 1. Definir los datos correctos
    $usuario = 'ADMIN001';
    $password_nueva = 'admin123';
    
    // 2. Generar el hash real compatible con tu PHP
    $hash_correcto = password_hash($password_nueva, PASSWORD_DEFAULT);
    
    // 3. Actualizar el usuario en la BD
    $sql = "UPDATE usuario 
            SET contrasena_hash = ?, rol_id = 2 
            WHERE codigo_estudiante = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hash_correcto, $usuario);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<h1>¡ÉXITO! </h1>";
            echo "<p>La contraseña para <b>$usuario</b> se actualizó correctamente a: <b>$password_nueva</b></p>";
            echo "<p>Rol asignado: <b>Administrador (ID 2)</b></p>";
            echo "<br><a href='../login.php'>Ir a Iniciar Sesión</a>";
        } else {
            echo "<h1> AVISO</h1>";
            echo "<p>No se encontró el usuario <b>$usuario</b> o la contraseña ya era esa.</p>";
            echo "<p>Asegúrate de que el usuario ADMIN001 exista en la tabla 'usuario'.</p>";
        }
    } else {
        echo "Error SQL: " . $stmt->error;
    }
    
    $conn->close();

} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>