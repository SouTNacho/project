<?php

    session_start();
    // Falta implementar el rol

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Encuestas - BYP</title>
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
    <main id="main" class="survey-page">
        <section class="survey-management-card">
            <div class="survey-page-heading">
                <div>
                    <span class="survey-eyebrow">ENCUESTAS</span>
                    <h1>Gestión de encuestas</h1>
                    <p>Seleccione la encuesta activa para cada servicio y revise su contenido.</p>
                </div>
                <button type="button" id="register" class="primary-link">
                    <i data-lucide="square-plus"></i>Crear encuesta
                </button>
            </div>
            <div class="survey-services" id="general_container"></div>
        </section>

    </main>
    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/survey/screen_surveys.js"></script>

</body>
</html>