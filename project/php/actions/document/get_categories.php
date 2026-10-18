<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();

    try {

        $categories = findCategories($mysqli);

        if (!$categories) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al solicitar las categorías.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $categories]
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
