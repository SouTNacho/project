<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/contact_model.php";
    require_once __DIR__ . "/../../conection.php";

    $name = $_POST['contact_name'] ?? "";
    $email = $_POST['contact_email'] ?? "";
    $message = $_POST['contact_message'] ?? "";

    if (validateEmptyData($name) || validateEmptyData($email) || validateEmptyData($message)) {
        echo json_encode([
            'succes' => false,
            'message' => 'Todos los campos son obligatorios.'
        ]);
        exit;
    }

    if (!validateName($name)) {
        echo json_encode([
            'succes' => false,
            'message' => 'El nombre ingresado no es válido.'
        ]);
        exit;
    }

    if (!validateEmail($email)) {
        echo json_encode([
            'succes' => false,
            'message' => 'El email ingresado no es válido.'
        ]);
        exit;
    }

    if (!validateMessage($message)) {
        echo json_encode([
            'succes' => false,
            'message' => 'El mensaje ingresado no es válido.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        insertContactMessage($mysqli, $name, $email, $message);
        $mysqli->close();

        echo json_encode([
            'success' => true,
            'message' => 'El mensaje se ha enviado exitosamente.'
        ]);

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage() . ' Ha ocurrido un error al enviar el mensaje.'
        ]);
    }

?>