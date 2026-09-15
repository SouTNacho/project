<?php

    function findCompanionWithDocument($mysqli, $document) {

        $stmt = $mysqli->prepare("SELECT * FROM acompaniante WHERE cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        $ambulance = $result->fetch_assoc();
        $stmt->close();

        return $ambulance;
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

?>