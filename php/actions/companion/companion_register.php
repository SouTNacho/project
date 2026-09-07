<?php

    session_start();

    require_once __DIR__ . "/../functions/companion_functions.php";
    require_once __DIR__ . "/../models/companion_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/register.php");
        exit();
    }

?>