<?php

    session_start();
    // Falta autenticar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Panel Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
</head>
<body id="top">
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="/php/super_user_panel.html">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="actions/logout.php">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="management_employees.php">Gest. Funcionarios</a>
            </li>
            <li class="navbar_list_item">
                <a href="register.php">Reg. Funcionarios</a>
            </li>
            <li class="navbar_list_item">
                <a href="update_employee.php">Act.Funcionarios</a>
            </li>
            <li class="navbar_list_item">
                <a href="manage_cellphone.php">Gest. Teléfonos</a>
            </li>
        </ul>
    </nav>
    <main class="main flex-center-column">
        <h1>Panel de Administrador</h1>
        <section class="main_section">
            <div class="main_content">
                <h2>Hospital de Clínicas</h2>
                <h2>Dr. Manuel Quintela</h2>
                <p>Bienvenido Administrador</p>
            </div>
        </section>
        <a href="#top" class="main_up_button" aria-label="Volver al inicio">
            <span class="material-symbols-outlined">stat_1</span>
        </a>
    </main>
    <footer class="footer">
        <div class="footer_logo flex-center-column">
            <a href="/php/super_user_panel.html">
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