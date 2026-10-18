<?php

    function findAllDocumentActions($mysqli) {

        $stmt = $mysqli->prepare("SELECT id_accion, nombre FROM accion ORDER BY nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        $actions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $actions;
    }

    function findDocumentActionById($mysqli, $action_id) {

        $stmt = $mysqli->prepare("SELECT id_accion, nombre FROM accion WHERE id_accion = ?");
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $action = $result->fetch_assoc();
        $stmt->close();

        return $action;
    }

    function insertDocumentAction($mysqli, $name) {

        $stmt = $mysqli->prepare("INSERT INTO accion(nombre) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    function updateDocumentAction($mysqli, $action_id, $name) {

        $stmt = $mysqli->prepare("UPDATE accion SET nombre = ? WHERE id_accion = ?");
        $stmt->bind_param("si", $name, $action_id);
        $stmt->execute();
        $stmt->close();
    }

    function isDocumentActionInUse($mysqli, $action_id) {

        $stmt = $mysqli->prepare("SELECT id_administra_documento FROM administra_documento
                                WHERE id_accion = ? LIMIT 1");
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $is_in_use = $result->num_rows > 0;
        $stmt->close();

        return $is_in_use;
    }

    function isRequiredDocumentAction($action_id) {

        return in_array((int) $action_id, [1, 2, 3, 4, 5], true);
    }

    function deleteDocumentAction($mysqli, $action_id) {

        $stmt = $mysqli->prepare("DELETE FROM accion WHERE id_accion = ?");
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        $stmt->close();
    }

?>
