import formTools from "../library.js";

const departamento = document.getElementById("departamento");
const localidad = document.getElementById("localidad");
const form = document.getElementById("register_location_form");
const view = document.getElementById("view");

if (departamento && localidad) {
    formTools.loadDepartmentsSelect(departamento);

    departamento.addEventListener("change", () => {
        formTools.loadLocationsSelect(localidad, departamento);
    });
}

if (form) {

    const nombre = document.getElementById("nombre_ubicacion");
    const direccion = document.getElementById("direccion");
    const descripcion = document.getElementById("descripcion");
    const boton = document.getElementById("boton_submit_ubicacion");

    form.addEventListener("submit", async (e) => {

        e.preventDefault();

        const nombreTrim = nombre.value.trim();
        const direccionTrim = direccion.value.trim();
        const departamentoTrim = departamento.value.trim();
        const localidadTrim = localidad.value.trim();
        const descripcionTrim = descripcion.value.trim();

        if (
            nombreTrim === "" ||
            direccionTrim === "" ||
            departamentoTrim === "" ||
            localidadTrim === ""
        ) {
            alert("Complete todos los campos obligatorios");
            return;
        }

        boton.disabled = true;

        const datos = new FormData();

        datos.append("accion", "agregar");
        datos.append("nombre_ubicacion", nombreTrim);
        datos.append("direccion", direccionTrim);
        datos.append("departamento", departamentoTrim);
        datos.append("localidad", localidadTrim);
        datos.append("descripcion", descripcionTrim);

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
                console.error(resultado);
                alert(`Error HTTP ${respuesta.status} al registrar la ubicación.`);
                return;
            }

            alert(resultado);

            if (resultado.includes("correctamente")) {
                form.reset();
                localidad.innerHTML = '<option value="">Seleccione...</option>';
                formTools.loadDepartmentsSelect(departamento);
                await loadLocations();
            }

        } catch (error) {
            console.error(error);
            alert("No se pudo registrar la ubicación.");
        } finally {
            boton.disabled = false;
        }
    });
}

async function loadLocations() {

    if (!view) return;

    const data = new FormData();
    data.append("accion", "listar");

    try {

        const response = await fetch(
            "/php/actions/location/process_location.php",
            {
                method: "POST",
                body: data
            }
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || "No se pudieron cargar las ubicaciones.");
        }

        renderLocations(result.item);

    } catch (error) {
        console.error(error);
        view.innerHTML = `
            <h2 class="locations-view-title">Ubicaciones registradas</h2>
            <p>No se pudieron cargar las ubicaciones.</p>
        `;
    }
}

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function renderLocations(locations) {

    view.innerHTML = `
        <h2 class="locations-view-title">Ubicaciones registradas</h2>
    `;

    if (locations.length === 0) {
        view.innerHTML += `
            <p>No hay ubicaciones registradas.</p>
        `;
        lucide.createIcons();
        return;
    }

    const list = document.createElement("ul");
    list.className = "locations-list";

    locations.forEach(location => {

        const item = document.createElement("li");

        item.innerHTML = `
            <p>${escapeHtml(location.nombre)}</p>
            <p>
                ${escapeHtml(location.direccion)}<br>
                ${escapeHtml(location.localidad)}, ${escapeHtml(location.departamento)}<br>
                ${escapeHtml(location.descripcion || "Sin descripción")}
            </p>
            <button
                type="button"
                class="update-btn location-update-btn"
                data-id="${Number(location.id_ubicacion)}"
                title="Modificar ubicación"
            >
                <i data-lucide="refresh-cw"></i>
            </button>
            <button
                type="button"
                class="delete-btn location-delete-btn"
                data-id="${Number(location.id_ubicacion)}"
                data-name="${escapeHtml(location.nombre)}"
                title="Eliminar ubicación"
            >
                <i data-lucide="trash-2"></i>
            </button>
        `;

        list.appendChild(item);
    });

    view.appendChild(list);
    lucide.createIcons();

    document.querySelectorAll(".location-update-btn").forEach(button => {
        button.addEventListener("click", () => {
            window.location.href =
                "/php/pages/locations/delete_update_locations.php?id=" +
                button.dataset.id;
        });
    });

    document.querySelectorAll(".location-delete-btn").forEach(button => {
        button.addEventListener("click", async () => {

            if (!confirm(`¿Está seguro de eliminar la ubicación "${button.dataset.name}"?`)) {
                return;
            }

            const data = new FormData();
            data.append("accion", "eliminar");
            data.append("id_ubicacion", button.dataset.id);

            try {

                const response = await fetch(
                    "/php/actions/location/process_location.php",
                    {
                        method: "POST",
                        body: data
                    }
                );

                const result = await response.text();

                if (!response.ok) {
                    alert(`Error HTTP ${response.status} al eliminar la ubicación.`);
                    console.error(result);
                    return;
                }

                alert(result);

                if (result.includes("correctamente")) {
                    await loadLocations();
                }

            } catch (error) {
                console.error(error);
                alert("No se pudo eliminar la ubicación.");
            }
        });
    });
}

if (view) {
    document.addEventListener("DOMContentLoaded", loadLocations);
}
