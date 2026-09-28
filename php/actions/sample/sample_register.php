<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/sample_functions.php";
    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_POST['sample_code'] ?? '');
        $document = trim($_POST['sample_document_patient'] ?? '');
        $type = trim($_POST['sample_type'] ?? '');
        $description = trim($_POST['sample_description'] ?? '');

        if (validateEmptyData($document) || validateEmptyData($type) ||
        validateEmptyData($description) || validateEmptyData($code)) {
            redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateSampleCode($code)) {
            redirectionForError("La código ingresado no es válido");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cedula ingresada no es válida");
        }

        if (!validateString($type)) {
            redirectionForError("El tipo ingresado no es válido");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida");
        }

        $mysqli = connection_db();
        $sample = findSampleWithCode($mysqli, $code);

        if($sample) {
            redirectWithError($mysqli, "Ya existe una muestra asociada a este código");
        }

        $patient = findPatientWithDocument($mysqli, $document);

        if(!$patient) {
            redirectWithError($mysqli, "El paciente ingresado no existe");
        }

        try {

            insertSample($mysqli, $code, $type, $description, $patient['id_paciente']);
            $mysqli->close();

            $_SESSION["success"] = "Muestra registrada correctamente";
            header("Location: /php/pages/sample/sample_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar la muestra';
            header("Location: /php/pages/sample/sample_register.php");
            exit();
        }

    }

?> 