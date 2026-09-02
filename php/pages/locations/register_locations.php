<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ubicación</title>
</head>
<body>

    <section>
        <div class="form-container">

            <h2>Agregar Ubicación</h2>

            <form id="register_location_form">

                <label for="nombre_ubicacion">Nombre:</label>
                <input type="text" id="nombre_ubicacion" name="nombre_ubicacion">

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">

                <label for="departamento">Departamento:</label>
                <select id="departamento">
                    <option value="">Seleccione...</option>
                </select>

                <label for="localidad">Localidad:</label>
                <select id="localidad">
                    <option value="">Seleccione...</option>
                </select>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"></textarea>

                <button type="submit" id="boton_submit_ubicacion">
                    Agregar Ubicación
                </button>

            </form>

        </div>
    </section>

    <script src="/js/location/location.js"></script>

</body>
</html>