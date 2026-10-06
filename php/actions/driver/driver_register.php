<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/driver_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/driver/driver_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/driver/driver_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $document = trim($_POST['driver_document'] ?? '');
        $expiration = trim($_POST['driver_license_expiration'] ?? '');
        $category = trim($_POST['driver_license_category'] ?? '');
        $password = trim($_POST['driver_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (validateEmptyData($document) || validateEmptyData($expiration) || validateEmptyData($category) ||
            validateEmptyData($password) || validateEmptyData($confirm_password)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cédula ingresada no es válida.");
        }

        if (!validateDate($expiration)) {
            redirectionForError("La fecha de expiración no es válida.");
        }

        if (strlen($category) !== 1) {
            redirectionForError("La categoría no es válida.");
        }

        if (!validatePassword($password)) {
            redirectionForError("La contraseña ingresada no es válida.");
        }

        if ($password !== $confirm_password) {
            redirectionForError("Las contraseñas no coinciden.");
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $mysqli = connection_db();
        $driver = findDriverWithDocument($mysqli, $document);

        if($driver) {
            redirectWithError($mysqli, "Este conductor ya está registrado.");
        }

        $employee = findEmployeeWithDocument($mysqli, $document);

        if(!$employee) {
            redirectWithError($mysqli, "El empleado no existe.");
        }

        try {

            $mysqli->begin_transaction();

            $id = insertDriver($mysqli, $expiration, $category, $password_hash, (int) $employee['id_funcionario']);
            $driver_code = 'DR' . str_pad($id, 8, '0', STR_PAD_LEFT);
            updateDriverCode($mysqli, $id, $driver_code);

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Conductor registrado correctamente, el código correspondiente es: " .  $driver_code;
            header("Location: /php/pages/driver/driver_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {


            $mysqli->rollback();
            $mysqli->close();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/driver/driver_form.php");
            exit();
        }

    }

?> 