let nombre_ubicacion_delete =
    document.getElementById("nombre_ubicacion_delete");

let boton_delete =
    document.getElementById("boton_delete_ubicacion");


boton_delete.addEventListener("click", async (e) => {

    e.preventDefault();

    nombre_ubicacion_delete.value =
        nombre_ubicacion_delete.value.trim();


    if (nombre_ubicacion_delete.value === "") {
        alert("Ingrese el nombre de la ubicación");
        return;
    }


    let datos = new FormData();

    datos.append("accion", "eliminar");
    datos.append("nombre_ubicacion_delete", nombre_ubicacion_delete.value);


    
    let respuesta = await fetch("/php/actions/location/process_location.php", {
            method: "POST",
            body: datos
        }
    );


    let resultado = await respuesta.text();

    alert(resultado);

    location.reload();

});