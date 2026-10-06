<?php

    session_start();
    // Falta implementar el rol

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Encuesta - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/survey_style.css">
</head>

<body>

    <?php

        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../super_user_pages/super_user_navbar.php';

    ?>

    <main id="main" class="survey-page">
        <section class="survey-builder-card">
            <div class="survey-page-heading">
                <div>
                    <span class="survey-eyebrow">ENCUESTAS</span>
                    <h1>Crear encuesta</h1>
                    <p>Configure la encuesta y vea cómo quedará antes de guardarla.</p>
                </div>
                <button type="button" id="back" class="survey-back-button">
                    <i data-lucide="arrow-left"></i> Volver a encuestas
                </button>
            </div>

            <form id="create_survey_form">
                <div class="survey-basic-grid">
                    <div class="survey-field">
                        <label for="survey_name">Nombre de la encuesta</label>
                        <input type="text" name="survey_name" id="survey_name" placeholder="Ej.: Encuesta de satisfacción de documentos">
                        <span id="survey_name_msg" class="field-message"></span>
                    </div>

                    <div class="survey-field">
                        <label for="survey_service">Servicio asociado</label>
                        <select id="survey_service">
                            <option value="">Seleccione un servicio</option>
                        </select>
                        <span id="survey_service_msg" class="field-message"></span>
                    </div>
                </div>

                <section class="question-builder">
                    <div class="section-heading">
                        <div>
                            <span class="survey-eyebrow">CONSTRUCTOR</span>
                            <h2>Preguntas</h2>
                            <p>
                                Agregue el tipo de pregunta que necesita.
                                Después escriba la pregunta directamente en cada tarjeta.
                            </p>
                        </div>
                    </div>

                    <div class="question-options">
                        <button type="button" id="boolean_question" class="question-type-button">
                            <i data-lucide="circle-check" class="question-type-icon"></i>
                            <span>
                                <strong>Sí / No</strong>
                                <small>Respuesta de dos opciones</small>
                            </span>
                        </button>

                        <button type="button" id="satisfaction_question" class="question-type-button">
                            <i data-lucide="star" class="question-type-icon"></i>
                            <span>
                                <strong>Nivel de satisfacción</strong>
                                <small>Escala de cinco opciones</small>
                            </span>
                        </button>
                    </div>

                    <div id="question_container" class="question-list">
                        <div id="question_empty" class="question-empty">
                            <span>+</span>
                            <p>Aún no hay preguntas</p>
                            <small>Elegí un tipo de pregunta arriba para comenzar.</small>
                        </div>
                    </div>
                    <span id="question_container_msg" class="field-message"></span>
                </section>

                <section class="survey-live-preview">
                    <div class="section-heading">
                        <div>
                            <span class="survey-eyebrow">VISTA PREVIA</span>
                            <h2>Así verá la encuesta el usuario</h2>
                        </div>
                    </div>

                    <div id="survey_preview" class="preview-card">
                        <h3 id="preview_title">Nombre de la encuesta</h3>
                        <p id="preview_empty">Las preguntas aparecerán aquí a medida que las agregues.</p>
                        <div id="preview_questions"></div>
                    </div>
                </section>

                <div class="survey-form-actions">
                    <button type="button" id="cancel" class="secondary-action">
                        Cancelar
                    </button>

                    <button type="submit" id="send_survey_btn" class="primary-action">
                        Crear Encuesta
                    </button>
                </div>
            </form>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/survey/create_form.js"></script>

</body>
</html>