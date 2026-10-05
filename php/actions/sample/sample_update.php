<?php

    session_start();

    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/sample/sample_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $code = trim($_POST['sample_code'] ?? '');
        $document = trim($_POST['sample_document_patient'] ?? '');
        $type = trim($_POST['sample_type'] ?? '');
        $description = trim($_POST['sample_description'] ?? '');

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/sample/sample_form.php");
            exit();
        }

        if (!validateEmptyData($code)) {
            if (!validateSampleCode($code)) {
                redirectionForError("El código ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectionForError("La cédula ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($type)) {
            if (!validateString($type)) {
                redirectionForError("El tipo ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripción ingresada no es válida.", $id);
            }
        }

        $mysqli = connection_db();
        $sample = findSampleWithId($mysqli, $id);

        if(!$sample) {
            redirectWithError($mysqli, "Esta muestra no existe.", $id);
        }

        if ((int) $sample['id_estado_muestra'] === 3 || (int) $sample['id_estado_muestra'] === 4) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado o descartado.", $id);
        }

        if (!validateEmptyData($code)) {
            
            $sample_code = findSampleWithCode($mysqli, $code);

            if($sample_code && (int) $sample['id_muestra'] !== (int) $sample_code['id_muestra']) {
                redirectWithError($mysqli, "Este código ya está registrado.", $id);
            }
        }

        if (!validateEmptyData($document)) {

            $patient = findPatientWithDocument($mysqli, $document);

            if(!$patient) {
                redirectWithError($mysqli, "El paciente ingresado no existe.", $id);
            }

            if ((int) $patient['id_estado_paciente'] === 3 || (int) $patient['id_estado_paciente'] === 4) {
                redirectWithError($mysqli, "No se puede registrar una muestra para un paciente inactivo o eliminado.", $id);
            }
        }

        try {

            if (validateEmptyData($document)) {
                $patient_id = $sample["id_paciente"];

            } else {
                $patient_id = $patient['id_paciente'];

            }

            $code = keepOldValue($code, $sample["codigo"]);
            $type = keepOldValue($type, $sample["tipo"]);
            $description = keepOldValue($description, $sample["descripcion"]);

            updateSample($mysqli, $code, (int) $patient_id,  $type, $description, (int) $sample['id_muestra']);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/sample/sample_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {
            $mysqli->close();
    
            $_SESSION["errors"] = "Ocurrió un error al actualizar.";
            header("Location: /php/pages/sample/sample_form.php?id=" . $id);
            exit();
        }

    }

?>