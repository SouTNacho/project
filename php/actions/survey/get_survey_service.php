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
    $surveys = getActiveSurveysFromService($mysqli, $id);

    if (!$surveys) {

        $mysqli->close();

        echo json_encode([
            'success' => true,
            'message' => 'No existen encuestas para este servicio',
            'item' => []
        ]);

        exit;
    }

    $mysqli->close();

    echo json_encode([
        'success' => true,
        'message' => 'La peticion ha sido exitosa',
        'item' => $surveys
    ]);

    exit;

?>