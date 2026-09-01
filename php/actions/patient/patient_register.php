<?php

    session_start();
    
    require_once __DIR__ . "/../functions/patient_functions.php";
    require_once __DIR__ . "/../models/patient_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/patient_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/patient_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $first_name = trim($_POST['patient_first_name'] ?? '');
        $last_name = trim($_POST['patient_last_name'] ?? '');
        $document = trim($_POST['patient_document'] ?? '');
        $birthdate = trim($_POST['patient_birthdate'] ?? '');
        $address = trim($_POST['patient_address'] ?? '');
        $email = trim($_POST['patient_email'] ?? '');
        $cellphone_code = trim($_POST['patient_cellphone_code'] ?? '');
        $cellphone_number = trim($_POST['patient_cellphone_number'] ?? '');
        // Si lo operamos con Estado al paciente tenemos que hacerlo int y setiarlo en 1 por defoult

        if (validateEmptyData($first_name) || validateEmptyData($last_name) || validateEmptyData($document) ||
        validateEmptyData($birthdate) || validateEmptyData($address) || validateEmptyData($email) ||
        validateEmptyData($cellphone_code) || validateEmptyData($cellphone_number)) {

            redirectionForError("Todos los campos son obligatorios");
        }

        $phone_number = $cellphone_code . $cellphone_number;

        if (!validateDocument($document)) {
            redirectionForError("La cedula ingresada no es válida");
        }

        if (!validatePhone($phone_number)) {
            redirectionForError("El celular ingresado no es válido");
        }

        if (!validateName($first_name)) {
            redirectionForError("El nombre ingresado no es válido");
        }

        if (!validateName($last_name)) {
            redirectionForError("El apellido ingresado no es válido");
        }

        if (!validateString($address)) {
            redirectionForError("La dirección ingresada no es válida");
        }

        if (!validateDate($birthdate)) {
            redirectionForError("La fecha de nacimiento ingresada no es válido");
        }

        if (!validateEmail($email)) {
            redirectionForError("El email ingresado no es válido");
        }

        $mysqli = connection_db();
        $patient = findPatientWithDocument($mysqli, $document);

        if($patient) {
            redirectWithError($mysqli, "El usuario ya existe");
        }

        $patient_email = findPatientWithEmail($mysqli, $email);

        if ($patient_email) {
            redirectWithError($mysqli, "Este email ya está registrado");
        }

        $patient_phone_number = findPatientWithPhoneNumber($mysqli, $phone_number);

        if ($patient_phone_number) {
            redirectWithError($mysqli, "Este celular ya está registrado");
        }

        try {

            insertPatient($mysqli, $first_name, $last_name, $document,
                $phone_number, $email, $birthdate, $address);

            $mysqli->close();

            $_SESSION["success"] = "Paciente registrado correctamente";
            header("Location: /php/patient_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            $mysqli->close();
            
            error_log( $e->getMessage());
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar el paciente';
            header("Location: /php/patient_register.php");
            exit();
        }

    }
    
?>