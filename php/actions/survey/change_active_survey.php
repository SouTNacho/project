<?php

    session_start();
    header("Content-Type: application/json");
    // Validar que sea el super usuario el que pueda modificar esto

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/survey_model.php";
    require_once __DIR__ . "/../../conection.php";

    $survey_id = (int) ($_POST['survey_id'] ?? 0);
    $service_id = (int) ($_POST['service_id'] ?? 0);

    if ($service_id <= 0 || $survey_id <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Los datos recibidos no son válidos'
        ]);

        exit;
    }

    $mysqli = connection_db();

    try {

        $survey = findSurveyWithId($mysqli, $survey_id);

        if (!$survey || (int)$survey['id_servicio'] !== $service_id) {

            $mysqli->close();
            echo json_encode([
                'success' => false,
                'message' => 'La encuesta no pertenece al servicio seleccionado'
            ]);
            exit;
        }

        $active_survey = getActiveSurvey($mysqli, $service_id);
        if ($active_survey && (int)$active_survey['id_encuesta'] === $survey_id) {

            $mysqli->close();
            echo json_encode([
                'success' => true,
                'message' => 'La encuesta seleccionada ya está activa para el servicio'
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
            'success' => true,
            'message' => 'La encuesta ha sido activada exitosamente'
        ]);
        exit;

    } catch (mysqli_sql_exception $e) {

        $mysqli->rollback();
        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
        exit;
    }

?>