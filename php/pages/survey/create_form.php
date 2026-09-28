<?php

    session_start();
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Encuesta - BYP</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <form id="create_survey_form">
        <div>
            <h2>Crear Encuesta</h2>
        </div>
        <div>
            <label for="survey_name">Ingrese el nombre</label>
            <input type="text" name="survey_name"
            id="survey_name" class="survey_name"
            placeholder="Encuesta de satisfacción">
            <span id="survey_name_msg"></span>
        </div>
        <div>
            <label for="survey_service">Seleccione el servicio asociado</label>
            <select id="survey_service">
                <option value="">Seleccione una opción</option>
            </select>
            <span id="survey_service_msg"></span>
        </div>
        <div class="question_container">
            <div class="question_options">
                <button type="button" id="boolean_question">Sí / No</button>
                <button type="button" id="satisfaction_question">Nivel de satisfacción</button>
            </div>
        </div>
        <span id="question_container_msg"></span>
        <div>
            <button type="submit" id="send_survey_btn">Crear Encuesta</button>
        </div>
    </form>
    <script type="module" src="/js/survey/create_form.js"></script>
</body>
</html>