<?php

    function findAmbulancesForTraslete($mysqli, $strat_time, $finish_time) {

        $stmt = $mysqli->prepare("SELECT * FROM ambulancia WHERE ");

        $stmt->bind_param("ss", $strat_time, $finish_time);
        $stmt->execute();

        $result = $stmt->get_result();
        $administratives = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $administratives;
    }

    function findAllAdministrativesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT administrativo.* FROM administrativo INNER JOIN funcionario
            ON administrativo.id_funcionario = funcionario.id_funcionario
            WHERE funcionario.id_estado_funcionario = 1 AND codigo LIKE ?");

        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $administratives = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $administratives;
    }


    function insertAdministrative($mysqli, $permissions, $password, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO administrativo (permisos, pass, id_funcionario) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $permissions, $password, $employee_id);

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    function changePasswordAdministrative($mysqli, $administrative_id, $password) {

        $stmt = $mysqli->prepare("UPDATE administrativo SET pass = ? WHERE id_administrativo = ?");
        $stmt->bind_param("si", $password, $administrative_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAdministrativeWithDocument($mysqli, $document) {

        $stmt = $mysqli->prepare("SELECT a.* FROM administrativo a INNER JOIN funcionario f ON a.id_funcionario = f.id_funcionario WHERE f.cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();

        $result = $stmt->get_result();
        $administrative = $result->fetch_assoc();
        $stmt->close();

        return $administrative;
    }

?>