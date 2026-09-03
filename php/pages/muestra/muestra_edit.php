<?php

session_start();

require_once __DIR__ . "/../../conection.php";
require_once __DIR__ . "/../../functions/muestra_functions.php";

$muestra_id = $_GET["id"] ?? "";

$mysqli = connection_db();

$muestra = getMuestraById(
    $mysqli,
    $muestra_id
);

$mysqli->close();

if (!$muestra) {
    die("La muestra no existe.");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BYP - Editar Muestra</title>

    <link rel="shortcut icon" href="../../../src/logo_small.png" type="image/x-icon">

    <link rel="stylesheet" href="../../../styles/form_style.css">

</head>

<body>

    <main>

        <form
            action="../../actions/muestra/muestra_edit.php"
            method="post"
        >

            <div class="header-form">

                <a href="muestra_list.php">

                    <img
                        src="../../../src/logo_small.png"
                        alt="Logo del Hospital de Clínicas"
                    >

                </a>

                <h1 class="form-title">
                    Editar Muestra
                </h1>

            </div>


            <div>

                <label for="muestra_id">
                    ID de muestra
                </label>

                <input
                    type="text"
                    name="muestra_id"
                    id="muestra_id"
                    value="<?= htmlspecialchars($muestra["id_muestra"]) ?>"
                    readonly
                >

            </div>


            <div>

                <label for="muestra_type">
                    Tipo
                </label>

                <input
                    type="text"
                    name="muestra_type"
                    id="muestra_type"
                    value="<?= htmlspecialchars($muestra["tipo"]) ?>"
                    maxlength="50"
                >

            </div>


            <div>

                <label for="muestra_subtype">
                    Subtipo
                </label>

                <input
                    type="text"
                    name="muestra_subtype"
                    id="muestra_subtype"
                    value="<?= htmlspecialchars($muestra["subtipo"]) ?>"
                    maxlength="50"
                >

            </div>


            <div>

                <label for="muestra_description">
                    Descripción
                </label>

                <textarea
                    name="muestra_description"
                    id="muestra_description"
                    maxlength="100"
                ><?= htmlspecialchars($muestra["descripcion"]) ?></textarea>

            </div>


            <div>

                <label for="patient_document">
                    Cédula del paciente
                </label>

                <input
                    type="text"
                    name="patient_document"
                    id="patient_document"
                    value="<?= htmlspecialchars($muestra["cedula"]) ?>"
                    maxlength="8"
                >

            </div>


            <div>

                <input
                    type="submit"
                    value="GUARDAR CAMBIOS"
                >

            </div>

        </form>

    </main>

</body>

</html>