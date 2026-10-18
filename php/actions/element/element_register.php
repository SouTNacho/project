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
        $description = trim($_POST['element_description'] ?? '');

        $type_text = $_POST['element_type'] ?? '';
        $type_text = is_string($type_text) ? mb_strtolower(trim($type_text)) : '';
        //mb_startolower convierte a minusculas
        //(para manejar y aceptar tanto biologico como BIOLÓGICO u otras formas)

        $subtype_text = $_POST['element_subtype'] ?? '';
        $subtype_text = is_string($subtype_text) ? trim($subtype_text) : '';

        if (validateEmptyData($code) || validateEmptyData($name) || validateEmptyData($type_text) ||
        validateEmptyData($subtype_text) || validateEmptyData($description)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateElementCode($code)) {
            redirectionForError("El código ingresado no es válido.");
        }

        if (!validateString($name)) {
            redirectionForError("El nombre ingresado no es válido.");
        }

        //se le asigna el 1 y 2 para cualquiera de las variantes
        $type = match ($type_text) {
            '1', 'biológico', 'biologico' => 1,
            '2', 'no biológico', 'no biologico' => 2,
            default => null
        };

        if ($type === null) {
            redirectionForError("El tipo seleccionado no es válido.");
        }

        // filter_var devuelve false si no es un entero solo
        $subtype = filter_var($subtype_text, FILTER_VALIDATE_INT);

        if ($subtype === false || $subtype <= 0) {
            redirectionForError("El subtipo seleccionado no es válido.");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida.");
        }

        $mysqli = connection_db();

        try {

            // El subtipo tiene que existir y pertenecer al tipo elegido
            $existingCorrect_subtype = findSubtypeWithId($mysqli, $subtype);

            if (!$existingCorrect_subtype) {
                redirectWithError($mysqli, "El subtipo seleccionado no existe.");
            }

            if ((int) $existingCorrect_subtype['tipo'] !== $type) {
                redirectWithError($mysqli, "El subtipo seleccionado no corresponde al tipo.");
            }

            $element = findElementWithCode($mysqli, $code);

            if($element) {
                redirectWithError($mysqli, "Este código ya esta registrado.");
            }

            $element_name = findElementWithName($mysqli, $name);

            if ($element_name) {
                redirectWithError($mysqli, "Este nombre ya esta registrado.");
            }

            insertElement($mysqli, $code, $name, $type, $subtype, $description);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/element/element_form.php");
            exit();
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Error al registrar.';
            header("Location: /php/pages/element/element_form.php");
            exit();
        }
    }
?>