<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('super_user');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Categorias - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../super_user_pages/super_user_navbar.php';
    ?>
    <main id="main" class="form-main">
        <?php
            if (isset($_SESSION["success"])) {
                echo '<div class="success-message">' . $_SESSION["success"] . '</div>';
                unset($_SESSION["success"]);
            }

            if (isset($_SESSION["errors"])) {
                echo '<div class="error-message">' . $_SESSION["errors"] . '</div>';
                unset($_SESSION["errors"]);
            }
        ?>
        <form action="" method="post" id="categories_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="category_name">Nombre:</label>
                <input type="text" name="category_name"
                id="category_name" placeholder="Procedimientos">
                <span id="category_name_msg"></span>
            </div>
            <div>
                <a href="/php/pages/category/manage_categories.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="category_btn" class="form-btn"></button>
                <span id="category_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/category/form_category.js"></script>
</body>

</html>