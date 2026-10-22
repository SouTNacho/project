<?php

    function findTypeWithName($mysqli, $name) {

        $stmt = $mysqli->prepare("SELECT * FROM tipo_muestra WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $type = $result->fetch_assoc();
        $stmt->close();

        return $type;
    }

    function findTypeWithId($mysqli, $type_id) {

        $stmt = $mysqli->prepare("SELECT * FROM tipo_muestra WHERE id_tipo_muestra = ?");
        $stmt->bind_param("i", $type_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $type = $result->fetch_assoc();
        $stmt->close();

        return $type;
    }

    function findAllTypes($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM tipo_muestra");
        $stmt->execute();
        $result = $stmt->get_result();
        $types = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $types;
    }

    function findActiveTypes($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM tipo_muestra WHERE id_estado_tipo_muestra = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $types = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $types;
    }

    function insertType($mysqli, $name) {

        $stmt = $mysqli->prepare("INSERT INTO tipo_muestra(nombre) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    function updateType($mysqli, $name, $type_id) {

        $stmt = $mysqli->prepare("UPDATE tipo_muestra SET nombre = ? WHERE id_tipo_muestra = ?");
        $stmt->bind_param("si", $name, $type_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllTypesStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_tipo_muestra");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateType($mysqli, $type_id, $state_id) {
        
        $stmt = $mysqli->prepare("UPDATE tipo_muestra SET id_estado_tipo_muestra = ? WHERE id_tipo_muestra = ?");
        $stmt->bind_param("ii", $state_id, $type_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllTypesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM tipo_muestra WHERE nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $types = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $types;
    }

?>