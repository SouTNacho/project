<?php

    session_start();
    // Falta autenticar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Panel de Administración</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
</head>

<body id="top">
    <?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/administrative_navbar.php';
?>

    <main class="main flex-center-column">
        <h1>Panel de Administración</h1>
        <section class="main_section">
            <div class="main_content">
                <h2>Hospital de Clínicas</h2>
                <h2>Dr. Manuel Quintela</h2>
                <p>Bienvenido nombre</p>
            </div>
        </section>
        <a href="#top" class="main_up_button" aria-label="Volver al inicio">
            <span class="material-symbols-outlined">stat_1</span>
        </a>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    
    <?php require_once __DIR__ . '/footer.php'; ?>

<script type="module" src="/js/employee/manage_employee.js"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>