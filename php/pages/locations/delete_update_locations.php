<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar / Eliminar Ubicación</title>
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

            <h2 class="form-title">Eliminar Ubicación</h2>

            <form id="delete_location_form" class="form">

                <section class="form-section">
                <label for="nombre_ubicacion_delete">Nombre de la ubicación:</label>
                <input type="text" id="nombre_ubicacion_delete" name="nombre_ubicacion_delete" class="form-input">
                </section>

                <button type="submit" id="boton_delete_ubicacion" class="form-button">Eliminar Ubicación
                </button>

            </form>

        </div>
    </section>


    <section>
        <div class="form-container">

            <h2 class="form-title">Actualizar Ubicación</h2>

            <form id="update_location_form" class="form">

                <section class="form-section">
                <label for="nombre_ubicacion_update">Nombre actual:</label>
                <input type="text" id="nombre_ubicacion_update" name="nombre_ubicacion_update" class="form-input">
                </section>

                <section class="form-section">
                <label for="nombre_nuevo">Nuevo nombre:</label>
                <input type="text" id="nombre_nuevo" name="nombre_nuevo" class="form-input">
                </section>

                <section class="form-section">
                <label for="direccion_update">Nueva dirección:</label>
                <input type="text" id="direccion_update" name="direccion_update" class="form-input">
                </section>

                <section class="form-section">
                <label for="departamento_update">Nuevo departamento:</label>
                <input type="text" id="departamento_update" name="departamento_update" class="form-input">
                </section>

                <section class="form-section">
                <label for="localidad_update">Nueva localidad:</label>
                <input type="text" id="localidad_update" name="localidad_update" class="form-input">
                </section>

                <section class="form-section">
                <label for="descripcion_update">Nueva descripción:</label>
                <textarea id="descripcion_update" name="descripcion_update" class="form-input"></textarea>
                </section>


                <button type="submit" id="boton_update_ubicacion" class="form-button">
                    Actualizar Ubicación</button>

            </form>

        </div>
    </section>


    <script src="/js/location/location_delete.js"></script>
    <script src="/js/location/location_update.js"></script>

</body>
</html>