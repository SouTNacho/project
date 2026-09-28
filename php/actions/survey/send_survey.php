<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/survey_model.php";
    require_once __DIR__ . "/../../conection.php";

    $survey_id = (int) $_POST['survey_id'] ?? 0;
    $response = $_POST['response'] ?? "";

    if (validateEmptyData($survey_id) || validateEmptyData($response)) {

        echo json_encode([
            'succes' => false,
            'message' => 'Los datos enviados no son válidos.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    $survey = findSurveyWithId($mysqli, $survey_id);

    if (!$survey) {

        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'La encuesta no existe.'
        ]);
        exit;
    }

    try {
        
        saveResponse($mysqli, $survey_id, $response);

        $mysqli->close();

        echo json_encode([
            'succes' => true,
            'message' => 'La encuesta se ha enviado exitosamente.'
        ]);

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' =>  $e->getMessage() . ' Ha ocurrido un error al enviar la encuesta.'
        ]);
    }

?>