<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/super_user_functions.php";
    require_once __DIR__ . "/../../models/super_user_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/super_user/super_user_update.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/super_user/super_user_update.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST['super_user_name'] ?? '');
        $code = trim($_POST['super_user_code'] ?? '');
        $permissions = trim($_POST['super_user_permissions'] ?? '');
        $password = trim($_POST['super_user_password'] ?? '');
        $confirm_password = trim($_POST['super_user_confirm_password'] ?? '');

        if (validateEmptyData($code)) {
            redirectionForError("El código es obligatorio");
        }

        if (!validateEmployeeCode($code)) {
            redirectionForError("El código ingresado no es válido");
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre ingresado no es válido");
            }
        }

        if (!validateEmptyData($permissions)) {
            if (!validateShortString($permissions)) {
                redirectionForError("Los permisos ingresados no son válidos");
            }
        }

        if (!validateEmptyData($password)) {
            if (!validatePassword($password)) {
                redirectionForError("La contraseña ingresada no es válida");
            }
        }

        if (!validateEmptyData($confirm_password)) {
            if (!validatePassword($confirm_password)) {
                redirectionForError("La confirmación de la contraseña no es válida");
            }
        }

        if ($password !== $confirm_password) {
            redirectionForError("Las contraseña no coinciden");
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
        }

        $mysqli = connection_db();
        $super_user = findSuperUserWithCode($mysqli, $code);

        if(!$super_user) {
            redirectWithError($mysqli, "El Administrador ingresado no existe");
        }

        $super_user_name = findSuperUserWithName($mysqli, $name);

        if ($super_user_name && $super_user['id_super_usuario'] !== $super_user_name['id_super_usuario']) {
            redirectWithError($mysqli, "Este nombre ya está registrado para otro Administrador");
        }

        try {

            $name = keepOldValue($name, $super_user["nombre"]);
            $permissions = keepOldValue($permissions, $super_user["permisos"]);

            if (!$password_hash) {

                $password = keepOldValue($password, $super_user["pass"]);
            } else {

                $password = $password_hash;
            }

            updateSuperUser($mysqli, $name, $permissions, $password, (int) $super_user['id_super_usuario']);

            $mysqli->close();

            $_SESSION["success"] = "Administrador actualizado correctamente";
            header("Location: /php/pages/super_user/super_user_update.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar el administrador";
            header("Location: /php/pages/super_user/super_user_update.php");
            exit();
        }

    }
    
?>