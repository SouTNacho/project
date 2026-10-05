<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/ubication_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ubication/ubication_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ubication/ubication_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST['ubication_name'] ?? '');
        $address = trim($_POST['ubication_address'] ?? '');
        $latitude = trim($_POST['ubication_latitude'] ?? '');
        $longitude = trim($_POST['ubication_longitude'] ?? '');

        if ( validateEmptyData($name) || validateEmptyData($address) ||
            validateEmptyData($latitude) || validateEmptyData($longitude)) {
            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateString($name)) {
            redirectionForError("El nombre ingresado no es válido.");
        }

        if (!validateLargeString($address)) {
            redirectionForError("La dirección ingresada no es válida.");
        }

        if (!is_numeric($latitude) || (float) $latitude < -90 || (float) $latitude > 90) {
            redirectionForError("La latitud ingresada no es válida.");
        }

        if (!is_numeric($longitude) || (float) $longitude < -180 || (float) $longitude > 180) {
            redirectionForError("La longitud ingresada no es válida.");
        }

        $mysqli = connection_db();

        $ubication = findUbicationWithName($mysqli, $name);

        if ($ubication) {
            redirectWithError($mysqli, "Ya existe está ubicación, ingrese otro nombre.");
        }

        try {

            insertUbication( $mysqli, $name, $address, $latitude, $longitude);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/ubication/ubication_form.php");
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUÉS QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ha ocurrido un error al registrar.";
            header("Location: /php/pages/ubication/ubication_form.php");
            exit();
        }
    }

?>