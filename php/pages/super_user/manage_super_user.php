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
    <link rel="stylesheet" href="/styles/view_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body id="top">
    <?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../super_user_navbar.php';
?>
    <main id="main">

    <section class="management-control">

        <div class="actions">

            <button type="button" id="register">
                <i data-lucide="square-plus"></i>
                Registrar Super Usuario
            </button>

        </div>

        <div class="search">

            <div class="search-log-container">

                <input
                    id="filter_search"
                    type="search"
                    placeholder="Buscar funcionario..."
                >

                <button type="button" id="search">
                    <i data-lucide="search"></i>
                </button>

            </div>

            <div class="search-filters-container">

                <span>Estado:</span>

                <label>
                    <input
                        type="radio"
                        id="filter_all"
                        name="state"
                        value="all"
                        checked
                    >
                    Todos
                </label>

                <label>
                    <input
                        type="radio"
                        id="filter_active"
                        name="state"
                        value="active"
                    >
                    Activos
                </label>

                <label>
                    <input
                        type="radio"
                        id="filter_inactive"
                        name="state"
                        value="inactive"
                    >
                    Inactivos
                </label>

                <label>
                    <input
                        type="radio"
                        id="filter_deleted"
                        name="state"
                        value="deleted"
                    >
                    Eliminados
                </label>

            </div>

        </div>

    </section>

    <section id="view"></section>

    <dialog id="change-password-dialog">

        <form id="change_password_form">
        </form>

        <div>
            <button type="button" id="confirm_btn">
                Cambiar
            </button>

            <button type="button" id="cancel_btn">
                Cancelar
            </button>
        </div>

    </dialog>

        <?php

            require_once __DIR__ . "/../../functions/super_user_functions.php";
            require_once __DIR__ . "/../../models/super_user_model.php";
            require_once __DIR__ . "/../../conection.php";

            $mysqli = connection_db();
            $super_users = findAllSuperUsers($mysqli);

            echo "<div class='super-users-container'>";
/*
            if ($super_users) {

                
                echo "<h2>Gestionar Administradores</h2>";
                echo "<ul>";
                createSuperUsersList($super_users, $mysqli);
                echo "</ul>";

            } else {
                echo "<p>No se encontraron administradores.</p>";
            }

            echo "</div>";
*/
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