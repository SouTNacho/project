<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../models/subtype_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/subtype/subtype_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/subtype/subtype_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim((string) $newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $name = trim($_POST['subtype_name'] ?? '');
        $type = trim($_POST['subtype_type'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto.';
            header("Location: /php/pages/subtype/subtype_form.php");
            exit();
        }

        if (validateEmptyData($name) && validateEmptyData($type)) {
            redirectionForError("Debe modificar al menos un campo.", $id);
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre no es válido.", $id);
            }
        }

        if (!validateEmptyData($type)) {
            if (!in_array((int) $type, [1, 2], true)) {
                redirectionForError("El tipo no es válido.", $id);
            }
        }

        if (!validateEmptyData($type) && validateEmptyData($name)) {
            redirectionForError("Debe completar el nombre para modificar el tipo.", $id);
        }

        $mysqli = connection_db();
        $subtype = findSubtypeWithId($mysqli, $id);

        if (!$subtype) {
            redirectWithError($mysqli, "El subtipo no está registrado.", $id);
        }

        if (!validateEmptyData($name) || !validateEmptyData($type)) {

            $name = keepOldValue($name, $subtype['nombre']);
            $type = (int) keepOldValue($type, (string) $subtype['tipo']);
            $subtype_existing = findSubtypeWithNameAndType($mysqli, $name, $type);

            if ($subtype_existing && (int) $subtype_existing['id_subtipo_elemento'] !== (int) $subtype['id_subtipo_elemento']) {
                redirectWithError($mysqli, "Este subtipo ya está registrado.", $id);
            }
        }

        try {

            updateSubtype($mysqli, $name, $type, $id);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/subtype/subtype_form.php?id=" . $id);
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUÉS QUITAR EL MENSAJE
            error_log("Error al actualizar subtipo: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/subtype/subtype_form.php?id=" . $id);
            exit();
        }
    }

?>