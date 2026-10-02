<?php
session_start();
// Falta autenticar
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BYP - Panel de Super Usuario</title>

    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">

    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
</head>

<body>

<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/super_user_navbar.php';
?>

<main id="main">

    <section class="main_section">

        <div class="main_content">

            <h1>Panel de Super Usuario</h1>

            <h2>Hospital de Clínicas</h2>
            <h2>Dr. Manuel Quintela</h2>

            <p>Bienvenido Super Usuario</p>

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>

</body>
</html>