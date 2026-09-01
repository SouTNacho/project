<?php

    session_start();
    
    require_once __DIR__ . "/../functions/element_functions.php";
    require_once __DIR__ . "/../models/element_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/element_update.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/element_update.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $new_code = trim($_POST['element_new_cod'] ?? '');
        $name = trim($_POST['element_name'] ?? '');
        $current_code = trim($_POST['element_current_cod'] ?? '');
        $type = trim($_POST['element_type'] ?? '');
        $subtype = trim($_POST['element_subtype'] ?? '');
        $description = trim($_POST['element_description'] ?? '');

        if (!validateElementCode($current_code)) {
            redirectionForError("El código actual no es válido");
        }

        if (!validateEmptyData($type)) {
            if (validateEmptyData($subtype)) {
                redirectionForError("El subtipo es obligatorio");
            }
        }

        if (validateEmptyData($type)) {
            if (!validateEmptyData($subtype)) {
                redirectionForError("El tipo es obligatorio");
            }
        }

        if (!validateEmptyData($new_code)) {
            if (!validateElementCode($new_code)) {
                redirectionForError("El código nuevo no es válido");
            }
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre ingresado no es válido");
            }
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripcion ingresada no es válida");
            }
        }

        

        $mysqli = connection_db();
        $element = findElementWithCode($mysqli, $current_code);

        if(!$element) {
            redirectWithError($mysqli, "El elemento no existe");
        }

        $element_name = findElementWithName($mysqli, $name);

        if ($element_name && $element['id_elemento'] !== $element_name['id_elemento']) {
            redirectWithError($mysqli, "Este nombre ya está registrado");
        }

        try {

            $new_code = keepOldValue($new_code, $element["codigo"]);
            $name = keepOldValue($name, $element["nombre"]);
            $type = keepOldValue($type, $element["tipo"]);
            $subtype = keepOldValue($subtype, $element["subtipo"]);
            $description = keepOldValue($description, $element["descripcion"]);

            updateElement($mysqli, $new_code, $name, $type, $subtype, $description, (int) $element['id_elemento']);

            $mysqli->close();
            $_SESSION["success"] = "Elemento actualizado correctamente";
            header("Location: /php/element_update.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();

            error_log( $e->getMessage());
            $_SESSION["errors"] = "Ocurrió un error al actualizar el elemento";
            header("Location: /php/element_update.php");
            exit();
        }

    }
    
?>