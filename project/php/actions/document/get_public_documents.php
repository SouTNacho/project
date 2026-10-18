<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = $_GET['phrase'] ?? 'null';

    if ($phrase === '') {
        $phrase = 'null';
    }

    if (validateEmptyData($phrase)) {

        echo json_encode([
            'success' => false,
            'message' => 'Error, los datos recibidos no son válidos.'
        ]);

        exit;
    }

    $mysqli = connection_db();

    try {

        if ($phrase === 'null') {

            $documents = findActiveDocuments($mysqli);
        } else {

            $complete_phrase = '%' . $phrase . '%';
            $documents = findActiveDocumentsWithPhrase($mysqli, $complete_phrase);
        }

        if (!$documents) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron documentos.', 
                'item' => $documents]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $documents]
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
