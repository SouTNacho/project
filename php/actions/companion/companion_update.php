<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/companion_functions.php";
    require_once __DIR__ . "/../../models/companion_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_update.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_update.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $new_document = trim($_POST['companion_new_document'] ?? '');
        $current_document = trim($_POST['companion_current_document'] ?? '');
        $first_name = trim($_POST['companion_first_name'] ?? '');
        $last_name = trim($_POST['companion_last_name'] ?? '');

        if (!validateDocument($current_document)) {
            redirectionForError("La cedula actual no es válida");
        }

        if (!validateEmptyData($new_document)) {
            if (!validateDocument($new_document)) {
                redirectionForError("La cedula nueva no es válida");
            }
        }

        if (!validateEmptyData($first_name)) {
            if (!validateName($first_name)) {
                redirectionForError("El nombre ingresado no es válido");
            }
        }

        if (!validateEmptyData($last_name)) {
            if (!validateName($last_name)) {
                redirectionForError("El apellido ingresado no es válido");
            }
        }

        $mysqli = connection_db();
        $companion = findCompanionWithDocument($mysqli, $current_document);

        if(!$companion) {
            redirectWithError($mysqli, "Este acompañante no existe");
        }

        $companion_document = findCompanionWithDocument($mysqli, $new_document);

        if($companion_document && $companion['id_acompaniante'] !== $companion_document['id_acompaniante']) {
            redirectWithError($mysqli, "Ya existe un acompañante con esta cedula");
        }

        try {

            $new_document = keepOldValue($new_document, $companion["cedula"]);
            $first_name = keepOldValue($first_name, $companion["nombre"]);
            $last_name = keepOldValue($last_name, $companion["apellido"]);

            updateCompanion($mysqli, $new_document, $first_name, $last_name, (int) $companion['id_acompaniante']);
            $mysqli->close();

            $_SESSION["success"] = "Acompañante actualizado correctamente";
            header("Location: /php/pages/companion/companion_update.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar el acompañante";
            header("Location: /php/pages/companion/companion_update.php");
            exit();
        }

    }

?>