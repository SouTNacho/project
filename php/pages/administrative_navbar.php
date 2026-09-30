<?php 
    $current_page = basename($_SERVER['PHP_SELF']); 

    switch ($current_page) { 
        case 'administrative_panel.php': 
            $active_section = 'panel'; 
            break; 

        case 'manage_elements.php': 
        case 'screen_element.php': 
        case 'element_form.php': 
        case 'element_register.php':
        case 'element_update.php':
            $active_section = 'elements'; 
            break; 

        case 'manage_ambulances.php': 
        case 'ambulance_panel.php':
        case 'ambulance_register.php':
        case 'ambulance_update.php':
        case 'people_ambulance_panel.php':
        case 'things_ambulance_panel.php':
            $active_section = 'ambulances'; 
            break; 

        case 'screen_companions.php': 
        case 'companion_register.php': 
        case 'companion_update.php':
            $active_section = 'companions'; 
            break; 

        case 'manage_patients.php': 
        case 'screen_patient.php': 
        case 'patient_form.php': 
        case 'patient_register.php':
        case 'patient_update.php':
            $active_section = 'patients'; 
            break; 

        case 'muestra_panel.php':
        case 'muestra_list.php':
        case 'muestra_register.php':
        case 'muestra_edit.php':
            $active_section = 'samples'; 
            break; 

        case 'screen_documents.php':
        case 'upload_document.php':
        case 'download_document_qr.php':
            $active_section = 'documents'; 
            break; 

        case 'route_panel.php':
        case 'route_register.php':
        case 'route_update_drop.php':
            $active_section = 'routes'; 
            break; 

        case 'register_locations.php':
        case 'delete_update_locations.php':
            $active_section = 'ubications';
            break;

        case 'screen_surveys.php':
        case 'create_form.php':
            $active_section = 'surveys'; 
            break; 

        case 'panel_traslados.php':
            $active_section = 'transfers'; 
            break; 

        case 'management_employees.php':
        case 'manage_cellphone.php':
        case 'register.php':
        case 'update_employee.php':
            $active_section = 'employees'; 
            break;

        case 'manage_super_user.php':
        case 'super_user_register.php':
        case 'super_user_update.php':
            $active_section = 'super_users';
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
                <a href="/php/pages/document/screen_documents.php"> 
                    <i data-lucide="file-text"></i> 
                    <span>Documentos</span> 
                </a> 
            </li> 

            <li class="menu-item <?= $active_section === 'transfers' ? 'active' : '' ?>"> 
                <a href="/php/pages/traslados/panel_traslados.php"> 
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
                <a href="/php/pages/muestra/muestra_panel.php"> 
                    <i data-lucide="test-tube"></i> 
                    <span>Muestras</span> 
                </a> 
            </li> 

            <li class="menu-item <?= $active_section === 'companions' ? 'active' : '' ?>"> 
                <a href="/php/pages/companion/screen_companions.php"> 
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
                <a href="/php/pages/routes/route_panel.php"> 
                    <i data-lucide="route"></i> 
                    <span>Rutas</span> 
                </a> 
            </li> 

            <li class="menu-item <?= $active_section === 'ubications' ? 'active' : '' ?>"> 
                <a href="/php/pages/locations/register_locations.php"> 
                    <i data-lucide="map-pin"></i> 
                    <span>Ubicaciones</span> 
                </a> 
            </li> 

        </ul> 

    </nav> 

</aside>