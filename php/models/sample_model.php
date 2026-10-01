<?php

    function findSampleWithCode($mysqli, $code) {

        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE codigo = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $sample = $result->fetch_assoc();
        $stmt->close();

        return $sample;
    }

    function findAllSamples($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM muestra");
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findSampleWithId($mysqli, $sample_id) {

        $stmt = $mysqli->prepare(" SELECT muestra.*, paciente.cedula
                                FROM muestra INNER JOIN paciente
                                ON muestra.id_paciente = paciente.id_paciente
                                WHERE muestra.id_muestra = ?");
        $stmt->bind_param("i", $sample_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sample = $result->fetch_assoc();
        $stmt->close();

        return $sample;
    }

    function findActiveSamples($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findInactiveSamples($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 2");
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findDeletedSamples($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 3");
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findDiscardedSamples($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 4");
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findAllSamplesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findActiveSamplesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 1 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findInactiveSamplesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 2 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findDeletedSamplesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 3 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function findDiscardedSamplesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM muestra WHERE id_estado_muestra = 4 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $samples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $samples;
    }

    function insertSample($mysqli, $code, $type, $description, $patient_id) {

        $stmt = $mysqli->prepare("INSERT INTO muestra(codigo, tipo, descripcion, id_paciente) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("sssi", $code, $type, $description, $patient_id);
        $stmt->execute();
        $stmt->close();
    }

    function findPatientWithDocument($mysqli, $document) {
        
        $stmt = $mysqli->prepare("SELECT * FROM paciente WHERE cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $stmt->close();

        return $patient;
    }

    function findPatientWithId($mysqli, $patient_id) {

        $stmt = $mysqli->prepare("SELECT * FROM paciente WHERE id_paciente = ?");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $stmt->close();

        return $patient;
    }

    function updateSample($mysqli, $new_code, $patient_id, $type, $description, $sample_id) {

        $stmt = $mysqli->prepare("UPDATE muestra SET codigo = ?, tipo = ?, descripcion = ?, id_paciente = ? WHERE id_muestra = ?");
        $stmt->bind_param("sssii", $new_code, $type, $description, $patient_id, $sample_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllSamplesStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_muestra");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateSample($mysqli, $sample_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE muestra SET id_estado_muestra = ? WHERE id_muestra = ?");
        $stmt->bind_param("ii", $state_id, $sample_id);
        $stmt->execute();
        $stmt->close();
    }

?>