<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/route_model.php";
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
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $origin = trim($_POST['route_origin'] ?? '');
        $destination = trim($_POST['route_destination'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/route/route_form.php");
            exit();
        }

        if (!validateEmptyData($origin)) {
            if (!validateString($origin)) {
                redirectionForError("El origen ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($destination)) {
            if (!validateString($destination)) {
                redirectionForError("El destino ingresado no es válido.", $id);
            }
        }

        $mysqli = connection_db();
        $route = findRouteWithId($mysqli, $id);

        if(!$route) {
            redirectWithError($mysqli, "No existe este registro.", $id);
        }

        if ((int) $route['id_estado_ruta'] === 3) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
        }

        try {

            $origin = keepOldValue($origin, $route["origen"]);
            $destination = keepOldValue($destination, $route["destino"]);

            $matchRoute = findRouteWithOriginAndDestination($mysqli, $origin, $destination);

            if ($matchRoute && (int) $route['id_ruta'] !== (int) $matchRoute['id_ruta']) {
                redirectWithError($mysqli, "Esta ruta ya existe.", $id);
            }

            updateRoute($mysqli, $origin, $destination, (int) $route['id_ruta']);

            $mysqli->close();
            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/route/route_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/route/route_form.php?id=" . $id);
            exit();
        }
    }
?>