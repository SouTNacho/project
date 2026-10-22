<?php

    function insertContactMessage($mysqli, $name, $email, $content) {

        $stmt = $mysqli->prepare("INSERT INTO mensaje_contacto (nombre, correo, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $content);
        $stmt->execute();
        $stmt->close();
    }

    function findAllMessages($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto");
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }

    function findNonResponseMessages($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto WHERE respondido = false");
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }

    function findResponseMessages($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto WHERE respondido = true");
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }

    function findAllMessagesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto WHERE nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }

    function findNonResponseMessagesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto WHERE respondido = false AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }

    function findResponseMessagesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto WHERE respondido = true AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }

    function updateMessage($mysqli, $response, $message_id) {

        $stmt = $mysqli->prepare("UPDATE mensaje_contacto SET respondido = ? WHERE id_mensaje = ?");
        $stmt->bind_param("si", $response, $message_id);
        $stmt->execute();
        $stmt->close();
    }

    function findMessageWithId($mysqli, $message_id) {

        $stmt = $mysqli->prepare("SELECT * FROM mensaje_contacto WHERE id_mensaje = ?");
        $stmt->bind_param("i", $message_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $message = $result->fetch_assoc();
        $stmt->close();

        return $message;
    }

    function changeStateMessage($mysqli, $message_id) {
        
        $stmt = $mysqli->prepare("UPDATE mensaje_contacto SET respondido = true WHERE id_mensaje = ?");
        $stmt->bind_param("i", $message_id);
        $stmt->execute();
        $stmt->close();
    }

?>