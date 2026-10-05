<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/route_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
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

        $origin = trim($_POST['route_origin'] ?? '');
        $destination = trim($_POST['route_destination'] ?? '');

        if ( validateEmptyData($origin) || validateEmptyData($destination)) {
            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateString($origin)) {
            redirectionForError("El origen ingresado no es válido.");
        }

        if (!validateString($destination)) {
            redirectionForError("El destino ingresado no es válido.");
        }

        $mysqli = connection_db();

        $route = findRouteWithOriginAndDestination($mysqli, $origin, $destination);

        if ($route) {
            redirectWithError( $mysqli, "Esta ruta ya esta registrada."
            );
        }

        try {

            insertRoute($mysqli, $origin, $destination);

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