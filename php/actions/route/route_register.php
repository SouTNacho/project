<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../models/route_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/ubication_model.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/route/route_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/route/route_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST['route_name'] ?? '');
        $origin = trim($_POST['route_origin'] ?? '');
        $intermedium = trim($_POST['route_intermedium'] ?? '');
        $destination = trim($_POST['route_destination'] ?? '');

        if (validateEmptyData($name)) {
            redirectionForError("El nombre de la ruta es obligatorio.");
        }

        if (filter_var($origin, FILTER_VALIDATE_INT) === false || (int) $origin <= 0) {
            redirectionForError("El origen ingresado no es válido.");
        }

        if (filter_var($destination, FILTER_VALIDATE_INT) === false || (int) $destination <= 0) {
            redirectionForError("El destino ingresado no es válido.");
        }

        $origin = (int) $origin;
        $destination = (int) $destination;

        if (!validateLargeString($name)) {
            redirectionForError("El nombre ingresado no es válido.");
        }

        if ($intermedium !== '') {

            if (filter_var($intermedium, FILTER_VALIDATE_INT) === false || (int) $intermedium <= 0) {
                redirectionForError("El punto intermedio ingresado no es válido.");
            }

            $intermedium = (int) $intermedium;
        } else {

            $intermedium = null;
        }

        if ($origin === $destination && $intermedium === null) {
            redirectionForError("El origen y el destino no pueden ser iguales si no hay un punto intermedio.");
        }

        if ($intermedium !== null && ($intermedium === $origin || $intermedium === $destination)) {
            redirectionForError("El punto intermedio debe ser diferente del origen y del destino.");
        }

        $mysqli = connection_db();

        try {

            $route_name = findRouteWithName($mysqli, $name);
            if ($route_name) {

                redirectWithError($mysqli, "Ya existe una ruta con ese nombre.");
            }

            $ubication = findUbicationWithId($mysqli, $origin);
            if (!$ubication) {

                redirectWithError($mysqli, 'La ubicacion de origen no existe.');
            }

            $ubication = findUbicationWithId($mysqli, $destination);
            if (!$ubication) {

                redirectWithError($mysqli, 'La ubicacion de destino no existe.');
            }

            if ($intermedium !== null) {

                $ubication = findUbicationWithId($mysqli, $intermedium);
                if (!$ubication) {

                    redirectWithError($mysqli, 'La ubicacion intermedia no existe.');
                }
            }

            $route = findFullRoute($mysqli, $origin, $intermedium, $destination);
            if ($route) {

                redirectWithError($mysqli, 'La ruta ingresada ya existe.');
            }
            

            insertRoute($mysqli, $name, $origin, $intermedium, $destination);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/route/route_form.php");
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUÉS QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());

            $mysqli->close();

            $_SESSION["errors"] = "Ha ocurrido un error al registrar.";
            header("Location: /php/pages/route/route_form.php");
            exit();
        }
    }
?>