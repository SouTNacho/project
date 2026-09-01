<?php
// Y despues en otra pantalla permitir elegir la encuesta activa para cada servicio por si hay mas de una y queres activar una y desactivar la otra
    function saveSurvey($mysqli, $survey_title, $process_survey, $id_sevice, $id_state_survey) {

        $stmt = $mysqli->prepare("INSERT INTO encuesta(titulo, contenido, id_estado_encuesta, id_servicio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $survey_title, $process_survey, $id_state_survey, $id_sevice);
        $stmt->execute();
        $stmt->close();
    }

    function getSurveys($mysqli) {

        $stmt = $mysqli->prepare("SELECT encuesta.*, servicio.* FROM encuesta                                
                                INNER JOIN servicio ON servicio.id_servicio = encuesta.id_servicio");
        $stmt->execute();
        $result = $stmt->get_result();
        $surveys = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $surveys;
    }

    function findSurveyWithTitle($mysqli, $survey_title) {

        $stmt = $mysqli->prepare("SELECT * FROM encuesta WHERE titulo = ?");
        $stmt->bind_param("s", $survey_title);
        $stmt->execute();
        $result = $stmt->get_result();
        $survey = $result->fetch_assoc();
        $stmt->close();

        return $survey;
    }

    function findSurveyWithService($mysqli, $id_sevice) {

        $stmt = $mysqli->prepare("SELECT * FROM encuesta WHERE id_servicio = ? LIMIT 1");
        $stmt->bind_param("i", $id_sevice);
        $stmt->execute();
        $result = $stmt->get_result();
        $survey = $result->fetch_assoc();
        $stmt->close();

        return $survey;
    }

    function getServices($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM servicio");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function getActiveSurvey($mysqli, $service_id) {

        $stmt = $mysqli->prepare("SELECT * FROM encuesta WHERE id_servicio = ? AND id_estado_encuesta = 1");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $activeSurvey = $result->fetch_assoc();
        $stmt->close();

        return $activeSurvey;
    }

    function desactivateSurvey($mysqli, $survey_id) {

        $stmt = $mysqli->prepare("UPDATE encuesta SET id_estado_encuesta = 2 WHERE id_encuesta = ?");
        $stmt->bind_param("i", $survey_id);
        $stmt->execute();
        $stmt->close();
    }
    
    function activeSurvey($mysqli, $survey_id) {

        $stmt = $mysqli->prepare("UPDATE encuesta SET id_estado_encuesta = 1 WHERE id_encuesta = ?");
        $stmt->bind_param("i", $survey_id);
        $stmt->execute();
        $stmt->close();
    }

?>