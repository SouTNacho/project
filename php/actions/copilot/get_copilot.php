<?php

    session_start();
    // Falta validar rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/copilot_model.php";
    require_once __DIR__ . "/../../conection.php";

    $code =  trim($_GET['code'] ?? '');

    if ($code === '') {

        echo json_encode(
            ['success' => false,
            'message' => 'Error, los datos enviados son incorrectos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $copilot = findCopilotWithCode($mysqli, $code);

        if (!$copilot) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error, no se encontró el copiloto.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $copilot]
        );
        $mysqli->close();
        exit;

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
    }

?>
