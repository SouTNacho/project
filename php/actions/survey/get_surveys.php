<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../models/survey_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();
    $surveys = getSurveys($mysqli);

    if (!$surveys) {

        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => 'Ha ocurrido un error al conectar con el servidor'
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