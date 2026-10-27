<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('super_user');

?>

<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="../../../styles/notifications.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../js/notifications.js"></script>

    <meta charset="UTF-8">
    <title>Resultados de Encuestas - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/survey_style.css">
</head>

<body>

    <?php

        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../super_user_pages/super_user_navbar.php';

    ?>

    <main id="main" class="screen-survey-result">

        <div class="service-select-container">
            <label for="service_chose">Seleccione el servicio a analizar</label>
            <select name="service_chose" id="service_chose">
                <option value="">Seleccione un servicio</option>
                <option value="1">Documentos</option>
                <option value="2">Traslados</option>
            </select>
            <span id="service_chose_msg"></span>
        </div>

        <section id="surveys_results" class="surveys-results">
            <article class="chart-container">
                <canvas id="satisfaction_chart"></canvas>
            </article>
            <article class="chart-container">
                <canvas id="boolean_chart"></canvas>
            </article>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/survey/create_results.js"></script>

</body>
</html>