<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/cellphone_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/cellphone/cellphone_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/cellphone/cellphone_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $document = trim($_POST['employee_document'] ?? '');
        $code = trim($_POST['cellphone_code'] ?? '');
        $number = trim($_POST['cellphone_number'] ?? '');

        if (validateEmptyData($document) || validateEmptyData($code) || validateEmptyData($number)) {

            redirectionForError("Todos los campos son obligatorios.");
        }

        $cellphone_number = $code . $number;

        if (!validateDocument($document)) {
            redirectionForError("La cédula ingresada no es válida.");
        }

        if (!validatePhone($cellphone_number)) {
            redirectionForError("El teléfono no es válido.");
        }

        $mysqli = connection_db();

        $employee = findEmployeeWithDocument($mysqli, $document);

        if(!$employee) {
            redirectWithError($mysqli, "El empleado no existe.");
        }

        $cellphone = findCellphoneWithEmployeeAndNumber($mysqli, $employee['id_funcionario'], $cellphone_number);

        if($cellphone) {
            redirectWithError($mysqli, "Este teléfono ya está registrado, para este empleado.");
        }

        try {


            insertCellphone($mysqli, $cellphone_number, $employee['id_funcionario']);
            $mysqli->close();

            $_SESSION["success"] = "El registro ha sido exitoso.";
            header("Location: /php/pages/cellphone/cellphone_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            $mysqli->close();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/cellphone/cellphone_form.php");
            exit();
        }

    }

?> 