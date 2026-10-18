<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/survey_model.php";
    require_once __DIR__ . "/../../conection.php";

    $id = (int) ($_GET['id'] ?? 0);

    if ($id <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Los datos recividos no son válidos.'
        ]);
        exit;
    }

    $mysqli = connection_db();
    $responses = getResponsesSuevey($mysqli, $id);

    if (!$responses) {

        $mysqli->close();

        echo json_encode([
            'success' => true,
            'message' => 'No existen respuestas para esta encuesta',
            'item' => []
        ]);

        exit;
    }

    $mysqli->close();

    echo json_encode([
        'success' => true,
        'message' => 'La peticion ha sido exitosa',
        'item' => $responses
    ]);

    exit;

?>