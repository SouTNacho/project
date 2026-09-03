<?php

require_once __DIR__ . "/../../conection.php";
require_once __DIR__ . "/../../functions/muestra_functions.php";

$mysqli = connection_db();

$muestras = getAllMuestras($mysqli);

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Lista de Muestras</title>

    <link rel="stylesheet" href="../../../styles/form_style.css">

</head>

<body>

<div class="form-container">

    <img src="../../../src/logo_small.png" alt="Logo" class="logo">

    <h1>Lista de Muestras</h1>

    <?php if (count($muestras) === 0): ?>

        <p>No hay muestras registradas.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">

            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Subtipo</th>
                <th>Descripción</th>
                <th>Cédula paciente</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($muestras as $muestra): ?>

                <tr>

                    <td>
                        <?php echo $muestra["id_muestra"]; ?>
                    </td>

                    <td>
                        <?php echo $muestra["tipo"]; ?>
                    </td>

                    <td>
                        <?php echo $muestra["subtipo"]; ?>
                    </td>

                    <td>
                        <?php echo $muestra["descripcion"]; ?>
                    </td>

                    <td>
                        <?php echo $muestra["cedula"]; ?>
                    </td>

                    <td>

                        <a href="muestra_edit.php?id=<?php echo $muestra["id_muestra"]; ?>">
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
                             value="<?php echo $muestra["id_muestra"]; ?>"
                         >

                        <button type="submit">
                          Eliminar
                        </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

    <br>

    <a href="muestra_register.php">
        Registrar nueva muestra
    </a>

</div>

</body>

</html>