<?php

    function findElementWithCode($mysqli, $code) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE codigo = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function findAllElements($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findAllElementsStates($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM estado_elemento");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function findElementWithId($mysqli, $element_id) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_elemento = ?");
        $stmt->bind_param("s", $element_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function findElementWithName($mysqli, $name) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function updateElement($mysqli, $new_code, $name, $type, $subtype, $description, $element_id) {

        $stmt = $mysqli->prepare("UPDATE elemento SET codigo = ?, nombre = ?, tipo = ?,
            subtipo = ?, descripcion = ? WHERE id_elemento = ?");
        $stmt->bind_param("sssssi", $new_code, $name, $type, $subtype, $description, $element_id);
        $stmt->execute();
        $stmt->close();
    }

    // Despues manejar subtipo como tabla y hacer el crud para subtipo, solo el super user puede hacer el crud de subtipo
    function insertElement($mysqli, $code, $name, $type, $subtype, $description) {
        
        $stmt = $mysqli->prepare("INSERT INTO elemento(codigo, nombre, tipo, subtipo, descripcion) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $code, $name, $type, $subtype, $description);
        $stmt->execute();
        $stmt->close();
    }

    function changeStateElement($mysqli, $element_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE elemento SET id_estado_elemento = ? WHERE id_elemento = ?");
        $stmt->bind_param("ii", $state_id, $element_id);
        $stmt->execute();
        $stmt->close();
    }

?>