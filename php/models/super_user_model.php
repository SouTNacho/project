<?php

    function findSuperUserWithId($mysqli, $super_user_id) {
        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_super_usuario = ?");
        $stmt->bind_param("i", $super_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_user = $result->fetch_assoc();
        $stmt->close();

        return $super_user;
    }

    function findSuperUserWithCode($mysqli, $code) {
        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE codigo = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_user = $result->fetch_assoc();
        $stmt->close();

        return $super_user;
    }

    function findAllSuperUsers($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM super_usuario");
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }



    function findAllSuperUsersStates($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM estado_super_usuario");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function findSuperUserWithName($mysqli, $name) {
        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_user = $result->fetch_assoc();
        $stmt->close();

        return $super_user;
    }

    function updateSuperUser($mysqli, $name, $permissions, $password, $super_user_id) {

        $stmt = $mysqli->prepare("UPDATE super_usuario SET nombre = ?, permisos = ?, pass = ? WHERE id_super_usuario = ?");
        $stmt->bind_param("sssi", $name, $permissions, $password, $super_user_id);
        $stmt->execute();
        $stmt->close();
    }


    function insertSuperUser($mysqli, $name, $permissions, $password) {
        
        $stmt = $mysqli->prepare("INSERT INTO super_usuario(nombre, permisos, pass) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $name, $permissions, $password);
        $stmt->execute();
        $id = $mysqli->insert_id;
        $stmt->close();

        return $id;
    }

    function insertSuperUserCode($mysqli, $code, $super_user_id) {
        
        $stmt = $mysqli->prepare("UPDATE super_usuario SET codigo = ? WHERE id_super_usuario = ?");
        $stmt->bind_param("si", $code, $super_user_id);
        $stmt->execute();
        $id = $mysqli->insert_id;
        $stmt->close();

        return $id;
    }

    function changeStateSuperUser($mysqli, $super_user_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE super_usuario SET id_estado_super_usuario = ? WHERE id_super_usuario = ?");
        $stmt->bind_param("ii", $state_id, $super_user_id);
        $stmt->execute();
        $stmt->close();
    }


    function findSuperUsersWithFilter($mysqli, $state_id, $phrase) {

    if ($state_id == 0 && $phrase === "null") {

        return findAllSuperUsers($mysqli);

    } else if ($state_id == 0) {

        $stmt = $mysqli->prepare("
            SELECT *
            FROM super_usuario
            WHERE nombre LIKE ?
        ");

        $search = "%" . $phrase . "%";
        $stmt->bind_param("s", $search);

    } else if ($phrase === "null") {

        $stmt = $mysqli->prepare("
            SELECT *
            FROM super_usuario
            WHERE id_estado_super_usuario = ?
        ");

        $stmt->bind_param("i", $state_id);

    } else {

        $stmt = $mysqli->prepare("
            SELECT *
            FROM super_usuario
            WHERE id_estado_super_usuario = ?
            AND nombre LIKE ?
        ");

        $search = "%" . $phrase . "%";
        $stmt->bind_param("is", $state_id, $search);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $super_users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $super_users;
}
?>