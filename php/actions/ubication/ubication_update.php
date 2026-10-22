<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../models/ubication_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ubication/ubication_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ubication/ubication_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $name = trim($_POST['ubication_name'] ?? '');
        $department = trim($_POST['ubication_department'] ?? '');
        $address = trim($_POST['ubication_address'] ?? '');
        $latitude = trim($_POST['ubication_lat'] ?? '');
        $longitude = trim($_POST['ubication_long'] ?? '');
        
        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/ubication/ubication_form.php");
            exit();
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($department)) {
            if (!validateString($department)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($address)) {
            if (!validateString($address)) {
                redirectionForError("La dirección ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($latitude)) {
            if (!is_numeric($latitude) || (float) $latitude < -90 || (float) $latitude > 90) {
                redirectionForError("La latitud ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($longitude)) {
            if (!is_numeric($longitude) || (float) $longitude < -180 || (float) $longitude > 180) {
                redirectionForError("La longitud ingresada no es válida.", $id);
            }
        }

        $mysqli = connection_db();
        $ubication = findUbicationWithId($mysqli, $id);

        if(!$ubication) {
            redirectWithError($mysqli, "No existe este registro.", $id);
        }

        try {

            $name = keepOldValue($name, $ubication["nombre"]);
            $department = keepOldValue($department, $ubication["departamento"]);
            $address = keepOldValue($address, $ubication["direccion"]);
            $latitude = keepOldValue($latitude, $ubication["latitud"]);
            $longitude = keepOldValue($longitude, $ubication["longitud"]);

            $ubication_name = findUbicationWithName($mysqli, $name);

            if($ubication_name && (int) $ubication['id_ubicacion'] !== (int) $ubication_name['id_ubicacion']) {
                redirectWithError($mysqli, "Esta ubicación ya existe.", $id);
            }

            $ubication_coordinates = findUbicationWithCoordinatesAndId(
                $mysqli, $latitude, $longitude, $id);

            if ($ubication_coordinates) {
                redirectWithError($mysqli, "Ya existe una ubicación con esas coordenadas.", $id);
            }

            updateUbication($mysqli, $name, $department, $address, $latitude, $longitude, (int) $ubication['id_ubicacion']);

            $mysqli->close();
            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/ubication/ubication_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/ubication/ubication_form.php?id=" . $id);
            exit();
        }
    }
?>