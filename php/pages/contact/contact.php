<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contacto - BYP</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">

    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/index_style.css">
    <link rel="stylesheet" href="/styles/contact_style.css">
</head>

<body class="public-page">

    <?php require_once __DIR__ . '/../header.php'; ?>

    <main id="main" class="index-main">

        <section class="contact-section">


            <div class="contact-back">
                <a href="/index.php">
                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>
                    Volver al inicio
                </a>
            </div>


            <div class="contact-header">

                <span class="public-document-label">
                    Contacto
                </span>

                <h1>
                    Contáctenos
                </h1>

                <p>
                    Complete el siguiente formulario para enviar su consulta.
                </p>

            </div>



            <div class="form-container">

                <form method="post" id="contact_form">

                    <div>

                        <div>
                            <input
                                type="text"
                                name="contact_name"
                                id="contact_name"
                                placeholder="John Doe"
                            >

                            <span id="contact_name_msg"></span>
                        </div>

                        <div>
                            <input
                                type="email"
                                name="contanct_email"
                                id="contanct_email"
                                placeholder="example@gmail.com"
                            >

                            <span id="contanct_email_msg"></span>
                        </div>

                    </div>


                    <div>

                        <textarea
                            name="contanct_message"
                            id="contanct_message"
                            placeholder="Escriba su mensaje"
                        ></textarea>

                        <span id="contanct_message_msg"></span>

                    </div>


                    <div>

                        <input
                            type="submit"
                            value="Enviar"
                            id="element_btn"
                        >

                        <span id="element_btn_msg"></span>

                    </div>

                </form>

            </div>

        </section>

    </main>


    <?php require_once __DIR__ . '/../footer.php'; ?>


    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>