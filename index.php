<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Hospital de Clínicas</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
</head>
<body id="top">

    <?php

        if (!isset($_SESSION['type_user'])) {

            require_once __DIR__ . '/php/pages/header_nav_user.php';
        }

    ?>

    <main class="main">

        <section class="main_section">
            <div>
                <h2>Dr. Manuel Quintela</h2>
                <h3>Dr. Manuel Quintela</h3>
                <p>Bienvenido...</p>
            </div>
        </section>

        <a href="#top" class="main_up_button" aria-label="Volver al inicio">
            <span class="material-symbols-outlined">stat_1</span>
        </a>

    </main>

    <?php

        if (!isset($_SESSION['type_user'])) {

            require_once __DIR__ . '/php/pages/footer.php';
        }

    ?>

</body>
</html>