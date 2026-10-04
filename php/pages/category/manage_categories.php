<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - BYP</title>
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
</head>
<body>
<?php
    require_once __DIR__ . '/../header.php';
    require_once __DIR__ . '/../super_user_navbar.php';
?>
<main id="main">
    <section class="category-page">
        <?php if (isset($_SESSION['success'])): ?>
            <p class="category-notice"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errors'])): ?>
            <p class="category-error"><?= htmlspecialchars($_SESSION['errors'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        <div class="category-heading">
            <h2>Gestión de categorías</h2>
            <a class="category-register-link" href="/php/pages/category/category_register.php">
                <i data-lucide="square-plus"></i>
                Registrar categoría
            </a>
        </div>
        <div class="category-table-wrap">
            <table class="category-table" id="category-table" hidden>
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
                </thead>
                <tbody id="category-list"></tbody>
            </table>
            <p class="category-empty" id="category-empty">Cargando categorías...</p>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../footer.php'; ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
<script type="module" src="/js/category/manage_categories.js"></script>
</body>
</html>