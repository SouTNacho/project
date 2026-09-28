<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/patient_functions.php";
    require_once __DIR__ . "/../../models/patient_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/patient/patient_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/patient/patient_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $first_name = trim($_POST['patient_first_name'] ?? '');
        $last_name = trim($_POST['patient_last_name'] ?? '');
        $document = trim($_POST['patient_document'] ?? '');
        $birthdate = trim($_POST['patient_birthdate'] ?? '');
        $address = trim($_POST['patient_address'] ?? '');
        $email = trim($_POST['patient_email'] ?? '');
        $cellphone_code = trim($_POST['patient_cellphone_code'] ?? '');
        $cellphone_number = trim($_POST['patient_cellphone_number'] ?? '');
        $phone_number = '';

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/patient/patient_form.php");
            exit();
        }

        if (!validateEmptyData($first_name)) {
            if (!validateName($first_name)) {
                redirectionForError("El nombre ingresado no es válido", $id);
            }
        }

        if (!validateEmptyData($last_name)) {
            if (!validateName($last_name)) {
                redirectionForError("El apellido ingresado no es válido", $id);
            }
        }

        if (!validateEmptyData($address)) {
            if (!validateString($address)) {
                redirectionForError("La dirección ingresada no es válida", $id);
            }
        }

        if (!validateEmptyData($birthdate)) {
            if (!validateDate($birthdate)) {
                redirectionForError("La fecha de nacimiento ingresada no es válida", $id);
            }
        }

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectionForError("La cedula ingresada no es válida", $id);
            }
        }

        if (!validateEmptyData($email)) {
            if (!validateEmail($email)) {
                redirectionForError("El email ingresado no es válido", $id);
            }
        }

        if (!validateEmptyData($cellphone_code) || !validateEmptyData($cellphone_number)) {
            $phone_number = $cellphone_code . $cellphone_number;

            if (!validatePhone($phone_number)) {
                redirectionForError("El celular ingresado no es válido", $id);
            }
        }

        $mysqli = connection_db();
        $patient = findPatientWithId($mysqli, $id);

        if(!$patient) {
            redirectWithError($mysqli, "El paciente no existe", $id);
        }

        if (!validateEmptyData($document)) {

            $patient_document = findPatientWithDocument($mysqli, $document);

            if ($patient_document && (int) $patient['id_paciente'] !== (int) $patient_document['id_paciente']) {
                redirectWithError($mysqli, "Esta cedula ya está registrada", $id);
            }
        }

        if (!validateEmptyData($email)) {

            $patient_email = findPatientWithEmail($mysqli, $email);

            if ($patient_email && (int) $patient['id_paciente'] !== (int) $patient_email['id_paciente']) {
                redirectWithError($mysqli, "Este email ya está registrado", $id);
            }
        }

        if ($phone_number !== '') {
            $patient_phone_number = findPatientWithPhoneNumber($mysqli, $phone_number);

            if ($patient_phone_number && (int) $patient['id_paciente'] !== (int) $patient_phone_number['id_paciente']) {
                redirectWithError($mysqli, "Este celular ya está registrado", $id);
            }
        }

        try {

            $first_name = keepOldValue($first_name, $patient["nombre"]);
            $last_name = keepOldValue($last_name, $patient["apellido"]);
            $birthdate = keepOldValue($birthdate, $patient["fecha_nacimiento"]);
            $address = keepOldValue($address, $patient["direccion"]);
            $email = keepOldValue($email, $patient["email"]);
            $phone_number = keepOldValue($phone_number, $patient["telefono"]);
            $document = keepOldValue($document, $patient["cedula"]);

            updatePatient($mysqli, $first_name, $last_name, $birthdate,
                $address, $email, $phone_number, $document, (int) $patient['id_paciente']);

            $mysqli->close();
            $_SESSION["success"] = "Actualización exitosa";
            header("Location: /php/pages/patient/patient_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {
            
            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/patient/patient_form.php?id=" . $id);
            exit();
        }

    }
    
?>