<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$host = '127.0.0.1';
$port = '3306'; 
$dbname = 'db_unibot';
$username = 'root';
$password = ''; 

// Texto mas sencillo  
function limpiarTexto($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    // Reemplazar tildes
    $texto = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
        ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
        $texto
    );
    // verifica que solo sean letra y numero
    $texto = preg_replace('/[^a-z0-9\s]/', '', $texto);
    return trim($texto);
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    $mensaje_original = isset($input['mensaje']) ? trim($input['mensaje']) : '';
    $usuario_id = isset($input['usuario_id']) ? (int)$input['usuario_id'] : null;
    
    if (empty($mensaje_original)) {
        echo json_encode(['error' => true, 'mensaje' => 'Por favor envía un mensaje']);
        exit;
    }

    // simplificar mensaje
    $mensaje_limpio = limpiarTexto($mensaje_original);
    
    // Lista de palabras a ignorar
    $palabras_comunes = [
        'el', 'la', 'los', 'las', 'un', 'una', 'unos', 'unas', 'de', 'del', 'al', 'en', 'por', 'para', 'con', 'sin', 
        'sobre', 'es', 'son', 'como', 'cuando', 'donde', 'que', 'cual', 'cuales', 'puedo', 'puede', 'necesito', 
        'quiero', 'tengo', 'hay', 'esta', 'este', 'esto', 'mi', 'tu', 'su', 'me', 'te', 'se', 'yo', 'nosotros', 
        'ellos', 'y', 'o', 'u', 'a', 'hacer', 'saber', 'decir', 'ver', 'ir', 'hola', 'buenos', 'dias', 'tardes'
    ];
    
    $palabras = explode(' ', $mensaje_limpio);
    $palabras_clave = array_filter($palabras, function($palabra) use ($palabras_comunes) {
        return strlen($palabra) > 2 && !in_array($palabra, $palabras_comunes);
    });
    
    $palabras_clave = array_values($palabras_clave);

    $respuesta = [
        'tipo' => 'respuesta',
        'mensaje_original' => $mensaje_original,
        'encontrado' => false,
        'respuestas' => [],
        'timestamp' => date('c')
    ];

    if (count($palabras_clave) > 0) {

        $condiciones = [];
        $parametros = [];
        
        foreach ($palabras_clave as $palabra) {
            $condiciones[] = "(pregunta LIKE ? OR respuesta LIKE ?)";
            $parametros[] = "%$palabra%";
            $parametros[] = "%$palabra%";
        }
        
        $where_clause = implode(' OR ', $condiciones);
        
        $score_parts = [];
        foreach ($palabras_clave as $palabra) {
            $score_parts[] = "(IF(pregunta LIKE '%$palabra%', 10, 0) + IF(respuesta LIKE '%$palabra%', 2, 0))";
        }
        $score_formula = implode(' + ', $score_parts);
        
        $query = "
            SELECT 
                id, pregunta, respuesta, categoria_id,
                ($score_formula) as relevancia
            FROM preguntas_frecuentes 
            WHERE $where_clause
            HAVING relevancia >= 5  -- FILTRO DE CALIDAD: Si la relevancia es baja, no lo muestra
            ORDER BY relevancia DESC
            LIMIT 3
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($parametros);
        $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($preguntas) > 0) {
            $respuesta['encontrado'] = true;
            $respuesta['tipo_respuesta'] = 'preguntas_frecuentes';
            $respuesta['respuestas'] = $preguntas;
        }
        
        if (!$respuesta['encontrado']) {
            $texto_completo = implode(' ', $palabras_clave); 

            // A. HORARIOS
            if (strpos($texto_completo, 'horario') !== false || strpos($texto_completo, 'clase') !== false) {

                 try {
                    $stmt = $pdo->prepare("SELECT h.id, h.dia_semana, h.hora_inicio, h.hora_fin, COALESCE(aula.codigo, 'Sin asignar') as aula, COALESCE(asig.nombre, 'Sin asignar') as asignatura FROM horario h LEFT JOIN aula ON h.aula_id = aula.id LEFT JOIN asignatura asig ON h.asignatura_id = asig.id LIMIT 5");
                    $stmt->execute();
                    $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(count($horarios) > 0) {
                        $respuesta['encontrado'] = true;
                        $respuesta['tipo_respuesta'] = 'horarios';
                        $respuesta['respuestas'] = $horarios;
                    }
                } catch(PDOException $e) {}
            }
            
            // B. PAGOS 
            else if (strpos($texto_completo, 'pago') !== false || strpos($texto_completo, 'costo') !== false || strpos($texto_completo, 'mensualidad') !== false) {
                 $stmt = $pdo->prepare("SELECT * FROM preguntas_frecuentes WHERE categoria_id = 4 OR pregunta LIKE '%pago%' LIMIT 3");
                 $stmt->execute();
                 $res_pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                 if(count($res_pagos) > 0){
                     $respuesta['encontrado'] = true;
                     $respuesta['tipo_respuesta'] = 'preguntas_frecuentes';
                     $respuesta['respuestas'] = $res_pagos;
                 }
            }
        }
    }

    if (!$respuesta['encontrado']) {
        $respuesta['mensaje_bot'] = "No estoy seguro de haber entendido bien sobre \"" . htmlspecialchars($mensaje_original) . "\". \n\nIntenta usar palabras clave como:\n• 'Horarios'\n• 'Inscripciones'\n• 'Pagos'\n• 'Ubicación'";
    }

    if ($usuario_id) {
        try {
            $stmt = $pdo->prepare("INSERT INTO mensaje (contenido, fecha_envio, usuario_id, tipo) VALUES (?, NOW(), ?, 'usuario')");
            $stmt->execute([$mensaje_original, $usuario_id]);
            
            $stmt = $pdo->prepare("INSERT INTO mensaje (contenido, fecha_envio, usuario_id, tipo) VALUES (?, NOW(), ?, 'bot')");
            $contenido_bot = $respuesta['encontrado'] ? json_encode($respuesta) : $respuesta['mensaje_bot'];
            $stmt->execute([$contenido_bot, $usuario_id]);
        } catch (PDOException $e) {}
    }

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(['error' => true, 'mensaje' => 'Error BD: ' . $e->getMessage()]);
}
?>