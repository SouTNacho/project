<?php
session_start();
// Falta autenticar
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BYP - Panel de Rutas</title>

    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">

    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
</head>

<body>

<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../administrative_navbar.php';
?>

<main id="main">

    <section class="management-control">

        <div class="actions">

            <button type="button" id="register">
                <i data-lucide="square-plus"></i>
                Registrar Ruta
            </button>

        </div>

        <div class="search">

            <div class="search-log-container">

                <input
                    id="filter_search"
                    type="search"
                    placeholder="Buscar ruta por nombre, origen o destino..."
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
                    Todas
                </label>

                <label>
                    <input
                        type="radio"
                        id="filter_active"
                        name="state"
                        value="active"
                    >
                    Activas
                </label>

                <label>
                    <input
                        type="radio"
                        id="filter_inactive"
                        name="state"
                        value="inactive"
                    >
                    Inactivas
                </label>

            </div>

        </div>

    </section>

    <section id="view"></section>

</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
<script type="module" src="/js/rutes/ruta_panel.js"></script>

</body>
</html>
