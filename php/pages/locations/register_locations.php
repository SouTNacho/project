<?php
session_start();
// Falta implementar el rol
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ubicaciones - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">

    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
</head>

<body>

<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../administrative_navbar.php';
?>

<main id="main">

    <form id="register_location_form" class="form">

        <div>
            <h2>Agregar Ubicación</h2>
        </div>

        <div>
            <label for="nombre_ubicacion">Nombre:</label>
            <input
                type="text"
                id="nombre_ubicacion"
                name="nombre_ubicacion"
                placeholder="Nombre de la ubicación"
            >
            <span id="nombre_ubicacion_msg"></span>
        </div>

        <div>
            <label for="direccion">Dirección:</label>
            <input
                type="text"
                id="direccion"
                name="direccion"
                placeholder="Dirección"
            >
            <span id="direccion_msg"></span>
        </div>

        <div>
            <label for="departamento">Departamento:</label>
            <select id="departamento" name="departamento">
                <option value="">Seleccione...</option>
            </select>
            <span id="departamento_msg"></span>
        </div>

        <div>
            <label for="localidad">Localidad:</label>
            <select id="localidad" name="localidad">
                <option value="">Seleccione...</option>
            </select>
            <span id="localidad_msg"></span>
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea
                id="descripcion"
                name="descripcion"
                placeholder="Escriba una breve descripción"
            ></textarea>
            <span id="descripcion_msg"></span>
        </div>

        <div>
            <a href="/php/pages/locations/delete_update_locations.php">
                <i data-lucide="settings"></i>
                Gestionar
            </a>

            <button
                type="submit"
                id="boton_submit_ubicacion"
                class="form-btn"
            >
                <i data-lucide="square-plus"></i>
                Registrar
            </button>

            <span id="ubicacion_btn_msg" class="form-btn-msg"></span>
        </div>

    </form>

    <section id="view" class="locations-view">
        <h2 class="locations-view-title">Ubicaciones registradas</h2>
        <p class="locations-loading">Cargando ubicaciones...</p>
    </section>

</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>
<script type="module" src="/js/location/location.js"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
