<?php

session_start();

if (
    !isset($_SESSION["logged"]) ||
    $_SESSION["logged"] !== true ||
    !isset($_SESSION["user_type"]) ||
    $_SESSION["user_type"] !== "copilot"
) {
    header("Location: /php/pages/login.php");
    exit;
}

?>
