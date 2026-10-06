<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $code = trim($_POST['sample_code'] ?? '');
        $document = trim($_POST['sample_document_patient'] ?? '');
        $type = trim($_POST['sample_type'] ?? '');
        $description = trim($_POST['sample_description'] ?? '');

        if (validateEmptyData($code) || validateEmptyData($document) ||
            validateEmptyData($type) || validateEmptyData($description)) {
            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateSampleCode($code)) {
            redirectionForError("El código ingresado no es válido.");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cédula ingresada no es válida.");
        }

        if (!validateString($type)) {
            redirectionForError("El tipo ingresado no es válido.");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida.");
        }

        $mysqli = connection_db();
        $sample = findSampleWithCode($mysqli, $code);

        if($sample) {
            redirectWithError($mysqli, "Este código ya esta registrado.");
        }

        $patient = findPatientWithDocument($mysqli, $document);

        if (!$patient) {
            redirectWithError($mysqli, "El paciente ingresado no existe.");
        }

        if ((int) $patient['id_estado_paciente'] === 3 || (int) $patient['id_estado_paciente'] === 4) {
            redirectWithError($mysqli, "No se puede registrar una muestra para un paciente inactivo o eliminado.");
        }

        try {

            insertSample($mysqli, $code, $type, $description, $patient['id_paciente']);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/sample/sample_form.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            
            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/sample/sample_form.php");
            exit();
        }

    }

?> 