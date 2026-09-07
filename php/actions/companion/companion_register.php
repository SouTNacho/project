<?php

    session_start();

    require_once "/php/functions/companion_functions.php";
    require_once __DIR__ . "/php/models/companion_model.php";
    require_once __DIR__ . "/php/functions/validations.php";
    require_once __DIR__ . "/php/conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_register.php");
        exit();
    }

?>