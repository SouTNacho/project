<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/copilot_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";


    function redirectionForError($message, $code) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/copilot/copilot_form.php?code=" . $code);
        exit();
    }


    function redirectWithError($mysqli, $message, $code) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/copilot/copilot_form.php?code=" . $code);
        exit();
    }


    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_GET['code'] ?? '');
        $document = trim($_POST['copilot_document'] ?? '');
        $speciality = trim($_POST['copilot_speciality'] ?? '');
        $password = trim($_POST['copilot_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (validateEmptyData($code)) {
            redirectionForError("El código no es válido.", $code);
        }

        $mysqli = connection_db();
        $copilot = findCopilotWithCode($mysqli, $code);

        if (!$copilot) {
            redirectWithError( $mysqli, "El copiloto no está registrado.", $code);
        }

        $employee_id = (int) $copilot['id_funcionario'];

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectWithError($mysqli, "La cédula ingresada no es válida.", $code);
            }

            $employee = findEmployeeWithDocument($mysqli, $document);

            if (!$employee) {
                redirectWithError($mysqli, "El empleado no existe.", $code);
            }

            $employee_id = (int) $employee['id_funcionario'];
            $otherCopilot = findCopilotWithEmployeeId($mysqli, $employee_id);

            if ($otherCopilot && (int) $otherCopilot['id_copiloto'] !== (int) $copilot['id_copiloto']) {
                redirectWithError($mysqli, "El empleado ya está registrado como copiloto.", $code);
            }
        }

        $speciality = keepOldValue($speciality, $copilot['especialidad']);

        if (!validateString($speciality)) {
            redirectWithError($mysqli, "La especialidad no es válida.", $code);
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

            updateCopilot($mysqli, (int) $copilot['id_copiloto'], $speciality, $employee_id);

            if ($changePassword) {

                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                changePasswordCopilot($mysqli, (int) $copilot['id_copiloto'], $password_hash);
            }

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/copilot/copilot_form.php?code=" . $code);
            exit();

        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar copiloto: " . $e->getMessage());
            $_SESSION["errors"] ="Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/copilot/copilot_form.php?code=" . $code);
            exit();
        }
    }

?>