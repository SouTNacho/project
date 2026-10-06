<?php

    session_start();
    // Falta implementar el rol

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/panel_style.css">
</head>

<body>

    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/administrative_navbar.php';
    ?>

    <main id="main" class="panel-main">

        <div>
            <h2>Panel de Administración</h2>
            <p>Bienvenido al panel de administración. Aqui puede gestionar la documentacion y traslados de el Hospital de Clínica</p>
        </div>

    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>