<?php

    session_start();

    if (
        !isset($_SESSION["logged"]) ||
        $_SESSION["logged"] !== true ||
        $_SESSION["user_type"] !== "superuser"
    ) {
        header("Location: php/login.php");
        exit;
    }

?>