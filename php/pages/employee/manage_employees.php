<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Empleados - BYP</title>
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
                    <i data-lucide="square-plus"></i> Registrar Empleado
                </button>
            </div>
            <div class="search">
                <div class="search-log-container">
                    <input id="filter_search" type="search" placeholder="Buscar empleado por su cédula ...">
                    <button type="button" id="search">
                        <i data-lucide="search"></i>
                    </button>
                </div>
                <div class="search-filters-container">
                    <span>Estado:</span>
                    <label>
                        <input type="radio" id="filter_all" name="state" value="all" checked>Todos
                    </label>
                    <label>
                        <input type="radio" id="filter_active" name="state" value="active">Activos
                    </label>
                    <label>
                        <input type="radio" id="filter_inactive" name="state" value="inactive">Inactivos
                    </label>
                    <label>
                        <input type="radio" id="filter_deleted" name="state" value="deleted">Eliminados
                    </label>
                </div>
            </div>
        </section>

        <section id="view"></section>

    </main>
    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/employee/manage_employee.js"></script>
</body>

</html>