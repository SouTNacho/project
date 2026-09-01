<?php

    session_start();
    
    require_once __DIR__ . "/../functions/patient_functions.php";
    require_once __DIR__ . "/../models/patient_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/update_patient.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/update_patient.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $first_name = trim($_POST['patient_first_name'] ?? '');
        $last_name = trim($_POST['patient_last_name'] ?? '');
        $current_document = trim($_POST['patient_current_document'] ?? '');
        $new_document = trim($_POST['patient_new_document'] ?? '');
        $birthdate = trim($_POST['patient_birthdate'] ?? '');
        $address = trim($_POST['patient_address'] ?? '');
        $email = trim($_POST['patient_email'] ?? '');
        $cellphone_code = trim($_POST['patient_cellphone_code'] ?? '');
        $cellphone_number = trim($_POST['patient_cellphone_number'] ?? '');
        $phone_number = '';

        if (!validateDocument($current_document)) {
                redirectionForError("La cedula actual no es válida");
            }

        if (!validateEmptyData($first_name)) {
            if (!validateName($first_name)) {
                redirectionForError("El nombre ingresado no es válido");
            }
        }

        if (!validateEmptyData($last_name)) {
            if (!validateName($last_name)) {
                redirectionForError("El apellido ingresado no es válido");
            }
        }

        if (!validateEmptyData($address)) {
            if (!validateString($address)) {
                redirectionForError("La dirección ingresada no es válida");
            }
        }

        if (!validateEmptyData($birthdate)) {
            if (!validateDate($birthdate)) {
                redirectionForError("La fecha de nacimiento ingresada no es válido");
            }
        }

        if (!validateEmptyData($new_document)) {
            if (!validateDocument($new_document)) {
                redirectionForError("La cedula nueva no es válida");
            }
        }

        if (!validateEmptyData($email)) {
            if (!validateEmail($email)) {
                redirectionForError("El email ingresado no es válido");
            }
        }

        if (!validateEmptyData($cellphone_code) || !validateEmptyData($cellphone_number)) {
            $phone_number = $cellphone_code . $cellphone_number;

            if (!validatePhone($phone_number)) {
                redirectionForError("El celular ingresado no es válido");
            }
        }

        

        $mysqli = connection_db();
        $patient = findPatientWithDocument($mysqli, $current_document);

        if(!$patient) {
            redirectWithError($mysqli, "El usuario no existe");
        }

        $patient_new_document = findPatientWithDocument($mysqli, $new_document);

        if ($patient_new_document && $patient['id_paciente'] !== $patient_new_document['id_paciente']) {
            redirectWithError($mysqli, "Esta cedula ya está registrada");
        }

        $patient_email = findPatientWithEmail($mysqli, $email);

        if ($patient_email && $patient['id_paciente'] !== $patient_email['id_paciente']) {
            redirectWithError($mysqli, "Este email ya está registrado");
        }

        if ($phone_number !== '') {
            $patient_phone_number = findPatientWithPhoneNumber($mysqli, $phone_number);

            if ($patient_phone_number && $patient['id_paciente'] !== $patient_phone_number['id_paciente']) {
                redirectWithError($mysqli, "Este celular ya está registrado");
            }
        }

        try {

            $first_name = keepOldValue($first_name, $patient["nombre"]);
            $last_name = keepOldValue($last_name, $patient["apellido"]);
            $birthdate = keepOldValue($birthdate, $patient["fecha_nacimiento"]);
            $address = keepOldValue($address, $patient["direccion"]);
            $email = keepOldValue($email, $patient["email"]);
            $phone_number = keepOldValue($phone_number, $patient["telefono"]);
            $new_document = keepOldValue($new_document, $patient["cedula"]);

            updatePatient($mysqli, $first_name, $last_name, $birthdate,
                $address, $email, $phone_number, $new_document, (int) $patient['id_paciente']);

            $mysqli->close();
            $_SESSION["success"] = "Paciente actualizado correctamente";
            header("Location: /php/update_patient.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();

            error_log( $e->getMessage());
            $_SESSION["errors"] = "Ocurrió un error al actualizar el paciente";
            header("Location: /php/update_patient.php");
            exit();
        }

    }
    
?>