<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/copilot_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = trim($_GET['phrase'] ?? '');
    $mysqli = connection_db();

    try {

        if ($phrase !== '') {

            $complete_phrase = '%' . $phrase . '%';
            $copilots = findAllCopilotsWithPhrase($mysqli, $complete_phrase);
        } else {

            $copilots = findAllCopilots($mysqli);
        }

        if (!$copilots) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron copilotos.', 
                'item' => $copilots]
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
        exit;
    }

?>
