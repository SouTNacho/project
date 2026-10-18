<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../models/type_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();

    try {

        $types = findActiveTypes($mysqli);

        if (!$types) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al solicitar los tipos.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $types]
        );
        $mysqli->close();
        exit;

    } catch (mysqli_sql_exception $e) {
        
        // DESPUES QUITAR EL MENSAJE
        $mysqli->close();
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
    }

?>
