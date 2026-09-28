<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/super_user_functions.php";
    require_once __DIR__ . "/../../models/super_user_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/super_user/super_user_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/super_user/super_user_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST['super_user_name'] ?? '');
        $permissions = trim($_POST['super_user_permissions'] ?? '');
        $password = trim($_POST['super_user_password'] ?? '');
        $confirm_password = trim($_POST['super_user_confirm_password'] ?? '');

        if (validateEmptyData($permissions) || validateEmptyData($password) ||
        validateEmptyData($confirm_password) || validateEmptyData($name)) {
            redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateString($name)) {
            redirectionForError("El nombre ingresado no es válido");
        }

        if (!validateShortString($permissions)) {
            redirectionForError("Los permisos seleccionados no son válidos");
        }

        if (!validatePassword($password)) {
            redirectionForError("La contraseña ingresada no es válida");
        }

        if (!validatePassword($confirm_password)) {
            redirectionForError("La confirmación de la contraseña no es válida");
        }

        if ($password !== $confirm_password) {
            redirectionForError("Las contraseñas no coinciden");
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $mysqli = connection_db();
        $super_user = findSuperUserWithName($mysqli, $name);

        if($super_user) {
            redirectWithError($mysqli, "Ya existe este super usuario");
        }

        try {

            $mysqli->begin_transaction();

            $id = insertSuperUser($mysqli, $name, $permissions, $password_hash);
            
            $code = "SU" . str_pad($id, 8, '0', STR_PAD_LEFT);
            insertSuperUserCode($mysqli, $code, (int) $id);

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Administrador registrado correctamente, el código correspondiente es: " .  $code;
            header("Location: /php/pages/super_user/super_user_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar el administrador';
            header("Location: /php/pages/super_user/super_user_register.php");
            exit();
        }

    }
    
?>