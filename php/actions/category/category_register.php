<?php

    session_start();

    require_once __DIR__ . '/../../conection.php';
    require_once __DIR__ . '/../../models/category_model.php';

    $register_page = '/php/pages/category/category_register.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /php/pages/category/manage_categories.php');
        exit();
    }

    $name = trim($_POST['category_name'] ?? '');

    if (!preg_match('/^[^\p{C}]{1,50}$/u', $name)) {
        $_SESSION['errors'] = 'El nombre debe tener entre 1 y 50 caracteres válidos.';
        header('Location: ' . $register_page);
        exit();
    }

    $mysqli = connection_db();

    try {
        insertCategory($mysqli, $name);
        $mysqli->close();
        $_SESSION['success'] = 'Categoría registrada correctamente.';
        header('Location: /php/pages/category/manage_categories.php');
        exit();
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        $_SESSION['errors'] = $exception->getCode() === 1062
            ? 'Ya existe una categoría con ese nombre.'
            : 'No se pudo registrar la categoría.';
        header('Location: ' . $register_page);
        exit();
    }

?>