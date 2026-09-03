<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . "/../../functions/muestra_functions.php";
require_once __DIR__ . "/../../conection.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $muestra_id = trim($_POST["muestra_id"] ?? "");


    if ($muestra_id === "") {

        $_SESSION["errors"] =
            "Identificador de muestra inválido.";

        header(
            "Location: /project/project/private_project/php/pages/muestra/muestra_list.php"
        );

        exit();
    }


    $mysqli = connection_db();


    try {

        removeMuestra(
            $mysqli,
            $muestra_id
        );

        $mysqli->close();

        $_SESSION["success"] =
            "Muestra eliminada correctamente";

        header(
            "Location: /project/project/private_project/php/pages/muestra/muestra_list.php"
        );

        exit();

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();

        error_log($e->getMessage());

        $_SESSION["errors"] =
            "Ocurrió un error al eliminar la muestra.";

        header(
            "Location: /project/project/private_project/php/pages/muestra/muestra_list.php"
        );

        exit();
    }
}

?>