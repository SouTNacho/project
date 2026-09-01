<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../models/survey_model.php";
    require_once __DIR__ . "/../conection.php";

    $mysqli = connection_db();
    $services = getServices($mysqli);

    if (!$services) {

        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'Ha ocurrido un error al conectar con el servidor'
        ]);
        exit;
    }

    $mysqli->close();

        echo json_encode([
            'succes' => true,
            'message' => 'La peticion ha sido exitosa',
            'item' => $services
        ]);
        exit;

?>