<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar">
    <div class="logo-container">
        <a href="/index.php" class="logo-link">
            <img src="/src/logo-white.svg" alt="Logotipo del Hospital de Clínicas">
        </a>
    </div>
      
    <nav class="sidebar-nav" aria-label="Navegación principal">
        <ul class="nav-menu">

            <li class="menu-item <?= $current_page === 'super_user_panel.php' ? 'active' : '' ?>">
                <a href="/php/pages/super_user_panel.php">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Panel</span>
                </a>
            </li>

            <li class="menu-item <?= in_array($current_page, ['management_employees.php', 'register.php']) ? 'active' : '' ?>">
                <a href="/php/pages/employee/management_employees.php">
                    <i data-lucide="users"></i>
                    <span>Funcionarios</span>
                </a>
            </li>

            <li class="menu-item <?= in_array($current_page, ['screen_surveys.php', 'create_form.php']) ? 'active' : '' ?>">
                <a href="/php/pages/survey/screen_surveys.php">
                    <i data-lucide="clipboard-list"></i>
                    <span>Encuestas</span>
                </a>
            </li>

            <li class="menu-item <?= in_array($current_page, ['manage_super_user.php', 'super_user_register.php']) ? 'active' : '' ?>">
                <a href="/php/pages/super_user/manage_super_user.php">
                    <i data-lucide="shield-user"></i>
                    <span>Administradores</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="/php/pages/">
                    <i data-lucide="boxes"></i>
                    <span>Elementos</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="/php/pages/">
                    <i data-lucide="test-tube"></i>
                    <span>Muestras</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="/php/pages/">
                    <i data-lucide="briefcase-medical"></i>
                    <span>Servicios</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="/php/pages/">
                    <i data-lucide="file-text"></i>
                    <span>Documentos</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="/php/pages/">
                    <i data-lucide="user-cog"></i>
                    <span>Copilotos</span>
                </a>
            </li>

        </ul>
    </nav>
</aside>