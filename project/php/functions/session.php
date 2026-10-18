<?php

    function redirectLoggedUser() {

        if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true) {
            return;
        }

        switch ($_SESSION["type_user"] ?? '') {
            case "administrative":

                header("Location: /php/pages/administrative_pages/administrative_panel.php");
                exit();
            case "driver":

                header("Location: /php/pages/driver/driver_panel.php");
                exit();
            case "super_user":

                header("Location: /php/pages/super_user_pages/super_user_panel.php");
                exit();
            default:

                $_SESSION = [];
                session_destroy();
                header("Location: /php/pages/login.php");
                exit();
        }
    }

    function validateSession($user_type) {

        if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true) {
            header("Location: /php/pages/login.php");
            exit();
        }

        if (($_SESSION["type_user"] ?? '') !== $user_type) {
            http_response_code(403);
            exit("No tiene permisos para acceder a esta página.");
        }

        return true;
    }

    function validateActionSession($user_types) {
        header("Content-Type: application/json; charset=UTF-8");

        if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true) {

            http_response_code(401);
            echo json_encode(
                ["success" => false,
                "message" => "Debe iniciar sesión."]);
            exit();
        }

        if (!in_array($_SESSION["type_user"] ?? '', (array) $user_types, true)) {

            http_response_code(403);
            echo json_encode(
                ["success" => false,
                "message" => "No tiene permisos para realizar esta acción."]);
            exit();
        }
    }

?>