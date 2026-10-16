<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user', 'administrative');

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/type_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = trim($_GET['phrase'] ?? '');
    $mysqli = connection_db();

    try {

        if ($phrase !== '') {

            $complete_phrase = '%' . $phrase . '%';
            $types = findAllTypesWithPhrase($mysqli, $complete_phrase);
        } else {

            $types = findAllTypes($mysqli);
        }

        if (!$types) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron tipos.', 
                'item' => $types]
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
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
        exit;
    }

?>
