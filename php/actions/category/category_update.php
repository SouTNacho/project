<?php

    session_start();

    require_once __DIR__ . '/../../conection.php';
    require_once __DIR__ . '/../../models/category_model.php';

    $manage_page = '/php/pages/category/manage_categories.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . $manage_page);
        exit();
    }

    $category_id = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);

    if ($category_id === false || $category_id === null || $category_id < 1) {
        $_SESSION['errors'] = 'La categoría seleccionada no es válida.';
        header('Location: ' . $manage_page);
        exit();
    }

    $update_page = '/php/pages/category/category_update.php?id=' . $category_id;
    $name = trim($_POST['category_name'] ?? '');

    if (!preg_match('/^[^\p{C}]{1,50}$/u', $name)) {
        $_SESSION['errors'] = 'El nombre debe tener entre 1 y 50 caracteres válidos.';
        header('Location: ' . $update_page);
        exit();
    }

    $mysqli = connection_db();

    try {
        if (!findCategoryById($mysqli, $category_id)) {
            $mysqli->close();
            $_SESSION['errors'] = 'La categoría seleccionada ya no existe.';
            header('Location: ' . $manage_page);
            exit();
        }

        updateCategory($mysqli, $category_id, $name);
        $mysqli->close();
        $_SESSION['success'] = 'Categoría actualizada correctamente.';
        header('Location: ' . $manage_page);
        exit();
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        $_SESSION['errors'] = $exception->getCode() === 1062
            ? 'Ya existe una categoría con ese nombre.'
            : 'No se pudo actualizar la categoría.';
        header('Location: ' . $update_page);
        exit();
    }

?>