<?php

    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/../../models/category_model.php';
    require_once __DIR__ . '/../../conection.php';

    $mysqli = null;

    try {
        $mysqli = connection_db();
        $categories = findAllCategories($mysqli);
        $mysqli->close();

        echo json_encode([
            'success' => true,
            'item' => $categories
        ]);
    } catch (Throwable $exception) {
        if ($mysqli instanceof mysqli) {
            $mysqli->close();
        }

        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'No se pudieron cargar las categorías.'
        ]);
    }

?>