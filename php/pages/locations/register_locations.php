<?php
    session_start();
    // Falta implementar el rol
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Ubicación - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">

    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
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
        <input type="text" id="nombre_ubicacion" name="nombre_ubicacion">
    </div>

    <div>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion">
    </div>

    <div>
        <label for="departamento">Departamento:</label>
        <select id="departamento">
            <option value="">Seleccione...</option>
        </select>
    </div>

    <div>
        <label for="localidad">Localidad:</label>
        <select id="localidad">
            <option value="">Seleccione...</option>
        </select>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"></textarea>
    </div>

    <div>
        <button type="submit" id="boton_submit_ubicacion" class="form-btn">
            Agregar Ubicación
        </button>
    </div>

</form>

        </section>

    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
<script type="module" src="/js/location/location.js"></script>
</body>
</html>