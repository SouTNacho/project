<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ubicación</title>
    <link rel="stylesheet" href="/styles/form_style.css">
    <link rel="stylesheet" href="/styles/general_style.css">

</head>
<body>

    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="/php/pages/administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="/index.html">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/routes/route_panel.php">Panel Rutas</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/locations/register_locations.php">Registrar Ubicación</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/locations/delete_update_locations.php">Act. o Elim. Ubicación</a>
            </li>
            
        </ul>
    </nav>

    <section>
        <div class="form-container">

            <h2 class="form-title">Agregar Ubicación</h2>

            <form id="register_location_form">
                
            <section class="form-section">
                <label for="nombre_ubicacion">Nombre:</label>
                <input type="text" id="nombre_ubicacion" name="nombre_ubicacion" class="form-input">
            </section>
            <section class="form-section">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-input">
            </section>
            <section class="form-section">
                <label for="departamento">Departamento:</label>
                <select id="departamento" class="form-input">
                    <option value="">Seleccione...</option>
                </select>
            </section>
            <section class="form-section">
                <label for="localidad">Localidad:</label>
                <select id="localidad" class="form-input">
                    <option value="">Seleccione...</option>
                </select>
            </section>
            <section class="form-section">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-input"></textarea>
            </section>
            
                <button type="submit" id="boton_submit_ubicacion" class="form-button">
                    Agregar Ubicación
                </button>

            </form>

        </div>
    </section>

    <script src="/js/location/location.js"></script>
    <script type="module" src="/js/location/location.js"></script>

</body>
</html>