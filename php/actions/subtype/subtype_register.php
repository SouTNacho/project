<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');
    
    require_once __DIR__ . "/../../models/subtype_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element_subtype/subtype_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element_subtype/subtype_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST['subtype_name'] ?? $_POST['subtype'] ?? '');
        $type = (int) ($_POST['subtype_type'] ?? 0);

        if (validateEmptyData($name) || $type === 0) {
            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateString($name)) {

            redirectionForError("El nombre ingresado no es válido.");
        }

        if ($type !== 1 && $type !== 2) {

            redirectionForError("El tipo ingresado no es válido.");
        }

        $mysqli = connection_db();
        $subtype = findSubtypeWithNameAndType($mysqli, $name, $type);

        if($subtype) {
            redirectWithError($mysqli, "Este subtipo ya está registrado.");
        }

        try {

            insertSubtype($mysqli, $name, $type);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/element_subtype/subtype_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/element_subtype/subtype_register.php");
            exit();
        }

    }

?> 