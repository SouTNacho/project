<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/driver_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";


    function redirectionForError($message, $code) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/driver/driver_form.php?code=" . $code);
        exit();
    }


    function redirectWithError($mysqli, $message, $code) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/driver/driver_form.php?code=" . $code);
        exit();
    }


    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_GET['code'] ?? '');
        $document = trim($_POST['driver_document'] ?? '');
        $expiration = trim($_POST['driver_license_expiration'] ?? '');
        $category = trim(
            $_POST['driver_license_category'] ?? '');
        $password = trim($_POST['driver_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (validateEmptyData($code)) {
            redirectionForError("El código no es válido.", $code);
        }

        $mysqli = connection_db();
        $driver = findDriverWithCode($mysqli, $code);

        if (!$driver) {
            redirectWithError( $mysqli, "El conductor no está registrado.", $code);
        }

        $employee_id = (int) $driver['id_funcionario'];

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectWithError($mysqli, "La cédula ingresada no es válida.", $code);
            }

            $employee = findEmployeeWithDocument($mysqli, $document);

            if (!$employee) {
                redirectWithError($mysqli, "El empleado no existe.", $code);
            }

            $employee_id = (int) $employee['id_funcionario'];
            $otherDriver = findDriverWithEmployeeId($mysqli, $employee_id);

            if ($otherDriver && (int) $otherDriver['id_conductor'] !== (int) $driver['id_conductor']) {
                redirectWithError($mysqli, "El empleado ya está registrado como conductor.", $code);
            }
        }

        $expiration = keepOldValue($expiration, $driver['vencimiento_carnet']);
        $category = keepOldValue($category, $driver['categoria_carnet']);

        if (!validateDate($expiration)) {
            redirectWithError($mysqli, "La fecha de expiración no es válida.", $code);
        }

        if (strlen($category) !== 1) {
            redirectWithError($mysqli, "La categoría no es válida.", $code);
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

            updateDriver($mysqli, (int) $driver['id_conductor'], $expiration, $category, $employee_id);

            if ($changePassword) {

                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                changePasswordDriver($mysqli, (int) $driver['id_conductor'], $password_hash);
            }

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/driver/driver_form.php?code=" . $code);
            exit();

        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar conductor: " . $e->getMessage());
            $_SESSION["errors"] ="Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/driver/driver_form.php?code=" . $code);
            exit();
        }
    }

?>