<?php

session_start();

require_once __DIR__ . "/../../functions/muestra_functions.php";
require_once __DIR__ . "/../../models/patient_model.php";
require_once __DIR__ . "/../../functions/validations.php";
require_once __DIR__ . "/../../conection.php";


function redirectWithError($message) {

    $_SESSION["errors"] = $message;

    header(
        "Location: /project/project/private_project/php/pages/muestra/muestra_list.php"
    );

    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $muestra_id = trim($_POST["muestra_id"] ?? "");
    $tipo = trim($_POST["muestra_type"] ?? "");
    $subtipo = trim($_POST["muestra_subtype"] ?? "");
    $descripcion = trim($_POST["muestra_description"] ?? "");
    $documento = trim($_POST["patient_document"] ?? "");


    if (
        $muestra_id === "" ||
        $tipo === "" ||
        $subtipo === "" ||
        $descripcion === "" ||
        $documento === ""
    ) {

        redirectWithError(
            "Todos los campos son obligatorios."
        );
    }


    if (!validateString($tipo)) {

        redirectWithError(
            "El tipo ingresado no es válido."
        );
    }


    if (!validateString($subtipo)) {

        redirectWithError(
            "El subtipo ingresado no es válido."
        );
    }


    if (!validateDocument($documento)) {

        redirectWithError(
            "La cédula ingresada no es válida."
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

        editMuestra(
            $mysqli,
            $tipo,
            $subtipo,
            $descripcion,
            $id_paciente,
            $muestra_id
        );

        $mysqli->close();

        $_SESSION["success"] =
            "Muestra modificada correctamente.";

        header(
            "Location: /project/project/private_project/php/pages/muestra/muestra_list.php"
        );

        exit();

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();

        error_log($e->getMessage());

        redirectWithError(
            "Ocurrió un error al modificar la muestra."
        );
    }
}

?>