<?php

    session_start();

    if (isset($_SESSION["success"])) {
        echo $_SESSION["success"];
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["errors"])) {
        echo $_SESSION["errors"];
        unset($_SESSION["errors"]);
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Actualizar ambulancia</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
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
            <a href="/php/actions/logout.php">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_panel.php">Panel Ambulancias</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_register.php">Registrar Ambulancia</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_update.php">Act. Ambulancia</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/manage_ambulances.php">Gestionar Ambulancia</a>
            </li>
        </ul>
    </nav>
    <main>
        <div class="form-container">
        <form action="/php/actions/ambulance/ambulance_update.php" method="post" id="ambulance_update_form">
            <div class="header-form">
                
                <h1 class="form-title">Actualizar ambulancia</h1>
            </div>
            <section class="form-section">
                <label for="ambulance_current_registration">Matricula Actual</label>
                <input type="text" name="ambulance_current_registration"
                id="ambulance_current_registration" placeholder="IAD1234" class="form-input">
                <span id="ambulance_current_registration_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_new_registration">Nueva Matricula</label>
                <input type="text" name="ambulance_new_registration"
                id="ambulance_new_registration" placeholder="IAD1234" class="form-input">
                <span id="ambulance_new_registration_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_brand">Marca</label>
                <input type="text" name="ambulance_brand"
                id="ambulance_brand" placeholder="Mercedez Benz" class="form-input">
                <span id="ambulance_brand_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_model">Modelo</label>
                <input type="text" name="ambulance_model"
                id="ambulance_model" placeholder="Spark" class="form-input">
                <span id="ambulance_model_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_year">Año</label>
                <input type="text" name="ambulance_year"
                id="ambulance_year" placeholder="2025" class="form-input">
                <span id="ambulance_year_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_description">Descripción</label>
                <textarea name="ambulance_description" id="ambulance_description" 
                placeholder="Escriba una breve descripción" class="form-input"></textarea>
                <span id="ambulance_description_msg"></span>
            </section>
            <div>
                <input type="submit" value="ACTUALIZAR" id="ambulance_update_btn" class="form-button">
                <span id="ambulance_update_btn_msg"></span>
            </div>
</div>
        </form>

        <?php
   /* include "/php/conection.php";
    $con = connection_db(); //llamo a la funcion de conexion a bd

$resultado = $con->query("SELECT * FROM ambulancia");

echo "<h2>Lista de Ambulancias</h2>";

    if ($resultado->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Matricula</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Descripcion</th></tr>";
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["matricula"] . "</td>";
            echo "<td>" . $row["marca"] . "</td>";
            echo "<td>" . $row["modelo"] . "</td>";
            echo "<td>" . $row["anio"] . "</td>";
            echo "<td>" . $row["descripcion"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay ambulancias registradas.";
    }

    */?>
    </main>
    <script type="module" src="/js/ambulance/update_ambulance.js"></script>
</body>
</html>