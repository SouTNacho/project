<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../models/contact_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    $message_id = (int) ($_POST['id']?? 0);

    if ($message_id <= 0) {
        
        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $message = findMessageWithId($mysqli, $message_id);

        if (!$message) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: mensaje no encontrado.']
            );
            $mysqli->close();
            exit;
        }

        changeStateMessage($mysqli, $message_id);
        $mysqli->close();

        echo json_encode(
            ['success' => true,
            'message' => 'Estado actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado: ' . $e->getMessage()]
        );
        exit;
    }

?>
