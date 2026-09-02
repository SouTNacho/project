<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar o eliminar Rutas</title>
</head>
<body>

<!-- Formulario para borrar ruta -->
    <form>
        <h2>Eliminar Ruta</h2>
        <label for="ruta_delete">Nombre*</label>
        <input type="text" name="ruta_delete" id="ruta_delete">
        <button type="submit" id="boton_delete_ruta">Eliminar Ruta</button>
    </form>


    <!-- Formulario para actualizar ruta -->
    <form>
        <h2>Actualizar Ruta</h2>
        <label for="nombre_ruta_update">Nombre*</label>
        <input type="text" name="nombre_ruta_update" id="nombre_ruta_update">
        <br>
        <label for="nombre_nuevo">Nuevo Nombre</label>
        <input type="text" name="nombre_nuevo" id="nombre_nuevo">
        <br>
        <label for="origen_update">Origen</label>
        <input type="text" name="origen_update" id="origen_update">
        <br>
        <label for="destino_update">Destino</label>
        <input type="text" name="destino_update" id="destino_update">
        <br>
        <label for="descripcion_update">Descripcion</label>
        <input type="text" name="descripcion_update" id="descripcion_update">

        <button type="submit" id="boton_update_ruta">Actualizar Ruta</button>

    </form>
    <section id="ruta_list">

 <?php
    require_once __DIR__ . "/../../conection.php";
    $con = connection_db(); //llamo a la funcion de conexion a bd

$resultado = $con->query("SELECT * FROM ruta");

echo "<h2>Lista de Rutas</h2>";

    if ($resultado->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Nombre</th><th>Origen</th><th>Destino</th><th>Descripcion</th></tr>";
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["origen"] . "</td>";
            echo "<td>" . $row["destino"] . "</td>";
            echo "<td>" . $row["descripcion"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay rutas registradas.";
    }

    ?>
    </section>
    <script src="/js/rutes/ruta_delete.js"></script>
    <script src="/js/rutes/ruta_update.js"></script>
</body>
</html>