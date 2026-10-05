<?php

    session_start();
    // Falta validar rol

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
        $address = trim($_POST['ubication_address'] ?? '');
        $latitude = trim($_POST['ubication_latitude'] ?? '');
        $longitude = trim($_POST['ubication_longitude'] ?? '');
        
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

        if ((int) $ubication['id_estado_ubicacion'] === 3) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
        }

        if (!validateEmptyData($name)) {
            $ubication_name = findUbicationWithName($mysqli, $name);

            if($ubication_name && (int) $ubication['id_ubicacion'] !== (int) $ubication_name['id_ubicacion']) {
                redirectWithError($mysqli, "Esta ubicación ya existe.", $id);
            }
        }

        try {

            $name = keepOldValue($name, $ubication["nombre"]);
            $address = keepOldValue($address, $ubication["direccion"]);
            $latitude = keepOldValue($latitude, $ubication["latitud"]);
            $longitude = keepOldValue($longitude, $ubication["longitud"]);

            updateUbication($mysqli, $name, $address, $latitude, $longitude, (int) $ubication['id_ubicacion']);

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