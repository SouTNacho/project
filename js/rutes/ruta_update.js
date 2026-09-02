//actualizar
let nombre_update = document.getElementById("nombre_ruta_update");
let nombre_nuevo =document.getElementById("nombre_nuevo");
let origen_update = document.getElementById("origen_update");
let destino_update = document.getElementById("destino_update");
let descripcion_update = document.getElementById("descripcion_update");
let boton_update = document.getElementById("boton_update_ruta");

 


boton_update.addEventListener("click", async (e) => {
    e.preventDefault();
    let datos = new FormData();
    datos.append("nombre_update", nombre_update.value.trim());
    datos.append("nombre_nuevo", nombre_nuevo.value.trim());
    datos.append("origen_update", origen_update.value.trim());
    datos.append("destino_update", destino_update.value.trim());
    datos.append("descripcion_update", descripcion_update.value.trim());


    datos.append("accion", "actualizar");

    if (nombre_update=== ""){
        alert("completa todos los campos que tienen asteriscos");
        return;
    }


    try {
        let respuesta = await fetch("/php/actions/rutes/process_ruta.php", {
            method: "POST",
            body: datos
        });

        let resultado = await respuesta.text();

        console.log(resultado);
        alert(resultado);

        nombre_update.value = "";
        nombre_nuevo.value = "";
        origen_update.value = "";
        destino_update.value = "";
        descripcion_update.value = "";
        location.reload();


    } catch (error) {
        console.error("Error al enviar los datos:", error);
        alert("Error al registrar la ruta. Por favor, inténtalo de nuevo.");
        return;
    }
});