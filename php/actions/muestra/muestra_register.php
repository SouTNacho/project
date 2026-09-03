<?php

session_start();

require_once __DIR__ . "/../../functions/muestra_functions.php";
require_once __DIR__ . "/../../models/patient_model.php";
require_once __DIR__ . "/../../functions/validations.php";
require_once __DIR__ . "/../../conection.php";


function redirectionForError($message) {

    $_SESSION["errors"] = $message;

    header(
        "Location: /project/project/private_project/php/pages/muestra/muestra_register.php"
    );

    exit();
}


function redirectWithError($mysqli, $message) {

    $mysqli->close();

    $_SESSION["errors"] = $message;

    header(
        "Location: /project/project/private_project/php/pages/muestra/muestra_register.php"
    );

    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo = trim($_POST["muestra_type"] ?? "");
    $subtipo = trim($_POST["muestra_subtype"] ?? "");
    $descripcion = trim($_POST["muestra_description"] ?? "");
    $documento = trim($_POST["patient_document"] ?? "");


    if (
        $tipo === "" ||
        $subtipo === "" ||
        $descripcion === "" ||
        $documento === ""
    ) {

        redirectionForError(
            "Todos los campos son obligatorios"
        );
    }


    if (!validateString($tipo)) {

        redirectionForError(
            "El tipo ingresado no es válido"
        );
    }


    if (!validateString($subtipo)) {

        redirectionForError(
            "El subtipo ingresado no es válido"
        );
    }


    if (!validateDocument($documento)) {

        redirectionForError(
            "La cédula ingresada no es válida"
        );
    }


    // Creamos la conexión antes de consultar al paciente
    $mysqli = connection_db();


    // Buscamos el paciente mediante la cédula ingresada
    $patient = findPatientWithDocument(
        $mysqli,
        $documento
    );


    if (!$patient) {

        redirectWithError(
            $mysqli,
            "No existe un paciente con esa cédula"
        );
    }


    // Obtenemos el ID del paciente para usarlo como FK
    $id_paciente = $patient["id_paciente"];


    try {

        createMuestra(
            $mysqli,
            $tipo,
            $subtipo,
            $descripcion,
            $id_paciente
        );

        $mysqli->close();

        $_SESSION["success"] =
            "Muestra registrada correctamente";

        header(
            "Location: /project/project/private_project/php/pages/muestra/muestra_register.php"
        );

        exit();

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();

        error_log($e->getMessage());

        $_SESSION["errors"] =
            "Ocurrió un error al registrar la muestra.";

        header(
            "Location: /project/project/private_project/php/pages/muestra/muestra_register.php"
        );

        exit();
    }
}

?>