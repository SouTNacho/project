<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../conection.php";


    function normalizeTypeName($name) {
        return preg_replace('/\s+/u', ' ', trim($name)) ?? '';
    }

    
 
    // Devuelve el mensaje de error o null si el nombre es válido.
    function validateTypeName($name) {
 
        if ($name === '') {
            return "El nombre es obligatorio.";
        }
 
        if (mb_strlen($name) > 80) {
            return "El nombre no puede superar los " . 80 . " caracteres.";
        }
 
        if (!preg_match('/^[\p{L}\p{N} .,\-()\/&]+$/u', $name)) {
            return "El nombre contiene caracteres no permitidos.";
        }
 
        return null;
    }
    
    // Con $id > 0 vuelve al formulario en modo actualización; con 0, al formulario de registro.
    function redirectWithMessage($key, $message, $id = 0, $mysqli = null) {

        if ($mysqli) {
            $mysqli->close();
        }

        $_SESSION[$key] = $message;

        $url = "/php/pages/sample_type/type_register.php" . ($id > 0 ? "?id=" . $id : "");
        header("Location: " . $url);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: " . "/php/pages/sample_type/manage_sample_type.php");
        exit();
    }

    $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {
        redirectWithMessage("errors", "El ID es incorrecto.");
    }

    $name = $_POST['sample_type'] ?? '';
    $name = is_string($name) ? normalizeTypeName($name) : '';

    $error = validateTypeName($name);

    if ($error !== null) {
        redirectWithMessage("errors", $error, $id);
    }

    $mysqli = connection_db();

    try {

        $type = findTypeWithId($mysqli, $id);

        if (!$type) {
            redirectWithMessage("errors", "No existe este tipo.", 0, $mysqli);
        }

        if ($name === $type['nombre']) {
            redirectWithMessage("errors", "El nombre ingresado es igual al actual.", $id, $mysqli);
        }

        // El tipo no se modifica: se busca un nombre repetido dentro del mismo tipo.
        $same_name = findTypeWithName($mysqli, $name);

        if ($same_name && (int) $same_name['id_tipo'] !== $id) {
            redirectWithMessage("errors", "Ya existe un tipo con ese nombre para este tipo.", $id, $mysqli);
        }

        updateTypeName($mysqli, $name, $id);

        redirectWithMessage("success", "Tipo modificado correctamente.", $id, $mysqli);

    } catch (mysqli_sql_exception $e) {

        error_log("type_update.php: " . $e->getMessage());

        $message = (int) $e->getCode() === 1062
            ? "Ya existe un tipo con ese nombre para este tipo."
            : "Ocurrió un error al actualizar el tipo.";

        redirectWithMessage("errors", $message, $id, $mysqli);
    }