<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/ambulance_functions.php";
    require_once __DIR__ . "/../../models/ambulance_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_register.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $registration = trim($_POST['ambulance_registration'] ?? '');
        $brand = trim($_POST['ambulance_brand'] ?? '');
        $model = trim($_POST['ambulance_model'] ?? '');
        $year = trim($_POST['ambulance_year'] ?? '');
        $description = trim($_POST['ambulance_description'] ?? '');
        // Si lo operamos con Estado al paciente tenemos que hacerlo int y setiarlo en 1 por defoult

        if (validateEmptyData($registration) || validateEmptyData($brand) || validateEmptyData($model) ||
        validateEmptyData($year) || validateEmptyData($description)) {

            redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateAmbulanceRegistration($registration)) {
            redirectionForError("La matricula ingresada no es válida");
        }

        if (!validateString($brand)) {
            redirectionForError("La marca ingresada no es válida");
        }

        if (!validateString($model)) {
            redirectionForError("El modelo ingresado no es válido");
        }

        if (!validateYear($year) || $year < 1900 || $year > 2100) {
            redirectionForError("El año ingresado no es válido");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida");
        }

        $mysqli = connection_db();
        $ambulance = findAmbulanceWithRegistration($mysqli, $registration);

        if($ambulance) {
            redirectWithError($mysqli, "Ya existe una ambulancia asociada a este código");
        }

        try {

            insertAmbulance($mysqli, $registration, $brand, $model, $year, $description);
            $mysqli->close();

            $_SESSION["success"] = "ambulancia registrada correctamente";
            header("Location: /php/pages/ambulance/ambulance_register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();
            
            error_log( $e->getMessage());
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar el ambulanceo';
            header("Location: /php/pages/ambulance/ambulance_register.php");
            exit();
        }

    }

?> 