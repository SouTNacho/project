<?php

session_start();

require_once __DIR__ . "/../../conection.php";
require_once __DIR__ . "/../../functions/muestra_functions.php";

$muestra_id = $_GET["id"] ?? "";

if ($muestra_id === "") {
    header("Location: muestra_list.php");
    exit();
}

$mysqli = connection_db();

$muestra = getMuestraById($mysqli, $muestra_id);

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

    <link rel="stylesheet" href="../../../styles/form_style.css">

</head>

<body>

<main>

    <form action="../../actions/muestra/muestra_edit.php" method="POST">

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
                id="muestra_id"
                value="<?= htmlspecialchars($muestra["id_muestra"]) ?>"
                readonly
            >

            <input
                type="hidden"
                name="muestra_id"
                value="<?= htmlspecialchars($muestra["id_muestra"]) ?>"
            >

        </div>


        <div>

            <label for="muestra_code">
                Código
            </label>

            <input
                type="text"
                name="muestra_code"
                id="muestra_code"
                value="<?= htmlspecialchars($muestra["codigo"]) ?>"
                maxlength="10"
                required
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
                required
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
                required
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
                required
            >

        </div>

        <div>

            <input
                type="submit"
                value="GUARDAR CAMBIOS"
            >

        </div>

    </form>


    <br>

    <a href="muestra_list.php">
        Volver a la lista
    </a>

</main>

</body>

</html>