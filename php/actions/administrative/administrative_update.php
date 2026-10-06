<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/administrative_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";


    function redirectionForError($message, $code) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/administrative/administrative_form.php?code=" . $code);
        exit();
    }


    function redirectWithError($mysqli, $message, $code) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/administrative/administrative_form.php?code=" . $code);
        exit();
    }


    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_GET['code'] ?? '');
        $document = trim($_POST['administrative_document'] ?? '');
        $permissions = trim($_POST['administrative_permissions'] ?? '');
        $password = trim($_POST['administrative_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (validateEmptyData($code)) {
            redirectionForError("El código no es válido.", $code);
        }

        $mysqli = connection_db();
        $administrative = findAdministrativeWithCode($mysqli, $code);

        if (!$administrative) {
            redirectWithError( $mysqli, "El administrativo no está registrado.", $code);
        }

        $employee_id = (int) $administrative['id_funcionario'];

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectWithError($mysqli, "La cédula ingresada no es válida.", $code);
            }

            $employee = findEmployeeWithDocument($mysqli, $document);

            if (!$employee) {
                redirectWithError($mysqli, "El empleado no existe.", $code);
            }

            $employee_id = (int) $employee['id_funcionario'];
            $otherAdministrative = findAdministrativeWithEmployeeId($mysqli, $employee_id);

            if ($otherAdministrative && (int) $otherAdministrative['id_administrativo'] !== (int) $administrative['id_administrativo']) {
                redirectWithError($mysqli, "El empleado ya está registrado como administrativo.", $code);
            }
        }

        $permissions = keepOldValue($permissions, $administrative['permisos']);

        if (!validateShortString($permissions)) {
            redirectWithError($mysqli, "Los permisos no son válidos.", $code);
        }

        $changePassword = $password !== '' || $confirm_password !== '';

        if ($changePassword) {

            if (validateEmptyData($password) || validateEmptyData($confirm_password)) {
                redirectWithError($mysqli, "Debes completar ambos campos de contraseña.", $code);
            }

            if (!validatePassword($password)) {
                redirectWithError($mysqli, "La contraseña ingresada no es válida.", $code);
            }

            if ($password !== $confirm_password) {
                redirectWithError($mysqli, "Las contraseñas no coinciden.", $code);
            }
        }

        try {

            $mysqli->begin_transaction();

            updateAdministrative($mysqli, (int) $administrative['id_administrativo'], $permissions, $employee_id);

            if ($changePassword) {

                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                changePasswordAdministrative($mysqli, (int) $administrative['id_administrativo'], $password_hash);
            }

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/administrative/administrative_form.php?code=" . $code);
            exit();

        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar administrativo: " . $e->getMessage());
            $_SESSION["errors"] ="Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/administrative/administrative_form.php?code=" . $code);
            exit();
        }
    }

?>