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
            'message' => 'Error, el documento seleccionado no es valido.'
        ]);

        exit;
    }

    $mysqli = connection_db();

    try {

        $document = findDocumentWithToken($mysqli, $document_token);

        if (!$document) {
            
            $mysqli->close();
            echo json_encode([
                'success' => false,
                'message' => 'Error, el documento seleccionado no existe.'
            ]);
            exit;
        }

        $mysqli->close();
        echo json_encode([
            'success' => true,
            'message' => 'El documento seleccionado es válido.',
            'param' => 'http://localhost:3000/php/pages/document/download_qr_doc.php?token=' . $document_token
        ]);

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();
        // DESPUES QUITAR EL MENSAJE
        
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
    }

?>