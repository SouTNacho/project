<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/transfer_model.php";
    require_once __DIR__ . "/../../conection.php";

    $transfer_id = (int) ($_GET['id'] ?? 0);

    if ($transfer_id <= 0) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $copilots = findElementsWithTransferId($mysqli, $transfer_id);

        if (!$copilots) {
            echo json_encode(
                ['success' => false,
                'message' => 'No se encontraron copilotos.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $copilots]
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
