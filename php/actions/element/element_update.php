<?php
    session_start();

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element/element_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element/element_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $code = trim($_POST['element_code'] ?? '');
        $name = trim($_POST['element_name'] ?? '');
        $type = trim($_POST['element_type'] ?? '');
        $subtype = trim($_POST['element_subtype'] ?? '');
        $description = trim($_POST['element_description'] ?? '');
        
        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/element/element_form.php");
            exit();
        }

        if (!validateEmptyData($code)) {
            if (!validateElementCode($code)) {
                redirectionForError("El código nuevo no es válido.", $id);
            }
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($type)) {

            if (!validateString($type)) {
                redirectionForError("El tipo ingresado no es válido.", $id);
            }

            if (validateEmptyData($subtype)) {
                redirectionForError("El subtipo es obligatorio.", $id);
            }

            if (!validateString($subtype)) {
                redirectionForError("El subtipo ingresado no es válido.", $id);
            }
        }

        if (validateEmptyData($type) && !validateEmptyData($subtype)) {
            redirectionForError("El tipo es obligatorio.", $id);
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripción ingresada no es válida.", $id);
            }
        }

        $mysqli = connection_db();
        $element = findElementWithId($mysqli, $id);

        if(!$element) {
            redirectWithError($mysqli, "No existe este registro.", $id);
        }

        if ((int) $element['id_estado_elemento'] === 3) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
        }

        if (!validateEmptyData($code)) {
            $element_code = findElementWithCode($mysqli, $code);

            if($element_code && (int) $element['id_elemento'] !== (int) $element_code['id_elemento']) {
                redirectWithError($mysqli, "Este código ya existe.", $id);
            }
        }

        if (!validateEmptyData($name)) {

            $element_name = findElementWithName($mysqli, $name);

            if ($element_name && (int) $element['id_elemento'] !== (int) $element_name['id_elemento']) {
                redirectWithError($mysqli, "El nombre ingresado ya está registrado.", $id);
            }
        }

        try {

            $code = keepOldValue($code, $element["codigo"]);
            $name = keepOldValue($name, $element["nombre"]);
            $type = keepOldValue($type, $element["tipo"]);
            $subtype = keepOldValue($subtype, $element["subtipo"]);
            $description = keepOldValue($description, $element["descripcion"]);

            updateElement($mysqli, $code, $name, $type, $subtype, $description, (int) $element['id_elemento']);

            $mysqli->close();
            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/element/element_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/element/element_form.php?id=" . $id);
            exit();
        }
    }
?>