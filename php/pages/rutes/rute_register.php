<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutas</title>
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>

    <!-- Formulario para agregar ruta -->
    <form id="register_ruta_form" class="form">
        <h2 class="form-title">Agregar Ruta</h2>
            
            <section class="form-section">
                <label for="nombre_ruta">Nombre*</label>
                <input type="text" name="nombre_ruta" id="nombre_ruta" class="form-input">
            </section>

            <section class="form-section">
                <label for="origen">Origen*</label>
                <input type="text" name="origen" id="origen" class="form-input">
                <br>
            </section>

            <section class="form-section">
                <label for="destino">Destino*</label>
                <input type="text" name="destino" id="destino" class="form-input">
                <br>
            </section>
            
            <section class="form-section">
                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" id="descripcion" class="form-input">
                <br>
            </section>

        <button type="submit" id="boton_submit_ruta" class="form-button">Registrar Ruta</button>
    </form>

    
       
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
    <script src="/js/rutes/ruta.js"></script>
</body>
</html>