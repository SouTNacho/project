<?php

    session_start();

    require_once __DIR__ . '/../../conection.php';
    require_once __DIR__ . '/../../models/document_action_model.php';

    $manage_page = '/php/pages/document_action/manage_document_actions.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . $manage_page);
        exit();
    }

    $action_id = filter_var($_POST['action_id'] ?? null, FILTER_VALIDATE_INT);

    if ($action_id === false || $action_id === null || $action_id < 1) {
        $_SESSION['errors'] = 'La acción seleccionada no es válida.';
        header('Location: ' . $manage_page);
        exit();
    }

    $update_page = '/php/pages/document_action/document_action_update.php?id=' . $action_id;
    $name = trim($_POST['action_name'] ?? '');

    if (!preg_match('/^[^\p{C}]{1,50}$/u', $name)) {
        $_SESSION['errors'] = 'El nombre debe tener entre 1 y 50 caracteres válidos.';
        header('Location: ' . $update_page);
        exit();
    }

    $mysqli = connection_db();

    try {
        if (!findDocumentActionById($mysqli, $action_id)) {
            $mysqli->close();
            $_SESSION['errors'] = 'La acción seleccionada ya no existe.';
            header('Location: ' . $manage_page);
            exit();
        }

        updateDocumentAction($mysqli, $action_id, $name);
        $mysqli->close();
        $_SESSION['success'] = 'Acción de documento actualizada correctamente.';
        header('Location: ' . $manage_page);
        exit();
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        $_SESSION['errors'] = $exception->getCode() === 1062
            ? 'Ya existe una acción con ese nombre.'
            : 'No se pudo actualizar la acción de documento.';
        header('Location: ' . $update_page);
        exit();
    }

?>
