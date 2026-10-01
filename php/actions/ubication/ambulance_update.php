<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/ambulance_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/ambulance/ambulance_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $registration = trim($_POST['ambulance_registration'] ?? '');
        $brand = trim($_POST['ambulance_brand'] ?? '');
        $model = trim($_POST['ambulance_model'] ?? '');
        $year = trim($_POST['ambulance_year'] ?? '');
        $description = trim($_POST['ambulance_description'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/ambulance/ambulance_form.php");
            exit();
        }

        if (!validateEmptyData($registration)) {
            if (!validateAmbulanceRegistration($registration)) {
                redirectionForError("La matricula no es válida", $id);
            }
        }

        if (!validateEmptyData($brand)) {
            if (!validateString($brand)) {
                redirectionForError("La marca ingresada no es válida", $id);
            }
        }

        if (!validateEmptyData($model)) {
            if (!validateString($model)) {
                redirectionForError("El modelo ingresado no es válido", $id);
            }
        }

        if (!validateEmptyData($year)) {
            if (!validateYear($year) || (int) $year < 1900 || (int) $year > 2100) {
                redirectionForError("El año ingresado no es válido", $id);
            }
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripcion ingresada no es válida", $id);
            }
        }

        $mysqli = connection_db();
        $ambulance = findAmbulanceWithId($mysqli, $id);

        if(!$ambulance) {
            redirectWithError($mysqli, "El ambulancia no existe", $id);
        }

        $ambulance_registration = findAmbulanceWithRegistration($mysqli, $registration);

        if($ambulance_registration && (int) $ambulance['id_ambulancia'] !== (int) $ambulance_registration['id_ambulancia']) {
            redirectWithError($mysqli, "Ya existe una ambulancia con la nueva matricula", $id);
        }

        try {

            $registration = keepOldValue($registration, $ambulance["matricula"]);
            $brand = keepOldValue($brand, $ambulance["marca"]);
            $model = keepOldValue($model, $ambulance["modelo"]);
            $year = keepOldValue($year, $ambulance["anio"]);
            $description = keepOldValue($description, $ambulance["descripcion"]);

            updateAmbulance($mysqli, $registration, $brand, $model,
                (int) $year, $description, (int) $ambulance['id_ambulancia']);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/ambulance/ambulance_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/ambulance/ambulance_form.php?id=" . $id);
            exit();
        }

    }

?>