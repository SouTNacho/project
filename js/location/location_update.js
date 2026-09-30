const updateForm = document.getElementById("update_location_form");

if (updateForm) {

    const idUbicacion = document.getElementById("id_ubicacion_update");
    const nombre = document.getElementById("nombre_ubicacion_update");
    const direccion = document.getElementById("direccion_update");
    const departamento = document.getElementById("departamento_update");
    const localidad = document.getElementById("localidad_update");
    const descripcion = document.getElementById("descripcion_update");

    updateForm.addEventListener("submit", async (e) => {

        e.preventDefault();

        if (!idUbicacion || idUbicacion.value === "") {
            alert("Seleccione una ubicación.");
            return;
        }

        if (
            nombre.value.trim() === "" ||
            direccion.value.trim() === "" ||
            departamento.value.trim() === "" ||
            localidad.value.trim() === ""
        ) {
            alert("Nombre, dirección, departamento y localidad son obligatorios.");
            return;
        }

        const datos = new FormData();

        datos.append("accion", "actualizar");
        datos.append("id_ubicacion", idUbicacion.value);
        datos.append("nombre_ubicacion_update", nombre.value.trim());
        datos.append("direccion_update", direccion.value.trim());
        datos.append("departamento_update", departamento.value.trim());
        datos.append("localidad_update", localidad.value.trim());
        datos.append("descripcion_update", descripcion.value.trim());

        try {

            const respuesta = await fetch(
                "/php/actions/location/process_location.php",
                {
                    method: "POST",
                    body: datos
                }
            );

            const resultado = await respuesta.text();

            if (!respuesta.ok) {
                alert(`Error HTTP ${respuesta.status} al actualizar la ubicación.`);
                console.error(resultado);
                return;
            }

            alert(resultado);

            if (resultado.includes("correctamente")) {
                window.location.href =
                    "/php/pages/locations/register_locations.php";
            }

        } catch (error) {
            console.error(error);
            alert("No se pudo actualizar la ubicación.");
        }
    });
}
