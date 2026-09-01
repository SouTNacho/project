<?php

    session_start();
    header("Content-Type: application/json");

    // Validar que sea el super usuario el que pueda modificar esto

    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../models/survey_model.php";
    require_once __DIR__ . "/../conection.php";

    $survey_id = (int) $_POST['survey_id'];
    $service_id = (int) $_POST['service_id'];

    if (validateEmptyData($survey_id) || validateEmptyData($service_id)) {

        echo json_encode([
            'succes' => false,
            'message' => 'Los datos recibidos no son validos'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        $active_survey = getActiveSurvey($mysqli, $service_id);

        if ($active_survey && (int)$active_survey['id_encuesta'] === $survey_id) {

            $mysqli->close();

            echo json_encode([
                'succes' => true,
                'message' => 'La encuesta selecciona ya esta activa para el servicio'
            ]);
            exit;
        }

        $mysqli->begin_transaction();

        if ($active_survey) {

            desactivateSurvey($mysqli, $active_survey['id_encuesta']);
        }

        activeSurvey($mysqli, $survey_id);

        $mysqli->commit();
        $mysqli->close();

        echo json_encode([
            'succes' => true,
            'message' => 'La encuesta ha sido activada exitosamente'
        ]);
        exit;

    } catch (mysqli_sql_exception $e) {

        $mysqli->rollback();
        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
        exit;
    }

?>