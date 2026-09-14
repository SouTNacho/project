<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Descargar Documento</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/document_style.css">
</head>
<body id="top">
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="login.php">Funcionario</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/administrative_panel.php">Inicio</a>
            </li>
            <li class="navbar_list_item">
                <a href="">Visualizar Documentos</a>
            </li>
            <li class="navbar_list_item">
                <a href="">Gestionar Traslados</a>
            </li>
        </ul>
    </nav>
    <main>
        <section>
            <div class="form-container">
                <h2 class="form-title">¿Desea responder el cuestionario de satisfacción?</h2>

                <div class="botones">
                    <button id="download_btn">No, Descargar Documento</button>
                    <button id="form_button">Sí, Realizar Cuestionario</button>
                </div>
            </div>
        </section>
        <section class="hidden" id="satisfaction_container_form">
            <form method="post" id="satisfaction_form">
                <div class="header-form">
                    <a href="../index.html">
                        <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                    </a>
                    <h1 class="form-title">Formulario de Satisfaccion</h1>
                </div>
                <div>
                    <label for="user_document">Cedula</label>
                    <input type="text" name="user_document" id="user_document" placeholder="123456789">
                    <button id="user_document_btn">Confirmar cedula</button>
                    <span id="user_document_msg"></span>
                </div>
                <div>
                    <label for="user_first_name">Nombre</label>
                    <input type="text" name="user_first_name" id="user_first_name">
                    <span id="user_first_name_msg"></span>
                </div>
                <div>
                    <label for="user_last_name">Apellido</label>
                    <input type="text" name="user_last_name" id="user_last_name">
                    <span id="user_last_name_msg"></span>
                </div>
                <div class="phone_container">
                    <div>
                    <select name="user_cellphone_code" id="user_cellphone_code">
                        <option value="">Seleccione una opción</option>
                    </select>
                    </div>
                    <div>
                    <input type="text" name="user_cellphone_number"
                    id="user_cellphone_number" placeholder="99789876"
                    inputmode="numeric">
                    </div>
                    <span id="user_cellphone_number_msg"></span>
                </div>
                <div>
                    <label for="user_address">Direccion</label>
                    <input type="text" name="user_address" id="user_address">
                    <span id="user_address_msg"></span>
                </div>
                <div>
                    <label for="user_email">Email</label>
                    <input type="email" name="user_email" id="user_email">
                    <span id="user_email_msg"></span>
                </div>
                <div>
                    <label for="satisfaction_value">Nivel de satisfaccion con el servicio</label>
                    <select name="satisfaction_value" id="satisfaction_value">
                        <option value="">Seleccione una opción</option>
                        <option value="1">Muy insatisfecho</option>
                        <option value="2">insatisfecho</option>
                        <option value="3">Neutral</option>
                        <option value="4">Satisfecho</option>
                        <option value="5">Muy Satisfecho</option>
                    </select>
                    <span id="satisfaction_value_msg"></span>
                </div>
                <div>
                    <input type="submit" value="Enviar" id="send_form_btn">
                    <button id="cancel_btn">Cancelar</button>
                    <span id="send_form_btn_msg"></span>
                </div>
            </form>
        </section>
    </main>
    <footer class="footer">
        <div class="footer_logo flex-center-column">
            <a href="/php/administrative_panel.html">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <ul class="footer_list flex-center-column">
            <li class="footer_list_item">
                <a href="#">Políticas</a>
            </li>
            <li class="footer_list_item">
                <a href="#">Derechos</a>
            </li>
            <li class="footer_list_item">
                <a href="#">Contacto</a>
            </li>
        </ul>
        <p class="footer_content flex-center-column">
            &copy; 2026 Hospital de Clínicas. Todos los derechos reservados. Desarrollado por BYP.
        </p>
    </footer>
    <script type="module" src="/js/manage_qr_document.js"></script>
</body>
</html>