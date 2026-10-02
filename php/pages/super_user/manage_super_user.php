<?php
session_start();
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
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../super_user_navbar.php';
?>
    <main>

        <?php

            require_once __DIR__ . "/../../functions/super_user_functions.php";
            require_once __DIR__ . "/../../models/super_user_model.php";
            require_once __DIR__ . "/../../conection.php";

            $mysqli = connection_db();
            $super_users = findAllSuperUsers($mysqli);

            echo "<div class='super-users-container'>";

            if ($super_users) {

                
                echo "<h2>Gestionar Administradores</h2>";
                echo "<ul>";
                createSuperUsersList($super_users, $mysqli);
                echo "</ul>";

            } else {
                echo "<p>No se encontraron administradores.</p>";
            }

            echo "</div>";

        ?>

    </main>
    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/super_user/manage_super_user.js"></script>

    

<script>
    lucide.createIcons();
</script>
</body>
</html>