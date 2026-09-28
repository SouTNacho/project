<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/sample_functions.php";
    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_update.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_update.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $new_code = trim($_POST['sample_new_code'] ?? '');
        $current_code = trim($_POST['sample_current_code'] ?? '');
        $document = trim($_POST['sample_document_patient'] ?? '');
        $type = trim($_POST['sample_type'] ?? '');
        $description = trim($_POST['sample_description'] ?? '');

        if (!validateSampleCode($current_code)) {
            redirectionForError("El código actual no es válido");
        }

        if (!validateEmptyData($new_code)) {
            if (!validateSampleCode($new_code)) {
                redirectionForError("El código nuevo no es válido");
            }
        }

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectionForError("La cédula ingresada no es válida");
            }
        }

        if (!validateEmptyData($type)) {
            if (!validateString($type)) {
                redirectionForError("El tipo ingresado no es válido");
            }
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripción ingresada no es válida");
            }
        }

        $mysqli = connection_db();
        $sample = findSampleWithCode($mysqli, $current_code);

        if(!$sample) {
            redirectWithError($mysqli, "Esta muestra no existe");
        }

        $sample_code = findSampleWithCode($mysqli, $new_code);

        if($sample_code && (int) $sample['id_muestra'] !== (int) $sample_code['id_muestra']) {
            redirectWithError($mysqli, "Ya existe una muestra con este código");
        }

        if (!validateEmptyData($document)) {

            $patient = findPatientWithDocument($mysqli, $document);

            if(!$patient) {
                redirectWithError($mysqli, "El paciente ingresado no existe");
            }
        }

        try {

            if (validateEmptyData($document)) {
                $patient_id = $sample["id_paciente"];

            } else {
                $patient_id = $patient['id_paciente'];

            }

            $new_code = keepOldValue($new_code, $sample["codigo"]);
            $type = keepOldValue($type, $sample["tipo"]);
            $description = keepOldValue($description, $sample["descripcion"]);

            updateSample($mysqli, $new_code, (int) $patient_id,  $type, $description, (int) $sample['id_muestra']);
            $mysqli->close();

            $_SESSION["success"] = "Muestra actualizada correctamente";
            header("Location: /php/pages/sample/sample_update.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();
    
            $_SESSION["errors"] = "Ocurrió un error al actualizar la muestra";
            header("Location: /php/pages/sample/sample_update.php");
            exit();
        }

    }

?>