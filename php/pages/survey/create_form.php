<?php

    session_start();
    // Validar que solo se pueda usar por el encargado de hacer las encuestas

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="create_survey_form">
        <div class="header-form">
            <a href="/php/super_user_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
            <h1 class="form-title">Crear encuesta</h1>
        </div>
        <div>
            <label for="survey_name">Ingrese el nombre</label>
            <input type="text" name="survey_name"
            id="survey_name" class="survey_name" placeholder="Encuesta de satisacción x">
            <label for="survey_service">Seleccione el servicio asociado</label>
            <select id="survey_service">
                <option value="">Seleccione una opción</option>
                <?php

                    require_once "functions/survey_functions.php";
                    require_once "models/survey_model.php";
                    require_once "conection.php";

                    $mysqli = connection_db();
                    $services = getServices($mysqli);

                    if ($services) createServicesOptions($services);
                ?>
            </select>
        </div>
        <div class="question_container">
            <div class="question_options">
                <button type="button" id="multiple_question">Multiple opcion</button>
                <button type="button" id="selection_question">Seleccion</button>
            </div>
        </div>
        <div>
            <button type="submit" id="send_btn">Crear Encuesta</button>
        </div>
    </form>
</body>
<script type="module" src="/js/create_form.js"></script>
</html>