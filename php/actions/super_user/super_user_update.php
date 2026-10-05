<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/super_user_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/super_user/super_user_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/super_user/super_user_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $name = trim($_POST['super_user_name'] ?? '');
        $permissions = trim($_POST['super_user_permissions'] ?? '');
        $password = trim($_POST['super_user_password'] ?? '');
        $confirm_password = trim($_POST['super_user_confirm_password'] ?? '');
        $array = ['Low', 'Mid', 'High'];

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/super_user/super_user_form.php");
            exit();
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($permissions)) {
            if (!validateShortString($permissions) || !in_array($permissions, $array)) {
                redirectionForError("Los permisos ingresados no son válidos.", $id);
            }
        }

        if (!validateEmptyData($password)) {
            if (!validatePassword($password)) {
                redirectionForError("La contraseña ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($confirm_password)) {
            if (!validatePassword($confirm_password)) {
                redirectionForError("La confirmación de la contraseña no es válida.", $id);
            }
        }

        $password_hash = null;
        if (!validateEmptyData($password) || !validateEmptyData($confirm_password)) {

            if ($password !== $confirm_password) {
                redirectionForError("Las contraseñas no coinciden.", $id);
            } else {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
            }
        }

        $mysqli = connection_db();
        $super_user = findSuperUserWithId($mysqli, $id);

        if(!$super_user) {
            redirectWithError($mysqli, "El Super Usuario ingresado no existe.", $id);
        }

        if ((int) $super_user['id_estado_super_usuario'] === 3) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
        }

        if (!validateEmptyData($name)) {

            $super_user_name = findSuperUserWithName($mysqli, $name);

            if ($super_user_name && (int) $super_user['id_super_usuario'] !== (int) $super_user_name['id_super_usuario']) {
                redirectWithError($mysqli, "Este nombre ya está registrado.", $id);
            }
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

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/super_user/super_user_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/super_user/super_user_form.php?id=" . $id);
            exit();
        }

    }
    
?>