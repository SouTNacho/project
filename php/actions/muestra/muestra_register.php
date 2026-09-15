<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . "/../../functions/muestra_functions.php";
require_once __DIR__ . "/../../models/patient_model.php";
require_once __DIR__ . "/../../conection.php";


function redirectWithError($message)
{
    $_SESSION["errors"] = $message;

    header(
        "Location: /php/pages/muestra/muestra_register.php"
    );

    exit();
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header(
        "Location: /php/pages/muestra/muestra_register.php"
    );

    exit();
}


$codigo = trim($_POST["muestra_code"] ?? "");
$tipo = trim($_POST["muestra_type"] ?? "");
$descripcion = trim($_POST["muestra_description"] ?? "");
$documento = trim($_POST["patient_document"] ?? "");


if (
    $codigo === "" ||
    $tipo === "" ||
    $descripcion === "" ||
    $documento === ""
) {

    redirectWithError(
        "Todos los campos son obligatorios."
    );
}


$mysqli = connection_db();


$patient = findPatientWithDocument(
    $mysqli,
    $documento
);


if (!$patient) {

    $mysqli->close();

    redirectWithError(
        "No existe un paciente con esa cédula."
    );
}


$id_paciente = $patient["id_paciente"];


try {

    createMuestra(
        $mysqli,
        $codigo,
        $tipo,
        $descripcion,
        $id_paciente
    );

    $mysqli->close();

    $_SESSION["success"] =
        "Muestra registrada correctamente.";

    header(
        "Location: /php/pages/muestra/muestra_register.php"
    );

    exit();

} catch (mysqli_sql_exception $e) {

    $mysqli->close();

    error_log($e->getMessage());

    $_SESSION["errors"] =
        "Ocurrió un error al registrar la muestra.";

    header(
        "Location: /php/pages/muestra/muestra_register.php"
    );

    exit();
}

?>