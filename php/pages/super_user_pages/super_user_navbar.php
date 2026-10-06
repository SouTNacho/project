<?php

    $current_page = basename($_SERVER['PHP_SELF']);

    switch ($current_page) {

        case 'super_user_panel.php':
            $active_section = 'panel';
            break;

        case 'manage_employees.php':
        case 'screen_employee.php':
        case 'employee_form.php':
            $active_section = 'employees';
            break;

        case 'manage_cellphones.php':
        case 'screen_cellphone.php':
        case 'cellphone_form.php':
            $active_section = 'cellphones';
            break;

        case 'manage_super_users.php':
        case 'screen_super_user.php':
        case 'super_user_form.php':
            $active_section = 'super_users';
            break;

        case 'manage_drivers.php':
        case 'screen_driver.php':
        case 'driver_form.php':
            $active_section = 'drivers';
            break;

        case 'manage_copilots.php':
        case 'screen_copilot.php':
        case 'copilot_form.php':
            $active_section = 'copilots';
            break;

        case 'manage_administratives.php':
        case 'screen_administrative.php':
        case 'administrative_form.php':
            $active_section = 'administratives';
            break;

        case 'manage_samples.php':
        case 'screen_sample.php':
        case 'sample_form.php':
            $active_section = 'samples';
            break;

        case 'manage_surveys.php':
        case 'create_screen_survey.php':
            $active_section = 'surveys';
            break;

        case 'manage_services.php':
        case 'screen_service.php':
        case 'service_form.php':
            $active_section = 'services';
            break;

        case 'manage_actions.php':
        case 'screen_action.php':
        case 'action_form.php':
            $active_section = 'actions';
            break;

        case 'manage_categories.php':
        case 'screen_category.php':
        case 'category_form.php':
            $active_section = 'categories';
            break;

        case 'manage_types.php':
        case 'screen_type.php':
        case 'type_form.php':
            $active_section = 'types';
            break;

        case 'manage_subtypes.php':
        case 'screen_subtype.php':
        case 'subtype_form.php':
            $active_section = 'subtypes';
            break;

        default:
            $active_section = '';
            break;
    }

?>

<aside id="sidebar">
    <div class="logo-container">
        <a href="/index.php" class="logo-link">
            <img src="/src/logo-white.svg" alt="Logotipo del Hospital de Clínicas">
        </a>
    </div>
    <nav class="sidebar-nav" aria-label="Navegación principal">
        <ul class="nav-menu">
            <li class="menu-item <?= $active_section === 'panel' ? 'active' : '' ?>">
                <a href="/php/pages/super_user_pages/super_user_panel.php">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Panel</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'employees' ? 'active' : '' ?>">
                <a href="/php/pages/employee/manage_employees.php">
                    <i data-lucide="users"></i>
                    <span>Funcionarios</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'cellphones' ? 'active' : '' ?>">
                <a href="/php/pages/cellphone/manage_cellphones.php">
                    <i data-lucide="smartphone"></i>
                    <span>Celulares</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'super_users' ? 'active' : '' ?>">
                <a href="/php/pages/super_user/manage_super_users.php">
                    <i data-lucide="shield-user"></i>
                    <span>Super Usuarios</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'drivers' ? 'active' : '' ?>">
                <a href="/php/pages/driver/manage_drivers.php">
                    <i data-lucide="steering-wheel"></i>
                    <span>Conductores</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'copilots' ? 'active' : '' ?>">
                <a href="/php/pages/copilot/manage_copilots.php">
                    <i data-lucide="user-round"></i>
                    <span>Copilotos</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'administratives' ? 'active' : '' ?>">
                <a href="/php/pages/administrative/manage_administratives.php">
                    <i data-lucide="briefcase-business"></i>
                    <span>Administrativos</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'samples' ? 'active' : '' ?>">
                <a href="/php/pages/sample/manage_samples.php">
                    <i data-lucide="test-tube"></i>
                    <span>Muestras</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'surveys' ? 'active' : '' ?>">
                <a href="/php/pages/survey/manage_surveys.php">
                    <i data-lucide="clipboard-list"></i>
                    <span>Encuestas</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'services' ? 'active' : '' ?>">
                <a href="/php/pages/service/manage_services.php">
                    <i data-lucide="hospital"></i>
                    <span>Servicios</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'actions' ? 'active' : '' ?>">
                <a href="/php/pages/action/manage_actions.php">
                    <i data-lucide="zap"></i>
                    <span>Acciones</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'categories' ? 'active' : '' ?>">
                <a href="/php/pages/category/manage_categories.php">
                    <i data-lucide="folders"></i>
                    <span>Categorías</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'types' ? 'active' : '' ?>">
                <a href="/php/pages/type/manage_types.php">
                    <i data-lucide="tags"></i>
                    <span>Tipos</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'subtypes' ? 'active' : '' ?>">
                <a href="/php/pages/element_subtype/management_element_subtype.php">
                    <i data-lucide="tag"></i>
                    <span>Subtipos</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>