<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../models/category_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/category/category_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/category/category_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = trim($_GET['id'] ?? '');
        $name = trim($_POST['category_name'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/category/category_form.php");
            exit();
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre no es válido.", $id);
            }
        }

        $mysqli = connection_db();
        $category = findCategoryWithId($mysqli, $id);

        if (!$category) {
            redirectWithError( $mysqli, "El categoria no está registrada.", $id);
        }

        if (!validateEmptyData($name)) {
            $category_name = findCategoryWithName($mysqli, $name);

            if ($category_name && (int) $category_name['id_categoria'] !== (int) $category['id_categoria']) {
                redirectWithError($mysqli, "Este nombre ya está registrado.", $id);
            }
        }

        try {

            updatecategory($mysqli, $name, $category['id_categoria']);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/category/category_form.php?id=" . $id);
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] ="Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/category/category_form.php?id=" . $id);
            exit();
        }
    }

?>