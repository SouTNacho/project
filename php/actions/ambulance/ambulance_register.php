<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/ambulance_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $registration = trim($_POST['ambulance_registration'] ?? '');
        $brand = trim($_POST['ambulance_brand'] ?? '');
        $model = trim($_POST['ambulance_model'] ?? '');
        $year = trim($_POST['ambulance_year'] ?? '');
        $description = trim($_POST['ambulance_description'] ?? '');

        if (validateEmptyData($registration) || validateEmptyData($brand) || validateEmptyData($model) ||
        validateEmptyData($year) || validateEmptyData($description)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateAmbulanceRegistration($registration)) {
            redirectionForError("La matricula ingresada no es válida.");
        }

        if (!validateString($brand)) {
            redirectionForError("La marca ingresada no es válida.");
        }

        if (!validateString($model)) {
            redirectionForError("El modelo ingresado no es válido.");
        }

        if (!validateYear($year) || (int) $year < 1900 || (int) $year > 2100) {
            redirectionForError("El año ingresado no es válido.");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida.");
        }

        $mysqli = connection_db();
        $ambulance = findAmbulanceWithRegistration($mysqli, $registration);

        if($ambulance) {
            redirectWithError($mysqli, "Esta matricula ya esta registrada.");
        }

        try {

            insertAmbulance($mysqli, $registration, $brand, $model, $year, $description);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/ambulance/ambulance_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/ambulance/ambulance_form.php");
            exit();
        }

    }

?> 