<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../functions/subtype_validations.php";
    require_once __DIR__ . "/../../conection.php";

    const SUBTYPE_FORM_PAGE = "/php/pages/element_subtype/subtype_register.php";
    const SUBTYPE_LIST_PAGE = "/php/pages/element_subtype/management_element_subtype.php";

    // Con $id > 0 vuelve al formulario en modo actualización; con 0, al formulario de registro.
    function redirectWithMessage($key, $message, $id = 0, $mysqli = null) {

        if ($mysqli) {
            $mysqli->close();
        }

        $_SESSION[$key] = $message;

        $url = SUBTYPE_FORM_PAGE . ($id > 0 ? "?id=" . $id : "");
        header("Location: " . $url);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: " . SUBTYPE_LIST_PAGE);
        exit();
    }

    $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {
        redirectWithMessage("errors", "El ID es incorrecto.");
    }

    $name = $_POST['subtype'] ?? '';
    $name = is_string($name) ? normalizeSubtypeName($name) : '';

    $error = validateSubtypeName($name);

    if ($error !== null) {
        redirectWithMessage("errors", $error, $id);
    }

    $mysqli = connection_db();

    try {

        $subtype = findSubtypeWithId($mysqli, $id);

        if (!$subtype) {
            redirectWithMessage("errors", "No existe este subtipo.", 0, $mysqli);
        }

        if ($name === $subtype['nombre']) {
            redirectWithMessage("errors", "El nombre ingresado es igual al actual.", $id, $mysqli);
        }

        // El tipo no se modifica: se busca un nombre repetido dentro del mismo tipo.
        $same_name = findSubtypeWithName($mysqli, $name, (int) $subtype['tipo']);

        if ($same_name && (int) $same_name['id_subtipo'] !== (int) $subtype['id_subtipo']) {
            redirectWithMessage("errors", "Ya existe un subtipo con ese nombre para este tipo.", $id, $mysqli);
        }

        updateSubtypeName($mysqli, $name, $id);

        redirectWithMessage("success", "Subtipo modificado correctamente.", $id, $mysqli);

    } catch (mysqli_sql_exception $e) {

        error_log("subtype_update.php: " . $e->getMessage());

        $message = (int) $e->getCode() === 1062
            ? "Ya existe un subtipo con ese nombre para este tipo."
            : "Ocurrió un error al actualizar el subtipo.";

        redirectWithMessage("errors", $message, $id, $mysqli);
    }