<?php

    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/../../models/category_model.php';
    require_once __DIR__ . '/../../conection.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido.'
        ]);
        exit();
    }

    $category_id = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
    $state_id = filter_var($_POST['state_id'] ?? null, FILTER_VALIDATE_INT);

    if ($category_id === false || $category_id === null || $category_id < 1 ||
        !in_array($state_id, [1, 2], true)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Los datos recibidos no son válidos.'
        ]);
        exit();
    }

    $mysqli = connection_db();

    try {
        if (!findCategoryById($mysqli, $category_id)) {
            $mysqli->close();
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'La categoría no existe.'
            ]);
            exit();
        }

        changeCategoryState($mysqli, $category_id, $state_id);
        $mysqli->close();

        echo json_encode([
            'success' => true,
            'message' => 'Estado de la categoría actualizado correctamente.'
        ]);
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo actualizar el estado de la categoría.'
        ]);
    }

?>
