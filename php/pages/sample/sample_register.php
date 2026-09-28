<?php

    // Más adelante permitir con un crud que tipo. Y en elemento
    // el subtipo sea manejado por el super usuario haciendo tablas tablas

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Muestra - BYP</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <main>

        <?php
            if (isset($_SESSION["success"])) {
                echo '<div class="success-message">' . $_SESSION["success"] . '</div>';
                unset($_SESSION["success"]);
            }

            if (isset($_SESSION["errors"])) {
                echo '<div class="error-message">' . $_SESSION["errors"] . '</div>';
                unset($_SESSION["errors"]);
            }
        ?>

        <form action="/php/actions/sample/sample_register.php" method="post" id="sample_register_form">
            <div class="header-form">
                <h2 class="form-title">Registrar Muestra</h2>
            </div>
            <div>
                <label for="sample_code">Código de la Muestra:</label>
                <input type="text" name="sample_code"
                id="sample_code" placeholder="A2019851">
                <span id="sample_code_msg"></span>
            </div>
            <div>
                <label for="sample_document_patient">Cedula del Paciente:</label>
                <input type="text" name="sample_document_patient"
                id="sample_document_patient" placeholder="52019851">
                <span id="sample_document_patient_msg"></span>
            </div>
            <div>
                <label for="sample_type">Tipo de Muestra:</label>
                <select name="sample_type" id="sample_type">
                    <option value="">Seleccione una opción</option>
                    <option value="Sangre">Sangre</option>
                    <option value="Orina">Orina</option>
                    <option value="Heces">Heces</option>
                    <option value="Secreciones">Secreciones</option>
                    <option value="Esputo">Esputo</option>
                    <option value="Saliva">Saliva</option>
                    <option value="Líquidos corporales">Líquidos corporales</option>
                    <option value="Tejidos">Tejidos</option>
                    <option value="Células">Células</option>
                    <option value="Médula ósea">Médula ósea</option>
                    <option value="Material reproductivo">Material reproductivo</option>
                    <option value="Material dermatológico">Material dermatológico</option>
                    <option value="Material de heridas">Material de heridas</option>
                    <option value="Otro">Otro</option>
                </select>
                <span id="sample_type_msg"></span>
            </div>
            <div>
                <label for="sample_description">Descripción:</label>
                <textarea name="sample_description" id="sample_description"
                placeholder="Describa la muestra"></textarea>
                <span id="sample_description_msg"></span>
            </div>
            <div>
                <input type="submit" value="REGISTRAR" id="sample_register_btn">
                <span id="sample_register_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/sample/register_sample.js"></script>
</body>
</html>