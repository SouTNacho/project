<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/administrative_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/administrative/administrative_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/administrative/administrative_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $document = trim($_POST['administrative_document'] ?? '');
        $permissions = trim($_POST['administrative_permissions'] ?? '');
        $password = trim($_POST['administrative_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (validateEmptyData($document) ||  validateEmptyData($permissions) ||
            validateEmptyData($password) || validateEmptyData($confirm_password)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cédula ingresada no es válida.");
        }

        if (!validateShortString($permissions)) {
            redirectionForError("Los permisos no son válidos.");
        }

        if (!validatePassword($password)) {
            redirectionForError("La contraseña ingresada no es válida.");
        }

        if ($password !== $confirm_password) {
            redirectionForError("Las contraseñas no coinciden.");
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $mysqli = connection_db();
        $administrative = findAdministrativeWithDocument($mysqli, $document);

        if($administrative) {
            redirectWithError($mysqli, "Este administrativo ya está registrado.");
        }

        $employee = findEmployeeWithDocument($mysqli, $document);

        if(!$employee) {
            redirectWithError($mysqli, "El empleado no existe.");
        }

        try {

            $mysqli->begin_transaction();

            $id = insertAdministrative($mysqli, $permissions, $password_hash, (int) $employee['id_funcionario']);
            $administrative_code = 'FA' . str_pad($id, 8, '0', STR_PAD_LEFT);
            updateAdministrativeCode($mysqli, $id, $administrative_code);

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Administrativo registrado correctamente, el código correspondiente es: " .  $administrative_code;
            header("Location: /php/pages/administrative/administrative_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {


            $mysqli->rollback();
            $mysqli->close();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/administrative/administrative_form.php");
            exit();
        }

    }

?> 