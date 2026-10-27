<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('administrative');

    $user_type = $_SESSION['type_user'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

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
        <section class="index-intro">
            <div class="index-intro-content">
                <span class="index-label">Sesión iniciada</span>
                <h2>Bienvenido, <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?></h2>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>