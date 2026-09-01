<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/ambulance_functions.php";
    require_once __DIR__ . "/../../models/ambulance_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_update.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_update.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $new_registration = trim($_POST['ambulance_new_registration'] ?? '');
        $current_registration = trim($_POST['ambulance_current_registration'] ?? '');
        $brand = trim($_POST['ambulance_brand'] ?? '');
        $model = trim($_POST['ambulance_model'] ?? '');
        $year = trim($_POST['ambulance_year'] ?? '');
        $description = trim($_POST['ambulance_description'] ?? '');

        if (!validateAmbulanceRegistration($current_registration)) {
            redirectionForError("El código actual no es válido");
        }

        if (!validateEmptyData($new_registration)) {
            if (!validateAmbulanceRegistration($new_registration)) {
                redirectionForError("El código nuevo no es válido");
            }
        }

        if (!validateEmptyData($brand)) {
            if (!validateString($brand)) {
                redirectionForError("La marca ingresada no es válida");
            }
        }

        if (!validateEmptyData($model)) {
            if (!validateString($model)) {
                redirectionForError("El modelo ingresado no es válido");
            }
        }

        if (!validateEmptyData($year)) {
            if (!validateYear($year) || $year < 1900 || $year > 2100) {
                redirectionForError("El año ingresado no es válido");
            }
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripcion ingresada no es válida");
            }
        }

        $mysqli = connection_db();
        $ambulance = findAmbulanceWithRegistration($mysqli, $current_registration);

        if(!$ambulance) {
            redirectWithError($mysqli, "El ambulancia no existe");
        }

        $ambulance_registration = findAmbulanceWithRegistration($mysqli, $new_registration);

        if($ambulance_registration && $ambulance['id_ambulancia'] !== $ambulance_registration['id_ambulancia']) {
            redirectWithError($mysqli, "Ya existe una ambulancia con esa matricula");
        }

        try {

            $new_registration = keepOldValue($new_registration, $ambulance["matricula"]);
            $brand = keepOldValue($brand, $ambulance["marca"]);
            $model = keepOldValue($model, $ambulance["modelo"]);
            $year = keepOldValue($year, $ambulance["anio"]);
            $description = keepOldValue($description, $ambulance["descripcion"]);

            updateAmbulance($mysqli, $new_registration, $brand, $model, (int) $year, $description, (int) $ambulance['id_ambulancia']);
            $mysqli->close();

            $_SESSION["success"] = "Ambulancia actualizada correctamente";
            header("Location: /php/pages/ambulance/ambulance_update.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();

            error_log( $e->getMessage());
            $_SESSION["errors"] = "Ocurrió un error al actualizar el ambulancia";
            header("Location: /php/pages/ambulance/ambulance_update.php");
            exit();
        }

    }

?>