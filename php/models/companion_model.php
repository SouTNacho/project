<?php

    function findCompanionWithDocument($mysqli, $companion_document) {

        $stmt = $mysqli->prepare("SELECT * FROM companion WHERE cedula = ?");
        $stmt->bind_param("s", $companion_document);
        $stmt->execute();
        $result = $stmt->get_result();
        $companion = $result->fetch_assoc();
        $stmt->close();

        return $companion;
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

    function findAllCompanions($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM acompaniante");
        $stmt->execute();
        $result = $stmt->get_result();
        $companions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $companions;
    }

    function insertCompanion($mysqli, $name, $last_name, $companion_document) {

        $stmt = $mysqli->prepare("INSERT INTO acompaniante(cedula, nombre, apellido) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $companion_document, $name, $last_name);
        $stmt->execute();
        $stmt->close();
    }

    function updateCompanion($mysqli, $companion_id, $name, $last_name, $companion_document) {

        $stmt = $mysqli->prepare("UPDATE acompaniante SET cedula = ?, nombre = ?, apellido = ? WHERE id_acompaniante = ?");
        $stmt->bind_param("ssii", $companion_document, $name, $last_name, $companion_id);
        $stmt->execute();
        $stmt->close();
    }

?>