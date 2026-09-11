<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ubicación</title>
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>

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