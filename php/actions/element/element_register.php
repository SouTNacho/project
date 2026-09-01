<?php

    session_start();
    
    require_once __DIR__ . "/../functions/element_functions.php";
    require_once __DIR__ . "/../models/element_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/element_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/element_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_POST['element_cod'] ?? '');
        $name = trim($_POST['element_name'] ?? '');
        $type = trim($_POST['element_type'] ?? '');
        $subtype = trim($_POST['element_subtype'] ?? '');
        $description = trim($_POST['element_description'] ?? '');
        // Si lo operamos con Estado al paciente tenemos que hacerlo int y setiarlo en 1 por defoult

        if (validateEmptyData($code) || validateEmptyData($name) || validateEmptyData($type) ||
        validateEmptyData($subtype) || validateEmptyData($description)) {

            redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateElementCode($code)) {
            redirectionForError("El código ingresado no es válido");
        }

        if (!validateString($name)) {
            redirectionForError("El nombre ingresado no es válido");
        }

        if (!validateString($type)) {
            redirectionForError("El tipo seleccionado no es válido");
        }

        if (!validateString($subtype)) {
            redirectionForError("La subtipo seleccionado no es válida");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida");
        }

        $mysqli = connection_db();
        $element = findElementWithCode($mysqli, $code);

        if($element) {
            redirectWithError($mysqli, "Ya existe un elemento asociado a ese código");
        }

        if ($element['nombre'] === $name) {
            redirectWithError($mysqli, "Ya existe un elemento con este nombre asociado");
        }

        try {

            insertElement($mysqli, $code, $name, $type, $subtype, $description);

            $mysqli->close();

            $_SESSION["success"] = "Elemento registrado correctamente";
            header("Location: /php/element_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            $mysqli->close();
            
            error_log( $e->getMessage());
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar el elemento';
            header("Location: /php/element_register.php");
            exit();
        }

    }
    
?>