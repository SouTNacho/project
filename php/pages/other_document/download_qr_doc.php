<?php

    session_start();

    $is_logged = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
    $user_type = $_SESSION['type_user'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
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
        <section class="document-options">
            <h2>Documento disponible</h2>
            <p>¿Desea responder el cuestionario de satisfacción antes de descargar el documento?</p>
            <div class="document-actions">
                <button type="button" id="download_btn">
                    <i data-lucide="download"></i> No, descargar documento
                </button>
                <button type="button" id="form_button">
                    <i data-lucide="clipboard-list"></i> Sí, realizar cuestionario
                </button>
            </div>
        </section>

        <section class="survey-container" id="survey_container"></section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/other_document/manage_qr_document.js"></script>

</body>
</html>