<?php
    $current_page = basename($_SERVER['PHP_SELF']);

    switch ($current_page) {
        case 'administrative_panel.php':
            $active_section = 'panel';
            break;

        case 'manage_elements.php':
        case 'screen_element.php':
        case 'element_form.php':
            $active_section = 'elements';
            break;

        case 'manage_ambulances.php':
        case 'screen_ambulance.php':
        case 'ambulance_form.php':
            $active_section = 'ambulances';
            break;

        case 'manage_companions.php':
        case 'screen_companion.php':
        case 'companion_form.php':
            $active_section = 'companions';
            break;

        case 'manage_patients.php':
        case 'screen_patient.php':
        case 'patient_form.php':
            $active_section = 'patients';
            break;

        case 'manage_samples.php':
        case 'screen_sample.php':
        case 'sample_form.php':
            $active_section = 'samples';
            break;

        case 'manage_documents.php':
        case 'screen_document.php':
        case 'document_form.php':
            $active_section = 'documents';
            break;

        case 'manage_routes.php':
        case 'screen_route.php':
        case 'route_form.php':
            $active_section = 'routes';
            break;

        case 'manage_ubications.php':
        case 'screen_ubication.php':
        case 'ubication_form.php':
            $active_section = 'ubications';
            break;

        case 'manage_surveys.php':
        case 'screen_survey.php':
        case 'survey_form.php':
            $active_section = 'surveys';
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
                <a href="/php/pages/administrative_panel.php">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Panel</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'documents' ? 'active' : '' ?>">
                <a href="">
                    <i data-lucide="file-text"></i>
                    <span>Documentos</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'trasletes' ? 'active' : '' ?>">
                <a href="">
                    <i data-lucide="ambulance"></i>
                    <span>Traslados</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'patients' ? 'active' : '' ?>">
                <a href="/php/pages/patient/manage_patients.php">
                    <i data-lucide="user-round"></i>
                    <span>Pacientes</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'samples' ? 'active' : '' ?>">
                <a href="/php/pages/sample/manage_sample.php">
                    <i data-lucide="test-tube"></i>
                    <span>Muestras</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'companions' ? 'active' : '' ?>">
                <a href="/php/pages/companion/manage_companion.php">
                    <i data-lucide="users-round"></i>
                    <span>Acompañantes</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'ambulances' ? 'active' : '' ?>">
                <a href="/php/pages/ambulance/manage_ambulances.php">
                    <i data-lucide="truck"></i>
                    <span>Ambulancias</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'elements' ? 'active' : '' ?>">
                <a href="/php/pages/element/manage_elements.php">
                    <i data-lucide="boxes"></i>
                    <span>Elementos</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'routes' ? 'active' : '' ?>">
                <a href="">
                    <i data-lucide="route"></i>
                    <span>Rutas</span>
                </a>
            </li>
            <li class="menu-item <?= $active_section === 'ubications' ? 'active' : '' ?>">
                <a href="">
                    <i data-lucide="map-pin"></i>
                    <span>Ubicaciones</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>