//agregar
let nombre = document.getElementById("nombre_ruta");
let origen = document.getElementById("origen");
let destino = document.getElementById("destino");
let descripcion = document.getElementById("descripcion");
let boton_agregar = document.getElementById("boton_submit_ruta");


boton_agregar.addEventListener("click", async (e) => {
    e.preventDefault();

    //les saco los espacio innecesarios de antes y despues del input
    let nombreTrim=nombre.value.trim();
    let origenTrim=origen.value.trim();
    let destinoTrim=destino.value.trim();
    let descripcionTrim=descripcion.value.trim();

    //campos obligatorios
    if (nombreTrim=== "" || origenTrim=== "" || destinoTrim=== ""){
        alert("completa todos los campos que tienen asteriscos");
        return;
    }



    let datos = new FormData();
    //agregar rutas
    datos.append("nombre", nombreTrim);
    datos.append("origen", origenTrim);
    datos.append("destino", destinoTrim);
    datos.append("descripcion", descripcionTrim);

    datos.append("accion", "agregar");

    //mandar datos al archivo php
    try {
        let respuesta = await fetch("/php/actions/rutes/process_ruta.php", {
            method: "POST",
            body: datos
        });

        let resultado = await respuesta.text();

        console.log(resultado);
        alert(resultado);

        nombre.value = "";
        origen.value = "";
        destino.value = "";
        descripcion.value = "";
        location.reload();


    } catch (error) {
        console.error("Error al enviar los datos:", error);
        alert("Error al registrar la ruta. Por favor, inténtalo de nuevo.");
        return;
    }
});