<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/companion_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/companion/companion_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $document = trim($_POST['companion_document'] ?? '');
        $first_name = trim($_POST['companion_first_name'] ?? '');
        $last_name = trim($_POST['companion_last_name'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/companion/companion_form.php");
            exit();
        }

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectionForError("La cédula igresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($first_name)) {
            if (!validateName($first_name)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($last_name)) {
            if (!validateName($last_name)) {
                redirectionForError("El apellido ingresado no es válido.", $id);
            }
        }

        $mysqli = connection_db();
        $companion = findCompanionWithId($mysqli, $id);

        if(!$companion) {
            redirectWithError($mysqli, "Este acompañante no existe", $id);
        }

        $companion_document = findCompanionWithDocument($mysqli, $document);

        if($companion_document && $companion['id_acompaniante'] !== $companion_document['id_acompaniante']) {
            redirectWithError($mysqli, "Esta cédula ya esta registrada" , $id);
        }

        try {

            $document = keepOldValue($document, $companion["cedula"]);
            $first_name = keepOldValue($first_name, $companion["nombre"]);
            $last_name = keepOldValue($last_name, $companion["apellido"]);

            updateCompanion($mysqli, $document, $first_name, $last_name, (int) $companion['id_acompaniante']);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/companion/companion_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/companion/companion_form.php");
            exit();
        }

    }

?>