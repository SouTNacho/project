<?php

session_start();

require_once __DIR__ . "/../../conection.php";
require_once __DIR__ . "/../../functions/muestra_functions.php";


$errors = $_SESSION["errors"] ?? [];
$success = $_SESSION["success"] ?? "";

unset($_SESSION["errors"]);
unset($_SESSION["success"]);


if (!is_array($errors)) {
    $errors = [$errors];
}


$mysqli = connection_db();

$muestras = getAllMuestras($mysqli);

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BYP - Muestras</title>

    <link rel="stylesheet" href="../../../styles/form_style.css">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
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
                <a href="/php/pages/muestra/muestra_panel.php">Panel Muestra</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/muestra/muestra_register.php">Registrar Muestra</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/muestra/muestra_list.php">Ver Muestras</a>
            </li>
        </ul>
    </nav>
    <main>

        <div class="header-form">

            <h1 class="form-title">
                Lista de Muestras
            </h1>

        </div>


        <?php if (!empty($errors)): ?>

            <div class="error-message">

                <?php foreach ($errors as $error): ?>

                    <p>
                        <?= htmlspecialchars($error) ?>
                    </p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <?php if ($success !== ""): ?>

            <div class="success-message">

                <p>
                    <?= htmlspecialchars($success) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (empty($muestras)): ?>

            <p>
                No hay muestras registradas.
            </p>

        <?php else: ?>

            <table border="1" cellpadding="8">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Cédula paciente</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($muestras as $muestra): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($muestra["id_muestra"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($muestra["codigo"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($muestra["tipo"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($muestra["descripcion"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($muestra["cedula"]) ?>
                            </td>

                            <td>

                                <a
                                    href="muestra_edit.php?id=<?= $muestra["id_muestra"] ?>"
                                >
                                    Editar
                                </a>

                                <form
                                    action="../../actions/muestra/muestra_delete.php"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="muestra_id"
                                        value="<?= $muestra["id_muestra"] ?>"
                                    >

                                    <button type="submit">
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>


        <br>


        

    </main>

</body>

</html>