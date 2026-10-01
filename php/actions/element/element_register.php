<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element/element_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element/element_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_POST['element_code'] ?? '');
        $name = trim($_POST['element_name'] ?? '');
        $type = trim($_POST['element_type'] ?? '');
        $subtype = trim($_POST['element_subtype'] ?? '');
        $description = trim($_POST['element_description'] ?? '');

        if (validateEmptyData($code) || validateEmptyData($name) || validateEmptyData($type) ||
        validateEmptyData($subtype) || validateEmptyData($description)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateElementCode($code)) {
            redirectionForError("El código ingresado no es válido.");
        }

        if (!validateString($name)) {
            redirectionForError("El nombre ingresado no es válido.");
        }

        if (!validateString($type)) {
            redirectionForError("El tipo seleccionado no es válido.");
        }

        if (!validateString($subtype)) {
            redirectionForError("El subtipo seleccionado no es válido.");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida.");
        }

        $mysqli = connection_db();
        $element = findElementWithCode($mysqli, $code);

        if($element) {
            redirectWithError($mysqli, "Este código ya esta registrado.");
        }

        $element_name = findElementWithName($mysqli, $name);

        if ($element_name) {
            redirectWithError($mysqli, "Este nombre ya esta registrado.");
        }

        try {

            insertElement($mysqli, $code, $name, $type, $subtype, $description);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/element/element_form.php");
            exit();
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/element/element_form.php");
            exit();
        }
    }
?>