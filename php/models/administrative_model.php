<?php

    function findAdministrativeWithCode($mysqli, $administrative_code) {

        $stmt = $mysqli->prepare("SELECT * FROM administrativo WHERE codigo = ?");
        $stmt->bind_param("s", $administrative_code);
        $stmt->execute();

        $result = $stmt->get_result();
        $administrative = $result->fetch_assoc();
        $stmt->close();

        return $administrative;
    }


    function findAdministrativeWithEmployeeId($mysqli, $employee_id) {

        $stmt = $mysqli->prepare("SELECT * FROM administrativo WHERE id_funcionario = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $administrative = $result->fetch_assoc();
        $stmt->close();

        return $administrative;
    }


    function findAllAdministratives($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM administrativo");
        $stmt->execute();

        $result = $stmt->get_result();
        $administratives = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $administratives;
    }


    function findAllAdministrativesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare(
            "SELECT * FROM administrativo WHERE codigo LIKE ?"
        );

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


    function updateAdministrativeCode($mysqli, $administrative_id, $administrative_code) {

        $stmt = $mysqli->prepare("UPDATE administrativo SET codigo = ? WHERE id_administrativo = ?");
        $stmt->bind_param("si", $administrative_code, $administrative_id);
        $stmt->execute();
        $stmt->close();
    }


    function updateAdministrative($mysqli, $administrative_id, $permissions, $employee_id) {

        $stmt = $mysqli->prepare("UPDATE administrativo SET permisos = ?, id_funcionario = ? WHERE id_administrativo = ?");
        $stmt->bind_param( "sii", $permissions, $employee_id, $administrative_id);
        $stmt->execute();
        $stmt->close();
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