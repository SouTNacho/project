import formTools from "../library.js";

let departamento = document.getElementById("departamento");
let localidad = document.getElementById("localidad");

formTools.loadDepartmentsSelect(departamento);

departamento.addEventListener("change", () => {
    formTools.loadLocationsSelect(localidad, departamento);
});

let nombre = document.getElementById("nombre_ubicacion");
let direccion = document.getElementById("direccion");
//let departamento = document.getElementById("departamento");
//let localidad = document.getElementById("localidad");
let descripcion = document.getElementById("descripcion");

let boton_agregar = document.getElementById("boton_submit_ubicacion");


boton_agregar.addEventListener("click", async (e) => {

    e.preventDefault();

    nombre.value = nombre.value.trim();
    direccion.value = direccion.value.trim();
    departamento.value = departamento.value.trim();
    localidad.value = localidad.value.trim();
    descripcion.value = descripcion.value.trim();


    if (nombre.value === "" ||direccion.value === "" ||departamento.value === "" ||localidad.value === "") {
        alert("Complete todos los campos obligatorios");
        return;
    }


    let datos = new FormData();

    datos.append("accion", "agregar");
    datos.append("nombre_ubicacion", nombre.value);
    datos.append("direccion", direccion.value);
    datos.append("departamento", departamento.value);
    datos.append("localidad", localidad.value);
    datos.append("descripcion", descripcion.value);


    let respuesta = await fetch("/php/actions/location/process_location.php", {
            method: "POST",
            body: datos
        }
    );


    let resultado = await respuesta.text();

    alert(resultado);

    location.reload();

});