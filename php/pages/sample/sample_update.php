<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Muestra - BYP</title>
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

        <form action="/php/actions/sample/sample_update.php" method="post" id="sample_update_form">
            <div class="header-form">
                <h2 class="form-title">Actualizar Muestra</h2>
            </div>
            <div>
                <label for="sample_current_code">Código Actual:</label>
                <input type="text" name="sample_current_code"
                id="sample_current_code" placeholder="A2019851">
                <span id="sample_current_code_msg"></span>
            </div>
            <div>
                <label for="sample_new_code">Nuevo Código:</label>
                <input type="text" name="sample_new_code"
                id="sample_new_code" placeholder="A2019851">
                <span id="sample_new_code_msg"></span>
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
            <div>
                <input type="submit" value="REGISTRAR" id="sample_update_btn">
                <span id="sample_update_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/sample/update_sample.js"></script>
</body>
</html>