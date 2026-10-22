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

        $stmt = $mysqli->prepare(" SELECT muestra.*, paciente.cedula, tipo_muestra.nombre AS tipo FROM muestra 
        INNER JOIN paciente ON muestra.id_paciente = paciente.id_paciente 
        INNER JOIN tipo_muestra ON muestra.id_tipo_muestra = tipo_muestra.id_tipo_muestra
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

    function insertSample($mysqli, $code, $type_id, $description, $patient_id) {

        $stmt = $mysqli->prepare("INSERT INTO muestra(codigo, id_tipo_muestra, descripcion, id_paciente) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("sisi", $code, $type_id, $description, $patient_id);
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

    function updateSample($mysqli, $new_code, $patient_id, $type_id, $description, $sample_id) {

        $stmt = $mysqli->prepare("UPDATE muestra SET codigo = ?, id_tipo_muestra = ?, descripcion = ?, id_paciente = ? WHERE id_muestra = ?");
        $stmt->bind_param("sisii", $new_code, $type_id, $description, $patient_id, $sample_id);
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
    

    // Tipos de muestras: columnas reales de BYP.
    function findTypes($mysqli, $phrase = '', $filter = 'all') {
        $sql = "SELECT id_tipo_muestra AS id_tipo, nombre FROM tipo_muestra";
        if ($phrase !== '') $sql .= " WHERE nombre LIKE ?";
        $sql .= " ORDER BY nombre";
        $stmt = $mysqli->prepare($sql);
        if ($phrase !== '') { $search = '%' . $phrase . '%'; $stmt->bind_param('s', $search); }
        $stmt->execute(); $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();
        return $rows;
    }
    function findTypeWithName($mysqli, $type) {
        $stmt = $mysqli->prepare("SELECT *, id_tipo_muestra AS id_tipo FROM tipo_muestra WHERE nombre = ?");
        $stmt->bind_param('s', $type); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }
    function findTypeWithId($mysqli, $type_id) {
        $stmt = $mysqli->prepare("SELECT *, id_tipo_muestra AS id_tipo FROM tipo_muestra WHERE id_tipo_muestra = ?");
        $stmt->bind_param('i', $type_id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }
    function updateTypeName($mysqli, $name, $type_id) {
        $stmt = $mysqli->prepare("UPDATE tipo_muestra SET nombre = ? WHERE id_tipo_muestra = ?");
        $stmt->bind_param('si', $name, $type_id); $stmt->execute(); $stmt->close();
    }
    function insertType($mysqli, $name) {
        $stmt = $mysqli->prepare("INSERT INTO tipo_muestra(nombre) VALUES(?)");
        $stmt->bind_param('s', $name); $stmt->execute(); $stmt->close();
    }
    function deleteType($mysqli, $type_id) {
        $stmt = $mysqli->prepare("DELETE FROM tipo_muestra WHERE id_tipo_muestra = ?");
        $stmt->bind_param('i', $type_id); $stmt->execute(); $stmt->close();
    }
    function countSamplesWithType($mysqli, $type_id) {
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM muestra WHERE id_tipo_muestra = ?");
        $stmt->bind_param('i', $type_id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return (int)$row['total'];
    }
?>
