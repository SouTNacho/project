<?php

    session_start();
    // Falta validar rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    $document_token = $_GET['token'] ?? '';

    if ($document_token === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Error, el documento seleccionado no es válido.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        $document = findDocumentWithToken($mysqli, $document_token);

        if (!$document) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error, no se encontró el documento.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $document]
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
