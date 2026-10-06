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

    $mysqli = connection_db();

    try {
        if (deleteCategory($mysqli, $category_id)) {
            $_SESSION['success'] = 'Categoría eliminada correctamente.';
        } else {
            $_SESSION['errors'] = 'La categoría ya no existe.';
        }
        $mysqli->close();
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        $_SESSION['errors'] = $exception->getCode() === 1451
            ? 'No se puede eliminar la categoría porque tiene documentos asociados.'
            : 'No se pudo eliminar la categoría.';
    }

    header('Location: ' . $manage_page);
    exit();

?>