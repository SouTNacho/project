<?php

    function findAmbulancesForTransfer($mysqli, $start_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT a.* FROM ambulancia AS a WHERE a.id_estado_ambulancia = 1
            AND NOT EXISTS (SELECT 1 FROM pendiente AS p WHERE p.id_ambulancia = a.id_ambulancia
            AND p.hora_inicio < ? AND p.hora_fin > ?)");

        $stmt->bind_param("ss", $finish_time, $start_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $transfers;
    }

    function findCompanionsForTransfer($mysqli, $start_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT a.* FROM acompaniante AS a WHERE a.id_estado_acompaniante = 1
            AND NOT EXISTS (SELECT 1 FROM pendiente AS p WHERE p.id_acompaniante = a.id_acompaniante
            AND p.hora_inicio < ? AND p.hora_fin > ?)");

        $stmt->bind_param("ss", $finish_time, $start_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $transfers;
    }

    function findCopilotsForTransfer($mysqli, $start_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT c.* FROM copiloto AS c INNER JOIN funcionario AS f
            ON c.id_funcionario = f.id_funcionario WHERE f.id_estado_funcionario = 1
            AND NOT EXISTS (SELECT 1 FROM pendiente AS p WHERE p.codigo_copiloto = c.codigo
            AND p.hora_inicio < ? AND p.hora_fin > ?)");

        $stmt->bind_param("ss", $finish_time, $start_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $transfers;
    }

    function findDriversForTransfer($mysqli, $start_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT d.* FROM conductor AS d INNER JOIN funcionario AS f
            ON d.id_funcionario = f.id_funcionario WHERE f.id_estado_funcionario = 1
            AND NOT EXISTS (SELECT 1 FROM pendiente AS p WHERE p.codigo_conductor = d.codigo
            AND p.hora_inicio < ? AND p.hora_fin > ?)");

        $stmt->bind_param("ss", $finish_time, $start_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $transfers;
    }

    function findPatientsForTransfer($mysqli, $start_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT pa.* FROM paciente AS pa WHERE pa.id_estado_paciente = 1
            AND NOT EXISTS (SELECT 1 FROM pendiente AS p WHERE p.id_paciente = pa.id_paciente
            AND p.hora_inicio < ? AND p.hora_fin > ?)");

        $stmt->bind_param("ss", $finish_time, $start_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $transfers;
    }

    function findSamplesForTransfer($mysqli, $start_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT m.* FROM muestra AS m WHERE m.id_estado_muestra = 1
            AND NOT EXISTS (SELECT 1 FROM pendiente AS p WHERE p.id_muestra = m.id_muestra
            AND p.hora_inicio < ? AND p.hora_fin > ?)");

        $stmt->bind_param("ss", $finish_time, $start_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $transfers;
    }

    function crateTrasnfer($mysqli, $start, $duration, $administrative, $copilot, $driver, $ambulance, $route, $patient, $companion) {

        if ($patient === 0) $patient = null;
        if ($companion === 0) $companion = null;

        if ($copilot === '') $copilot = null;
        $start = $start->format('Y-m-d H:i:s');

        $stmt = $mysqli->prepare("INSERT INTO traslado(hora_inicio_estimada, duracion_estimada_minutos, codigo_administrativo,
                    codigo_copiloto, codigo_conductor, id_ambulancia, id_ruta, id_paciente, id_acompaniante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssiiii", $start, $duration, $administrative, $copilot, $driver, $ambulance, $route, $patient, $companion);
        $stmt->execute();

        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    function saveTrasnfer($mysqli, $start, $end, $id, $copilot, $driver, $ambulance, $patient, $companion) {

        if ($patient === 0) $patient = null;
        if ($copilot === '') $copilot = null;
        if ($companion === 0) $companion = null;

        $start = $start->format('Y-m-d H:i:s');
        $end = $end->format('Y-m-d H:i:s');

        $stmt = $mysqli->prepare("INSERT INTO pendiente(hora_inicio, hora_fin, id_traslado, codigo_copiloto, codigo_conductor,
                                    id_ambulancia, id_paciente, id_acompaniante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissiii", $start, $end, $id, $copilot, $driver, $ambulance, $patient, $companion);

        $stmt->execute();
        $stmt->close();
    }

    function savePatientCompanion($mysqli, $patient, $companion) {

        $stmt = $mysqli->prepare("INSERT INTO paciente_acompaniante(id_paciente, id_acompaniante) VALUES (?, ?)");
        $stmt->bind_param("ii", $patient, $companion);
        $stmt->execute();
        $stmt->close();
    }

    function saveElement($mysqli, $id, $element) {

        $stmt = $mysqli->prepare("INSERT INTO elemento_traslado(id_traslado, id_elemento) VALUES (?, ?)");
        $stmt->bind_param("ii", $id, $element);
        $stmt->execute();
        $stmt->close();
    }

    function saveSample($mysqli, $id, $sample) {

        $stmt = $mysqli->prepare("INSERT INTO muestra_traslado(id_traslado, id_muestra) VALUES (?, ?)");
        $stmt->bind_param("ii", $id, $sample);
        $stmt->execute();
        $stmt->close();
    }

    function saveHistorial($mysqli, $id, $description) {

        $stmt = $mysqli->prepare("INSERT INTO historial_traslado(descripcion, id_traslado) VALUES (?, ?)");
        $stmt->bind_param("si", $description, $id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllTransfersStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_traslado");
        $stmt->execute();

        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $states;
    }

    function findAllTransfers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado");
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findAllTransfersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE codigo_conductor LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findPendingTransfers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 1");
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findActiveTransfers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 2");
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findStoppedTransfers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 2");
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findFinishTransfers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 2");
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findPendingTransfersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 1
                                    AND codigo_conductor LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findActiveTransfersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 2
                                    AND codigo_conductor LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findStoppedTransfersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 3
                                    AND codigo_conductor LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findFinishTransfersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_estado_traslado = 4
                                    AND codigo_conductor LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findTransferWithId($mysqli, $id) {

        $stmt = $mysqli->prepare("SELECT * FROM traslado WHERE id_traslado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_assoc();

        $stmt->close();
        return $transfers;
    }

    function findCopilotWithCodeForTransfer($mysqli, $code) {

        $stmt = $mysqli->prepare("SELECT c.*, f.cedula, f.nombre, f.apellido, f.id_estado_funcionario
                                    FROM copiloto AS c INNER JOIN funcionario AS f
                                    ON c.id_funcionario = f.id_funcionario 
                                    WHERE c.codigo = ?");

        $stmt->bind_param("s", $code);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_assoc();

        $stmt->close();

        return $transfers;
    }

    function findDriverWithCodeForTransfer($mysqli, $code) {

        $stmt = $mysqli->prepare("SELECT d.*, f.cedula, f.nombre, f.apellido, f.id_estado_funcionario
                                    FROM conductor AS d INNER JOIN funcionario AS f
                                    ON d.id_funcionario = f.id_funcionario 
                                    WHERE d.codigo = ?");

        $stmt->bind_param("s", $code);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_assoc();

        $stmt->close();

        return $transfers;
    }

    function findAdministrativeWithCodeForTransfer($mysqli, $code) {

        $stmt = $mysqli->prepare("SELECT a.*, f.cedula, f.nombre, f.apellido, f.id_estado_funcionario
                                    FROM administrativo AS a INNER JOIN funcionario AS f
                                    ON a.id_funcionario = f.id_funcionario 
                                    WHERE a.codigo = ?");

        $stmt->bind_param("s", $code);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_assoc();

        $stmt->close();

        return $transfers;
    }

    function findSamplesWithTransferId($mysqli, $id) {

        $stmt = $mysqli->prepare("SELECT * FROM muestra_traslado WHERE id_traslado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function findElementsWithTransferId($mysqli, $id) {

        $stmt = $mysqli->prepare("SELECT * FROM elemento_traslado WHERE id_traslado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $transfers;
    }

    function getTransferHistoryWithId($mysqli, $id) {

        $stmt = $mysqli->prepare("SELECT * FROM historial_traslado WHERE id_traslado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $historial = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $historial;
    }
?>