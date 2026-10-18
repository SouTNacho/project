<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Subtipo de Elementos - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../super_user_pages/super_user_navbar.php';
    ?>
    <main id="main">

        <section class="management-control">
            <div class="actions">
                <button type="button" id="register">
                    <i data-lucide="square-plus"></i> Registrar Subtipo de Elemento
                </button>
            </div>
            <div class="search">
                <div class="search-log-container">
                    <input id="filter_search" type="search" placeholder="Buscar subtipo por su nombre ...">
                    <button type="button" id="search">
                        <i data-lucide="search"></i>
                    </button>
                </div>
                <div class="search-filters-container">
                    <span>Tipo:</span>
                    <label>
                        <input type="radio" id="filter_all" name="state" value="all" checked>Todos
                    </label>
                    <label>
                        <input type="radio" id="filter_bio" name="state" value="bio">Biológico
                    </label>
                    <label>
                        <input type="radio" id="filter_nonbio" name="state" value="nonbio">No biológico
                    </label>
                </div>
            </div>
        </section>

  
        <section id="view"></section>

    </main>

    <script type="module" src="/js/element_subtype/manage_subtype.js"></script>

        <?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>
</body>

</html>