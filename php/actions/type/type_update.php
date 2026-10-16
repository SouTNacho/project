<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../models/type_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/type/type_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/type/type_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = trim($_GET['id'] ?? '');
        $name = trim($_POST['type_name'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/type/type_form.php");
            exit();
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre no es válido.", $id);
            }
        }

        $mysqli = connection_db();
        $type = findTypeWithId($mysqli, $id);

        if (!$type) {
            redirectWithError( $mysqli, "El tipo no está registrado.", $id);
        }

        if (!validateEmptyData($name)) {
            $type_name = findtypeWithName($mysqli, $name);

            if ($type_name && (int) $type_name['id_tipo'] !== (int) $type['id_tipo']) {
                redirectWithError($mysqli, "Este nombre ya está registrado.", $id);
            }
        }

        try {

            updateType($mysqli, $name, $type['id_tipo']);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/type/type_form.php?id=" . $id);
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] ="Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/type/type_form.php?id=" . $id);
            exit();
        }
    }

?>