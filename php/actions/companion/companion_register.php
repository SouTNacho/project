<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/companion_functions.php";
    require_once __DIR__ . "/../../models/companion_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $document = trim($_POST['companion_document'] ?? '');
        $first_name = trim($_POST['companion_first_name'] ?? '');
        $last_name = trim($_POST['companion_last_name'] ?? '');

        if (validateEmptyData($document) || validateEmptyData($first_name) || validateEmptyData($last_name)) {
            redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cedula ingresada no es válida");
        }

        if (!validateString($first_name)) {
            redirectionForError("El nombre ingresado no es válido");
        }

        if (!validateString($last_name)) {
            redirectionForError("El apellido ingresado no es válido");
        }

        $mysqli = connection_db();
        $companion = findCompanionWithDocument($mysqli, $document);

        if($companion) {
            redirectWithError($mysqli, "Ya existe una acompañante asociado a esta cedula");
        }

        try {

            insertCompanion($mysqli, $document, $first_name, $last_name);
            $mysqli->close();

            $_SESSION["success"] = "Acompañante registrado correctamente";
            header("Location: /php/pages/companion/companion_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar el acompañante';
            header("Location: /php/pages/companion/companion_register.php");
            exit();
        }

    }

?> 