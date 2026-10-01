<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Muestras - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../administrative_navbar.php';
    ?>
    <main id="main" class="form-main">
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
        <form action="" method="post" id="sample_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="sample_code">Código de la muestra:</label>
                <input type="text" name="sample_code"
                id="sample_code" placeholder="A2019851">
                <span id="sample_code_msg"></span>
            </div>
            <div>
                <label for="sample_document_patient">Cédula del paciente:</label>
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
                <a href="/php/pages/sample/manage_samples.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="sample_btn" class="form-btn"></button>
                <span id="sample_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/sample/form_sample.js"></script>
</body>

</html>