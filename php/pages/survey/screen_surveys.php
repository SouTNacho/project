<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Gestión de Encuestas</title>
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/survey_style.css">
</head>
<body>
<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../super_user_navbar.php';
?>

<main id="main" class="survey-page">
    <section class="survey-management-card">
        <div class="survey-page-heading">
            <div>
                <span class="survey-eyebrow">ENCUESTAS</span>
                <h1>Gestión de encuestas</h1>
                <p>Seleccione la encuesta activa para cada servicio y revise su contenido.</p>
            </div>
            <a class="primary-link" href="/php/pages/survey/create_form.php"> + Crear encuesta</a>
        </div>

        <div class="survey-services" id="general_container"></div>
    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
<script type="module" src="/js/survey/screen_surveys.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script type="module" src="/js/employee/manage_employee.js"></script>

<script>
    lucide.createIcons();
</script>

</body>
</html>