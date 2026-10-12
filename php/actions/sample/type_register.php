<?php
session_start();


require_once __DIR__ . "/../../models/sample_model.php";
require_once __DIR__ . "/../../functions/validations.php";
require_once __DIR__ . "/../../conection.php";
 

 
    // Saca espacios de los extremos y colapsa espacios repetidos.
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

 
    function redirectWithMessage($key, $message, $mysqli = null) {
 
        if ($mysqli) {
            $mysqli->close();
        }
 
        $_SESSION[$key] = $message;
        header("Location: " . "/php/pages/sample_type/type_register.php");
        exit();
    }
 
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: " . "/php/pages/sample_type/manage_sample_type.php");
        exit();
    }
 
    $name = $_POST['sample_type'] ?? '';
    $name = is_string($name) ? normalizeTypeName($name) : '';
 
    // filter_var devuelve false si no es un entero puro (evita que "1abc" pase como 1)
 
    /*if (!in_array($type, [1, 2], true)) {
        redirectWithMessage("errors", "El tipo seleccionado no es válido.");
    }*/
 
    $error = validateTypeName($name);
 
    if ($error !== null) {
        redirectWithMessage("errors", $error);
    }
 
    $mysqli = connection_db();
 
    try {

    if (findTypeWithName($mysqli, $name)) {
        redirectWithMessage ("errors", "ya existe un tipo con el mismo nombre", $mysqli);
    }
        
 
        insertType($mysqli, $name);
 
        redirectWithMessage("success", "Tipo registrado correctamente.", $mysqli);
 
    } catch (mysqli_sql_exception $e) {
 
        error_log("type_register.php: " . $e->getMessage());
 
        // 1062 = clave duplicada (si se agregó la clave única nombre + tipo)
        $message = (int) $e->getCode() === 1062
            ? "Este tipo ya existe"
            : "Ocurrió un error al registrar el tipo.";
 
        redirectWithMessage("errors", $message, $mysqli);
    }

