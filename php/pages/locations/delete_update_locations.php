<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar / Eliminar Ubicación</title>
</head>
<body>

    <section>
        <div class="form-container">

            <h2>Eliminar Ubicación</h2>

            <form id="delete_location_form">

                <label for="nombre_ubicacion_delete">
                    Nombre de la ubicación:
                </label>

                <input
                    type="text"
                    id="nombre_ubicacion_delete"
                    name="nombre_ubicacion_delete"
                >

                <button type="submit" id="boton_delete_ubicacion">
                    Eliminar Ubicación
                </button>

            </form>

        </div>
    </section>


    <section>
        <div class="form-container">

            <h2>Actualizar Ubicación</h2>

            <form id="update_location_form">

                <label for="nombre_ubicacion_update">
                    Nombre actual:
                </label>

                <input
                    type="text"
                    id="nombre_ubicacion_update"
                    name="nombre_ubicacion_update"
                >

                <label for="nombre_nuevo">
                    Nuevo nombre:
                </label>

                <input
                    type="text"
                    id="nombre_nuevo"
                    name="nombre_nuevo"
                >

                <label for="direccion_update">
                    Nueva dirección:
                </label>

                <input
                    type="text"
                    id="direccion_update"
                    name="direccion_update"
                >

                <label for="departamento_update">
                    Nuevo departamento:
                </label>

                <input
                    type="text"
                    id="departamento_update"
                    name="departamento_update"
                >

                <label for="localidad_update">
                    Nueva localidad:
                </label>

                <input
                    type="text"
                    id="localidad_update"
                    name="localidad_update"
                >

                <label for="descripcion_update">
                    Nueva descripción:
                </label>

                <textarea
                    id="descripcion_update"
                    name="descripcion_update"
                ></textarea>

                <button type="submit" id="boton_update_ubicacion">
                    Actualizar Ubicación
                </button>

            </form>

        </div>
    </section>


    <script src="/js/location/location_delete.js"></script>
    <script src="/js/location/location_update.js"></script>

</body>
</html>