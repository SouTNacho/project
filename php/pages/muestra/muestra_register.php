<?php

session_start();

$errors = $_SESSION["errors"] ?? [];
$success = $_SESSION["success"] ?? "";

unset($_SESSION["errors"]);
unset($_SESSION["success"]);

if (!is_array($errors)) {
    $errors = [$errors];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BYP - Registrar Muestra</title>

    <link rel="stylesheet" href="/styles/form_style.css">
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

        <form action="/project/php/actions/muestra/muestra_register.php" method="POST">
            <div class="header-form">
                <h1 class="form-title">Registrar Muestra</h1>
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


            <div>
                <label for="muestra_code">Código</label>
                <input type="text" name="muestra_code" id="muestra_code"
                    placeholder="MUE001" maxlength="10" required class="form-input">

            </div>


            <div>
                <label for="muestra_type">Tipo de muestra</label>
                <input type="text" name="muestra_type" id="muestra_type" placeholder="Sangre" 
                maxlength="50" required class="form-input">
            </div>

            <div>
                <label for="muestra_description">Descripción</label>
                <textarea name="muestra_description"
                    id="muestra_description" placeholder="Descripción de la muestra"
                    maxlength="100" required class="form-input"></textarea>

            </div>


            <div>
                <label for="patient_document">Cédula del paciente</label>
                <input type="text" name="patient_document" id="patient_document"
                    placeholder="12345678" maxlength="8" required class="form-input">

            </div>


            <div>
                <input type="submit" value="REGISTRAR" class="form-button">

            </div>

        </form>

    </main>

</body>

</html>