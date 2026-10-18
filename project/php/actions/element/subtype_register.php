<?php
session_start();


require_once __DIR__ . "/../../models/element_model.php";
require_once __DIR__ . "/../../functions/validations.php";
require_once __DIR__ . "/../../conection.php";
 

 
    // Saca espacios de los extremos y colapsa espacios repetidos.
    function normalizeSubtypeName($name) {
        return preg_replace('/\s+/u', ' ', trim($name)) ?? '';
    }
 
    // Devuelve el mensaje de error o null si el nombre es válido.
    function validateSubtypeName($name) {
 
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

 
    function redirectWithMessage($key, $message, $mysqli = null) {
 
        if ($mysqli) {
            $mysqli->close();
        }
 
        $_SESSION[$key] = $message;
        header("Location: " . "/php/pages/element_subtype/subtype_register.php");
        exit();
    }
 
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: " . "/php/pages/element_subtype/management_element_subtype.php");
        exit();
    }
 
    $name = $_POST['subtype'] ?? '';
    $name = is_string($name) ? normalizeSubtypeName($name) : '';
 
    // filter_var devuelve false si no es un entero puro (evita que "1abc" pase como 1)
    $type = filter_var($_POST['subtype_type'] ?? '', FILTER_VALIDATE_INT);
 
    if (!in_array($type, [1, 2], true)) {
        redirectWithMessage("errors", "El tipo seleccionado no es válido.");
    }
 
    $error = validateSubtypeName($name);
 
    if ($error !== null) {
        redirectWithMessage("errors", $error);
    }
 
    $mysqli = connection_db();
 
    try {
 
        if (findSubtypeWithName($mysqli, $name, $type)) {
            redirectWithMessage("errors", "Este subtipo ya existe para el tipo seleccionado.", $mysqli);
        }
 
        insertSubtype($mysqli, $name, $type);
 
        redirectWithMessage("success", "Subtipo registrado correctamente.", $mysqli);
 
    } catch (mysqli_sql_exception $e) {
 
        error_log("subtype_register.php: " . $e->getMessage());
 
        // 1062 = clave duplicada (si se agregó la clave única nombre + tipo)
        $message = (int) $e->getCode() === 1062
            ? "Este subtipo ya existe para el tipo seleccionado."
            : "Ocurrió un error al registrar el subtipo.";
 
        redirectWithMessage("errors", $message, $mysqli);
    }