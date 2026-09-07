let nombre_ubicacion_update = document.getElementById("nombre_ubicacion_update");
let nombre_nuevo = document.getElementById("nombre_nuevo");
let direccion_update = document.getElementById("direccion_update");
let departamento_update = document.getElementById("departamento_update");
let localidad_update =document.getElementById("localidad_update");
let descripcion_update = document.getElementById("descripcion_update");
let boton_update = document.getElementById("boton_update_ubicacion");


boton_update.addEventListener("click", async (e) => {

    e.preventDefault();

    nombre_ubicacion_update.value = nombre_ubicacion_update.value.trim();
    nombre_nuevo.value = nombre_nuevo.value.trim();
    direccion_update.value = direccion_update.value.trim();
    departamento_update.value = departamento_update.value.trim();
    localidad_update.value = localidad_update.value.trim();
    descripcion_update.value = descripcion_update.value.trim();


    if (nombre_ubicacion_update.value === "") {
        alert("Ingrese el nombre actual de la ubicación");
        return;
    }


    if (nombre_nuevo.value === "" && direccion_update.value === "" && departamento_update.value === "" 
        && localidad_update.value === "" && descripcion_update.value === "") {
        alert("No hay datos para actualizar");
        return;
    }


    let datos = new FormData();

    datos.append("accion", "actualizar");

    datos.append("nombre_ubicacion_update", nombre_ubicacion_update.value);
    datos.append("nombre_nuevo", nombre_nuevo.value);
    datos.append("direccion_update", direccion_update.value);
    datos.append("departamento_update", departamento_update.value);
    datos.append("localidad_update", localidad_update.value);
    datos.append("descripcion_update", descripcion_update.value);


    let respuesta = await fetch("/php/actions/location/process_location.php", {
            method: "POST",
            body: datos
        }
    );


    let resultado = await respuesta.text();

    alert(resultado);

    location.reload();

});