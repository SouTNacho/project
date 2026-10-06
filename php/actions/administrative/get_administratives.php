<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/administrative_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = trim($_GET['phrase'] ?? '');
    $mysqli = connection_db();

    try {

        if ($phrase !== '') {

            $complete_phrase = '%' . $phrase . '%';
            $administratives = findAllAdministrativesWithPhrase($mysqli, $complete_phrase);
        } else {

            $administratives = findAllAdministratives($mysqli);
        }

        if (!$administratives) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron administrativos.', 
                'item' => $administratives]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $administratives]
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
