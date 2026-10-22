<?php

    function findSubtypeWithName($mysqli, $name) {

        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtype = $result->fetch_assoc();
        $stmt->close();

        return $subtype;
    }

    function findSubtypeWithNameAndType($mysqli, $name, $type) {

        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento WHERE nombre = ? AND tipo = ?");
        $stmt->bind_param("si", $name, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtype = $result->fetch_assoc();
        $stmt->close();

        return $subtype;
    }

    function findActiveSubtypesWithType($mysqli, $type) {

        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento WHERE tipo = ? AND id_estado_subtipo_elemento = 1");
        $stmt->bind_param("i", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtypes;
    }

    function findSubtypeWithId($mysqli, $subtype_id) {

        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento WHERE id_subtipo_elemento = ?");
        $stmt->bind_param("i", $subtype_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtype = $result->fetch_assoc();
        $stmt->close();

        return $subtype;
    }

    function findAllSubtypes($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento");
        $stmt->execute();
        $result = $stmt->get_result();
        $subtypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtypes;
    }

    function findActiveSubtypes($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento WHERE id_estado_subtipo_elemento = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $subtypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtypes;
    }

    function insertSubtype($mysqli, $name, $type) {

        $stmt = $mysqli->prepare("INSERT INTO subtipo_elemento(nombre, tipo) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $type);
        $stmt->execute();
        $stmt->close();
    }

    function updateSubtype($mysqli, $name, $type, $subtype_id) {

        $stmt = $mysqli->prepare("UPDATE subtipo_elemento SET nombre = ?, tipo = ? WHERE id_subtipo_elemento = ?");
        $stmt->bind_param("sii", $name, $type, $subtype_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllSubtypesStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_subtipo_elemento");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateSubtype($mysqli, $subtype_id, $state_id) {
        
        $stmt = $mysqli->prepare("UPDATE subtipo_elemento SET id_estado_subtipo_elemento = ? WHERE id_subtipo_elemento = ?");
        $stmt->bind_param("ii", $state_id, $subtype_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllSubtypesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemento WHERE nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtypes;
    }

?>