<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/employee_form.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/employee_form.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $first_name = trim($_POST['employee_first_name'] ?? '');
        $last_name = trim($_POST['employee_last_name'] ?? '');
        $document = trim($_POST['employee_document'] ?? '');
        $nationality = trim($_POST['employee_nationality'] ?? '');
        $birthdate = trim($_POST['employee_birthdate'] ?? '');
        $department = trim($_POST['employee_department'] ?? '');
        $locality = trim($_POST['employee_locality'] ?? '');
        $address = trim($_POST['employee_address'] ?? '');
        $door_number = trim($_POST['employee_address_number'] ?? '');
        $email = trim($_POST['employee_email'] ?? '');
        $entry_date = trim($_POST['employee_entry_date'] ?? '');

        if ($locality === "Otra localidad") {
            $locality = trim($_POST['other_locality'] ?? '');
        }

        if (validateEmptyData($first_name) || validateEmptyData($last_name) || validateEmptyData($document) ||
            validateEmptyData($nationality) || validateEmptyData($birthdate) || validateEmptyData($department) ||
            validateEmptyData($locality) || validateEmptyData($address) || validateEmptyData($door_number) ||
            validateEmptyData($email) || validateEmptyData($entry_date)) {
                redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cedula ingresada no es válida.");
        }

        if (!validateName($first_name)) {
            redirectionForError("El nombre ingresado no es válido.");
        }

        if (!validateName($last_name)) {
            redirectionForError("El apellido ingresado no es válido.");
        }

        if (!validateString($locality)) {
            redirectionForError("La localidad ingresada no es válida.");
        }

        if (!validateString($address)) {
            redirectionForError("La dirección ingresada no es válida.");
        }

        if (!validateDate($birthdate)) {
            redirectionForError("La fecha de nacimiento ingresada no es válida.");
        }

        if (!validateDoorNumber($door_number)) {
            redirectionForError("El numero de puerta ingresado no es válido.");
        }

        if (!validateEmail($email)) {
            redirectionForError("El email ingresado no es válido.");
        }

        if (!validateShortString($department)) {
            redirectionForError("El departamento ingresado no es válido.");
        }

        if (!validateString($nationality)) {
            redirectionForError("La nacionalidad ingresada no es válida.");
        }

        $mysqli = connection_db();
        $employee = findEmployeeWithDocument($mysqli, $document);

        if($employee) {

            redirectWithError($mysqli, "La cédula ya está registrada");
        }

        $employee_email = findEmployeeWithEmail($mysqli, $email);

        if ($employee_email) {
            redirectWithError($mysqli, "Este email ya está registrado");
        }

        try {

            insertEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                            $locality, $address, $door_number, $email, $entry_date);

            $mysqli->close();
            $_SESSION["success"] = "El registro ha sido exitoso";
            header("Location: /php/pages/employee/employee_form.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al registrar.';
            header("Location: /php/pages/employee/employee_form.php");
            exit();
        }
    }
?>