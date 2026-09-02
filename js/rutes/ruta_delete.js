//eliminar
let ruta_delete = document.getElementById("ruta_delete");
let boton_delete = document.getElementById("boton_delete_ruta");


boton_delete.addEventListener("click", async (e) => {
    e.preventDefault();

    let datos = new FormData();
    datos.append("ruta_delete", ruta_delete.value.trim());

    datos.append("accion", "eliminar");

    try {
        let respuesta = await fetch("/php/actions/rutes/process_ruta.php", {
            method: "POST",
            body: datos
        });

        let resultado = await respuesta.text();

        console.log(resultado);
        alert(resultado);
        
        ruta_delete.value = "";
        location.reload();


    } catch (error) {
        console.error("Error al enviar los datos:", error);
        alert("Error al eliminar la ruta. Por favor, inténtalo de nuevo.");
        return;
    }
    
});