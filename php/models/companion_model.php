<?php

    function findCompanionWithDocument($mysqli, $document) {

        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        $companion = $result->fetch_assoc();
        $stmt->close();

        return $companion;
    }

    function insertCompanion($mysqli, $document, $first_name, $last_name) {

        $stmt = $mysqli->prepare("INSERT INTO acompaniante(cedula, nombre, apellido) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $document, $first_name, $last_name);
        $stmt->execute();
        $stmt->close();
    }

    function updateCompanion($mysqli, $new_document, $first_name, $last_name, $companion_id) {

        $stmt = $mysqli->prepare("UPDATE acompaniante SET cedula = ?, nombre = ?, apellido = ? WHERE id_acompaniante = ?");
        $stmt->bind_param("sssi", $new_document, $first_name, $last_name, $companion_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllCompanions($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM acompaniante");
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findActiveCompanions($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_estado_acompaniante = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findInactiveCompanions($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_estado_acompaniante = 2");
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findDeletedCompanions($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_estado_acompaniante = 3");
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findAllCompanionsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findActiveCompanionsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_estado_acompaniante = 1 AND cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findInactiveCompanionsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_estado_acompaniante = 2 AND cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findDeletedCompanionsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_estado_acompaniante = 3 AND cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function findAllCompanionsStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_acompaniante");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function findCompanionWithId($mysqli, $companion_id) {

        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE id_acompaniante = ?");
        $stmt->bind_param("i", $companion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $companion = $result->fetch_assoc();
        $stmt->close();

        return $companion;
    }

    function changeStateCompanion($mysqli, $companion_id, $state_id) {
        
        $stmt = $mysqli->prepare("UPDATE acompaniante SET id_estado_acompaniante = ? WHERE id_acompaniante = ?");
        $stmt->bind_param("ii", $state_id, $companion_id);
        $stmt->execute();
        $stmt->close();
    }

?>