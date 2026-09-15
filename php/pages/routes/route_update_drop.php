<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar o eliminar Rutas</title>
    <link rel="stylesheet" href="/styles/form_style.css">
    <link rel="stylesheet" href="/styles/general_style.css">
</head>
<body>
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="/php/pages/administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="/index.html">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/routes/route_panel.php">Panel Rutas</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/routes/route_register.php">Registrar Ruta</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/routes/route_update_drop.php">Act. o Elim. Ruta</a>
            </li>
            
        </ul>
    </nav>

<!-- Formulario para borrar ruta -->
    <form class="form">
        <h2 class="form-title">Eliminar Ruta</h2>
        <section class="form-section">
        <label for="ruta_delete">Nombre*</label>
        <input type="text" name="ruta_delete" id="ruta_delete" class="form-input">
</section>
        <button type="submit" id="boton_delete_ruta" class="form-button">Eliminar Ruta</button>
    </form>


    <!-- Formulario para actualizar ruta -->
    <form class="form">
        <h2 class="form-title">Actualizar Ruta</h2>

        <section class="form-section">
        <label for="nombre_ruta_update">Nombre*</label>
        <input type="text" name="nombre_ruta_update" id="nombre_ruta_update" class="form-input">
</section>
        <section class="form-section">
        <label for="nombre_nuevo">Nuevo Nombre</label>
        <input type="text" name="nombre_nuevo" id="nombre_nuevo" class="form-input">
</section>
        <section class="form-section">
        <label for="origen_update">Origen</label>
        <input type="text" name="origen_update" id="origen_update" class="form-input">
</section>

        <section class="form-section">
        <label for="destino_update">Destino</label>
        <input type="text" name="destino_update" id="destino_update" class="form-input">
</section>

        <section class="form-section">
        <label for="descripcion_update">Descripcion</label>
        <input type="text" name="descripcion_update" id="descripcion_update" class="form-input">
        </section>
        <button type="submit" id="boton_update_ruta" class="form-button">Actualizar Ruta</button>

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
    <script src="/js/routes/ruta_delete.js"></script>
    <script src="/js/routes/ruta_update.js"></script>
</body>
</html>