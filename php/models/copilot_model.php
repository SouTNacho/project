<?php

    function findCopilotWithCode($mysqli, $copilot_code) {

        $stmt = $mysqli->prepare("SELECT * FROM copiloto WHERE codigo = ?");
        $stmt->bind_param("s", $copilot_code);
        $stmt->execute();

        $result = $stmt->get_result();
        $copilot = $result->fetch_assoc();
        $stmt->close();

        return $copilot;
    }


    function findCopilotWithEmployeeId($mysqli, $employee_id) {

        $stmt = $mysqli->prepare("SELECT * FROM copiloto WHERE id_funcionario = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $copilot = $result->fetch_assoc();
        $stmt->close();

        return $copilot;
    }


    function findAllCopilots($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM copiloto");
        $stmt->execute();

        $result = $stmt->get_result();
        $copilots = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $copilots;
    }


    function findAllCopilotsWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM copiloto WHERE codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $copilots = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $copilots;
    }


    function insertCopilot($mysqli, $speciality, $password, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO copiloto (especialidad, pass, id_funcionario) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $speciality, $password, $employee_id);

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }


    function updateCopilotCode($mysqli, $copilot_id, $copilot_code) {

        $stmt = $mysqli->prepare("UPDATE copiloto SET codigo = ? WHERE id_copiloto = ?");
        $stmt->bind_param("si", $copilot_code, $copilot_id);
        $stmt->execute();
        $stmt->close();
    }


    function updateCopilot($mysqli, $copilot_id, $speciality, $employee_id) {

    $stmt = $mysqli->prepare("UPDATE copiloto SET especialidad = ?, id_funcionario = ? WHERE id_copiloto = ?");
    $stmt->bind_param("sii", $speciality, $employee_id, $copilot_id);
    $stmt->execute();
    $stmt->close();
}


    function changePasswordCopilot($mysqli, $copilot_id, $password) {

        $stmt = $mysqli->prepare("UPDATE copiloto SET pass = ? WHERE id_copiloto = ?");
        $stmt->bind_param("si", $password, $copilot_id);
        $stmt->execute();
        $stmt->close();
    }

    function findCopilotWithDocument($mysqli, $document) {

    $stmt = $mysqli->prepare("SELECT c.* FROM copiloto c INNER JOIN funcionario f
            ON c.id_funcionario = f.id_funcionario WHERE f.cedula = ?");

    $stmt->bind_param("s", $document);
    $stmt->execute();

    $result = $stmt->get_result();
    $copilot = $result->fetch_assoc();
    $stmt->close();

    return $copilot;
}

?>