<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');
    
    require_once __DIR__ . "/../../models/category_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/category/category_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/category/category_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST['category_name'] ?? '');

        if (validateEmptyData($name)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateString($name)) {

            redirectionForError("El nombre ingresado no es válido.");
        }

        $mysqli = connection_db();
        $category = findCategoryWithName($mysqli, $name);

        if($category) {
            redirectWithError($mysqli, "Esta categoría ya está registrada.");
        }

        try {

            insertCategory($mysqli, $name);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/category/category_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/category/category_form.php");
            exit();
        }

    }

?> 