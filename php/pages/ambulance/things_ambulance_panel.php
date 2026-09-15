<?php

    session_start();
    // Falta autenticar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Panel de Trasladados por Ambulancia</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
</head>
<body id="top">
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="/php/pages/administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="/php/actions/logout.php">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_panel.php">Panel de ambulancias</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/muestra/muestra_panel.php">Panel Muestra</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/element/element_register.php">Registrar Elemento</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/element/element_update.php">Act. Elementos</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/element/manage_elements.php">Admin. Elementos</a>
            </li>
        </ul>
    </nav>
    <main class="main flex-center-column">
        <h1>Panel de Traslados</h1>
        <section class="main_section">
            <div class="main_content">
                <h2>Panel de traslados</h2>
                
            </div>
        </section>
        <a href="#top" class="main_up_button" aria-label="Volver al inicio">
            <span class="material-symbols-outlined">stat_1</span>
        </a>
    </main>
    <footer class="footer">
        <div class="footer_logo flex-center-column">
            <a href="/php/pages/administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <ul class="footer_list flex-center-column">
            <li class="footer_list_item">
                <a href="#">Políticas</a>
            </li>
            <li class="footer_list_item">
                <a href="#">Derechos</a>
            </li>
            <li class="footer_list_item">
                <a href="#">Contacto</a>
            </li>
        </ul>
        <p class="footer_content flex-center-column">
            &copy; 2026 Hospital de Clínicas. Todos los derechos reservados. Desarrollado por BYP.
        </p>
    </footer>
</body>
</html>