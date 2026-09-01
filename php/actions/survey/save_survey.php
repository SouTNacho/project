<?php

    session_start();
    header("Content-Type: application/json");
    // Validar que sea un usuario correcto quien realice esta acción

    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../models/survey_model.php";
    require_once __DIR__ . "/../conection.php";

    $survey_title = $_POST['survey_title'] ?? "";
    $survey_content = $_POST['survey'] ?? "";
    $id_sevice = (int) $_POST['id_sevice'] ?? "";
    $id_state_survey = 1;

    if (validateEmptyData($survey_title) || validateEmptyData($survey_content) || validateEmptyData($id_sevice)) {

        echo json_encode([
            'status' => false,
            'message' => 'Los datos enviados no son válidos.'
        ]);
        exit;
    }

    $mysqli = connection_db();
    if (findSurveyWithService($mysqli, $id_sevice)) $id_state_survey = 2;

    $survey = findSurveyWithTitle($mysqli, $survey_title);

    if ($survey) {

        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'Ya existe una encuesta con ese nombre.'
        ]);
        exit;
    }

    try {
        
        saveSurvey($mysqli, $survey_title, $survey_content, $id_sevice, $id_state_survey);

        $mysqli->close();

        echo json_encode([
            'succes' => true,
            'message' => 'La entrevista se ha creado exitosamente.'
        ]);

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' =>  $e->getMessage() . 'Ha ocurrido un error al crear la entrevista.'
        ]);
    }

?>