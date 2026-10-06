<?php

    session_start();

    require_once __DIR__ . '/../../conection.php';
    require_once __DIR__ . '/../../models/category_model.php';

    $category_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    if ($category_id === false || $category_id === null || $category_id < 1) {
        $_SESSION['errors'] = 'La categoría seleccionada no es válida.';
        header('Location: /php/pages/category/manage_categories.php');
        exit();
    }

    $mysqli = connection_db();
    $category = findCategoryById($mysqli, $category_id);
    $mysqli->close();

    if (!$category) {
        $_SESSION['errors'] = 'La categoría seleccionada ya no existe.';
        header('Location: /php/pages/category/manage_categories.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Categoría - BYP</title>
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
<?php
    require_once __DIR__ . '/../header.php';
    require_once __DIR__ . '/../super_user_navbar.php';
?>
<main id="main" class="form-main">
    <?php if (isset($_SESSION['errors'])): ?>
        <p class="error-message"><?= htmlspecialchars($_SESSION['errors'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    <form action="/php/actions/category/category_update.php" method="post" class="form">
        <div>
            <h2 class="form-title">Actualizar categoría</h2>
        </div>
        <input type="hidden" name="category_id" value="<?= (int) $category['id_categoria'] ?>">
        <div>
            <label for="category_name">Nombre:</label>
            <input type="text" name="category_name" id="category_name" maxlength="50" value="<?= htmlspecialchars($category['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div>
            <a href="/php/pages/category/manage_categories.php">
                <i data-lucide="arrow-left"></i>
                Volver
            </a>
            <button type="submit" class="form-btn">ACTUALIZAR</button>
        </div>
    </form>
</main>
<?php require_once __DIR__ . '/../footer.php'; ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>