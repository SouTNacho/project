const deleteForm = document.getElementById("delete_location_form");

if (deleteForm) {

    const idUbicacion = document.getElementById("id_ubicacion_delete");
    const nombre = document.getElementById("nombre_ubicacion_delete");

    deleteForm.addEventListener("submit", async (e) => {

        e.preventDefault();

        if (!idUbicacion || idUbicacion.value === "") {
            alert("Seleccione una ubicación.");
            return;
        }

        if (!confirm(`¿Está seguro de eliminar la ubicación "${nombre.value}"?`)) {
            return;
        }

        const datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("id_ubicacion", idUbicacion.value);

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
                alert(`Error HTTP ${respuesta.status} al eliminar la ubicación.`);
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
            alert("No se pudo eliminar la ubicación.");
        }
    });
}
