<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../models/route_model.php";
    require_once __DIR__ . "/../../models/ubication_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/route/route_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/route/route_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return $newValue === '' ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $name = trim($_POST['route_name'] ?? '');
        $origin = trim($_POST['route_origin'] ?? '');
        $intermedium = trim($_POST['route_intermedium'] ?? '');
        $destination = trim($_POST['route_destination'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = "El ID es incorrecto.";
            header("Location: /php/pages/route/route_form.php");
            exit();
        }

        if (!validateEmptyData($name)) {
            if (!validateLargeString($name)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        if ($origin !== '' && (filter_var($origin, FILTER_VALIDATE_INT) === false || (int) $origin <= 0)) {
            redirectionForError("El origen ingresado no es válido.", $id);
        }

        if ($destination !== '' && (filter_var($destination, FILTER_VALIDATE_INT) === false || (int) $destination <= 0)) {
            redirectionForError("El destino ingresado no es válido.", $id);
        }

        if ($intermedium !== '' && (filter_var($intermedium, FILTER_VALIDATE_INT) === false || (int) $intermedium <= 0)) {
            redirectionForError("El punto intermedio ingresado no es válido.", $id);
        }

        $mysqli = connection_db();
        $route = findRouteWithId($mysqli, $id);

        if (!$route) {
            redirectWithError($mysqli, "No existe este registro.", $id);
        }

        if ((int) $route['id_estado_ruta'] === 3) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
        }

        try {

            $name = keepOldValue($name, $route['nombre']);
            $intermedium = $intermedium === '' ? null : (int) $intermedium;
            $origin = $origin === '' ? (int) $route['id_ubicacion_origen'] : (int) $origin;
            $destination = $destination === '' ? (int) $route['id_ubicacion_destino'] : (int) $destination;

            
            if ($origin === $destination && $intermedium === null) {
                redirectWithError($mysqli,
                    "El origen y el destino no pueden ser iguales si no hay un punto intermedio.", $id);
            }

            if ($intermedium !== null && ($intermedium === $origin || $intermedium === $destination)) {
                redirectWithError($mysqli,
                    "El punto intermedio debe ser diferente del origen y del destino.", $id);
            }

            $ubication = findUbicationWithId($mysqli, $origin);

            if (!$ubication) {
                redirectWithError($mysqli, "La ubicación de origen no existe.", $id);
            }

            $ubication = findUbicationWithId($mysqli, $destination);

            if (!$ubication) {
                redirectWithError($mysqli, "La ubicación de destino no existe.", $id);
            }

            if ($intermedium !== null) {

                $ubication = findUbicationWithId($mysqli, $intermedium);

                if (!$ubication) {
                    redirectWithError($mysqli, "La ubicación intermedia no existe.", $id);
                }
            }

            $routeName = findRouteWithName($mysqli, $name);

            if ($routeName && (int) $routeName['id_ruta'] !== (int) $id) {
                redirectWithError($mysqli, "Ya existe una ruta con ese nombre.", $id);
            }

            $matchRoute = findFullRoute($mysqli, $origin, $intermedium, $destination);

            if ($matchRoute &&(int) $matchRoute['id_ruta'] !== (int) $id) {
                redirectWithError( $mysqli, "La ruta ingresada ya existe.", $id);
            }

            updateRoute($mysqli, $name, $origin, $intermedium, $destination, (int) $id);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/route/route_form.php?id=" . $id);
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUÉS QUITAR EL MENSAJE
            error_log("Error al actualizar: " . $e->getMessage());

            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/route/route_form.php?id=" . $id);
            exit();
        }
    }
?>