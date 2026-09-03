<?php

session_start();

$errors = $_SESSION["errors"] ?? [];
$success = $_SESSION["success"] ?? "";

unset($_SESSION["errors"]);
unset($_SESSION["success"]);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar Muestra</title>

    <link rel="stylesheet" href="../../../styles/form_style.css">
</head>

<body>

    <div class="form-container">

        <img
            src="../../../src/logo_small.png"
            alt="Logo"
            class="logo"
        >

        <h1>Registrar Muestra</h1>

        <?php if (!empty($errors)): ?>

            <div class="error-message">

                <?php foreach ($errors as $error): ?>

                    <p>
                        <?php echo htmlspecialchars($error); ?>
                    </p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <?php if ($success != ""): ?>

            <div class="success-message">

                <p>
                    <?php echo htmlspecialchars($success); ?>
                </p>

            </div>

        <?php endif; ?>


        <form
            action="../../actions/muestra/muestra_register.php"
            method="POST"
        >

            <div class="form-group">

                <label for="muestra_type">
                    Tipo de muestra
                </label>

                <input
                    type="text"
                    id="muestra_type"
                    name="muestra_type"
                    maxlength="50"
                    required
                >

            </div>


            <div class="form-group">

                <label for="muestra_subtype">
                    Subtipo de muestra
                </label>

                <input
                    type="text"
                    id="muestra_subtype"
                    name="muestra_subtype"
                    maxlength="50"
                    required
                >

            </div>


            <div class="form-group">

                <label for="muestra_description">
                    Descripción
                </label>

                <input
                    type="text"
                    id="muestra_description"
                    name="muestra_description"
                    maxlength="100"
                    required
                >

            </div>


            <div class="form-group">

                <label for="patient_document">
                    Cédula del paciente
                </label>

                <input
                    type="text"
                    id="patient_document"
                    name="patient_document"
                    maxlength="8"
                    required
                >

            </div>


            <button type="submit">
                Registrar muestra
            </button>

        </form>

    </div>

</body>

</html>