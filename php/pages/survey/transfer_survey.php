<?php

    session_start();

    $is_logged = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
    $user_type = $_SESSION['type_user'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <link rel="stylesheet" href="../../styles/notifications.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/notifications.js"></script>
    
    <meta charset="UTF-8">
    <title>Documento - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>

    <header id="header">
        <div class="title-container">
            <h1>Hospital de Clínicas BYP</h1>
        </div>
    </header>

    <main id="main" class="survey-main">
        <h2>Bienvenido</h2>
        <h3>Encuesta de satisfacción</h3>

        <section class="survey-container" id="survey_container"></section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/survey/manage_transfer_survey.js"></script>

</body>
</html>