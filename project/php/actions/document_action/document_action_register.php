<?php

    session_start();

    require_once __DIR__ . '/../../conection.php';
    require_once __DIR__ . '/../../models/document_action_model.php';

    $register_page = '/php/pages/document_action/document_action_register.php';
    $manage_page = '/php/pages/document_action/manage_document_actions.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . $manage_page);
        exit();
    }

    $name = trim($_POST['action_name'] ?? '');

    if (!preg_match('/^[^\p{C}]{1,50}$/u', $name)) {
        $_SESSION['errors'] = 'El nombre debe tener entre 1 y 50 caracteres válidos.';
        header('Location: ' . $register_page);
        exit();
    }

    $mysqli = connection_db();

    try {
        insertDocumentAction($mysqli, $name);
        $mysqli->close();
        $_SESSION['success'] = 'Acción de documento registrada correctamente.';
        header('Location: ' . $manage_page);
        exit();
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        $_SESSION['errors'] = $exception->getCode() === 1062
            ? 'Ya existe una acción con ese nombre.'
            : 'No se pudo registrar la acción de documento.';
        header('Location: ' . $register_page);
        exit();
    }

?>
