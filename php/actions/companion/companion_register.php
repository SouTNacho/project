<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/companion_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $document = trim($_POST['companion_document'] ?? '');
        $first_name = trim($_POST['companion_first_name'] ?? '');
        $last_name = trim($_POST['companion_last_name'] ?? '');

        if (validateEmptyData($document) || validateEmptyData($first_name) || validateEmptyData($last_name)) {
            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cédula ingresada no es válida.");
        }

        if (!validateString($first_name)) {
            redirectionForError("El nombre ingresado no es válido.");
        }

        if (!validateString($last_name)) {
            redirectionForError("El apellido ingresado no es válido.");
        }

        $mysqli = connection_db();
        $companion = findCompanionWithDocument($mysqli, $document);

        if($companion) {
            redirectWithError($mysqli, "Esta cedula ye esta registrada.");
        }

        try {

            insertCompanion($mysqli, $document, $first_name, $last_name);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/companion/companion_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/companion/companion_form.php");
            exit();
        }

    }

?> 