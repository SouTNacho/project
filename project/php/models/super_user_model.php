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
        $stmt->close();
    }

    function changeStateSuperUser($mysqli, $super_user_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE super_usuario SET id_estado_super_usuario = ? WHERE id_super_usuario = ?");
        $stmt->bind_param("ii", $state_id, $super_user_id);
        $stmt->execute();
        $stmt->close();
    }

    function findActiveSuperUsers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_estado_super_usuario = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

    function findInactiveSuperUsers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_estado_super_usuario = 2");
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

    function findDeletedSuperUsers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_estado_super_usuario = 3");
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

    function findAllSuperUsersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

    function findActiveSuperUsersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_estado_super_usuario = 1 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

    function findInactiveSuperUsersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_estado_super_usuario = 2 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

    function findDeletedSuperUsersWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM super_usuario WHERE id_estado_super_usuario = 3 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $super_users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $super_users;
    }

?>