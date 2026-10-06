<?php

    session_start();
    // Falta validar rol
    
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/employee_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/employee_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
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

        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/employee/employee_form.php");
            exit();
        }

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectionForError("La cedula ingresada no es válida.", $id);
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

        if (!validateEmptyData($locality)) {
            if (!validateString($locality)) {
                redirectionForError("La localidad ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($address)) {
            if (!validateString($address)) {
                redirectionForError("La dirección ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($birthdate)) {
            if (!validateDate($birthdate)) {
                redirectionForError("La fecha de nacimiento ingresada no es válida.", $id);
            }
        }

        if (!validateEmptyData($door_number)) {
            if (!validateDoorNumber($door_number)) {
                redirectionForError("El numero de puerta ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($email)) {
            if (!validateEmail($email)) {
                redirectionForError("El email ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($department)) {
            if (!validateShortString($department)) {
                redirectionForError("El departamento ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($nationality)) {
            if (!validateString($nationality)) {
                redirectionForError("La nacionalidad ingresada no es válida.", $id);
            }
        }

        $mysqli = connection_db();
        $employee = findEmployeeWithId($mysqli, $id);

        if(!$employee) {

            redirectWithError($mysqli, "El empleado no existe", $id);
        }

        if ((int) $employee['id_estado_funcionario'] === 3) {
            redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
        }

        if (!validateEmptyData($document)) {
            $employee_document = findEmployeeWithDocument($mysqli, $document);

            if ($employee_document && (int) $employee['id_funcionario'] !== (int) $employee_document['id_funcionario']) {
                redirectWithError($mysqli, "Esta cédula ya está registrada", $id);
            }
        }

        if (!validateEmptyData($email)) {
            $employee_email = findEmployeeWithEmail($mysqli, $email);

            if ($employee_email && (int) $employee['id_funcionario'] !== (int) $employee_email['id_funcionario']) {
                redirectWithError($mysqli, "Este email ya está registrado", $id);
            }
        }

        try {

            $first_name = keepOldValue($first_name, $employee["nombre"]);
            $last_name = keepOldValue($last_name, $employee["apellido"]);
            $document = keepOldValue($document, $employee["cedula"]);
            $nationality = keepOldValue($nationality, $employee["nacionalidad"]);
            $birthdate = keepOldValue($birthdate, $employee["fecha_nacimiento"]);
            $department = keepOldValue($department, $employee["departamento"]);
            $locality = keepOldValue($locality, $employee["localidad"]);
            $address = keepOldValue($address, $employee["direccion"]);
            $door_number = keepOldValue($door_number, $employee["numero_puerta"]);
            $email = keepOldValue($email, $employee["email"]);
            $entry_date = keepOldValue($entry_date, $employee["fecha_ingreso"]);

            updateEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                            $locality, $address, $door_number, $email, $entry_date, (int) $employee['id_funcionario']);

            $mysqli->close();
            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/employee/employee_form.php?id=" . $id);
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();
            
            $_SESSION["errors"] = 'Ha ocurrido un error al actualizar.';
            header("Location: /php/pages/employee/employee_form.php?id=" . $id);
            exit();
        }
    }
?>