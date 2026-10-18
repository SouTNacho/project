<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/survey_model.php";
    require_once __DIR__ . "/../../conection.php";

    $sercive = (int) ($_GET['id'] ?? 0);

    if ($sercive <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Los datos recividos no son válidos.'
        ]);
        exit;
    }

    $mysqli = connection_db();
    $survey = getSurvey($mysqli, $sercive, 1);

    if (!$survey) {

        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => 'No existe una encuesta activa para este servicio.'
        ]);

        exit;
    }

    $mysqli->close();

    echo json_encode([
        'success' => true,
        'message' => 'La peticion ha sido exitosa',
        'item' => $survey
    ]);

    exit;

?>