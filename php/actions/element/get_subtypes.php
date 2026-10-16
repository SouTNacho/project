<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../models/subtype_model.php";
    require_once __DIR__ . "/../../conection.php";

    $type = (int) ($_GET['type'] ?? 0);

    if ($type <= 0) {

        echo json_encode(
            ['success' => false,
            'message' => 'Error el dato recibido no es correcto.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $subtypes = findActiveSubtypesWithType($mysqli, $type);

        if (!$subtypes) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al solicitar los subtipos.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $subtypes]
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
