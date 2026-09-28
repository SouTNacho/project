<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/survey_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();
    $survey = getSurvey($mysqli, 1, 1);

    if (!$survey) {

        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'No existe una encuesta activa para este servicio.'
        ]);
        exit;
    }

    $mysqli->close();

    echo json_encode([
        'succes' => true,
        'message' => 'La peticion ha sido exitosa',
        'item' => $survey
    ]);
    exit;

?>