
<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/employee_functions.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/register.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/register.php");
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
        $cellphone_code = trim($_POST['employee_cellphone_code'] ?? '');
        $cellphone_number = trim($_POST['employee_cellphone_number'] ?? '');
        $position = trim($_POST['employee_position'] ?? '');
        $entry_date = trim($_POST['employee_entry_date'] ?? '');
        $password = trim($_POST['employee_password'] ?? '');
        $repeat_password = trim($_POST['employee_confirm_password'] ?? '');
        $permissions = "";
        $license_expiration_date = "";
        $license_category = "";
        $speciality = "";
        $hash_pass = "";

        if ($locality === "Otra localidad") {
            $locality = trim($_POST['other_locality'] ?? '');
        }

        if (validateEmptyData($first_name) || validateEmptyData($last_name) || validateEmptyData($document) ||
            validateEmptyData($nationality) || validateEmptyData($birthdate) || validateEmptyData($department) ||
            validateEmptyData($locality) || validateEmptyData($address) || validateEmptyData($door_number) ||
            validateEmptyData($email) || validateEmptyData($cellphone_code) || validateEmptyData($cellphone_number) ||
            validateEmptyData($position) || validateEmptyData($entry_date) || validateEmptyData($password) ||
            validateEmptyData($repeat_password)) {
                redirectionForError("Todos los campos son obligatorios");
        }

        $phone_number = $cellphone_code . $cellphone_number;

        switch($position) {
            case "FA":

                $permissions = trim($_POST['employee_permissions'] ?? '');

                if (validateEmptyData($permissions)) {
                    redirectionForError("Los datos adicionales para el cargo son obligatorios");
                }

                if (!validateShortString($permissions)) {
                    redirectionForError("Los permisos ingresados no son vÃ¡lidos");
                }

                break;
            case "DR":

                $license_expiration_date = trim($_POST['employee_license_expiration'] ?? '');
                $license_category = trim($_POST['employee_license_category'] ?? '');

                if (validateEmptyData($license_expiration_date) || validateEmptyData($license_category)) {
                    redirectionForError("Los datos adicionales para el cargo son obligatorios");
                }

                if (!validateDate($license_expiration_date)) {
                    redirectionForError("La fecha de vencimiento del carnet ingresada no es vÃ¡lida");
                }

                if (!validateCategory($license_category)) {
                    redirectionForError("La categorÃa del carnet ingresada no es vÃ¡lida");
                }

                break;
            case "CO":

                $speciality = trim($_POST['employee_speciality'] ?? '');

                if (validateEmptyData($speciality)) {
                    redirectionForError("Los datos adicionales para el cargo son obligatorios");
                }

                if (!validateString($speciality)) {
                    redirectionForError("La especialidad ingresada no es vÃ¡lida");
                }

                break;
            default:
                redirectionForError("El cargo ingresado no es vÃ¡lido");
        }

        if (!validatePassword($password)) {
            redirectionForError("La contraseÃ±a ingresada no es vÃ¡lida");
        }

        if (!validateDocument($document)) {
            redirectionForError("La cedula ingresada no es vÃ¡lida");
        }

        if (!validatePhone($phone_number)) {
            redirectionForError("El celular ingresado no es vÃ¡lido");
        }

        if (!validateName($first_name)) {
            redirectionForError("El nombre ingresado no es vÃ¡lido");
        }

        if (!validateName($last_name)) {
            redirectionForError("El apellido ingresado no es vÃ¡lido");
        }

        if (!validateString($locality)) {
            redirectionForError("La localidad ingresada no es vÃ¡lida");
        }

        if (!validateString($address)) {
            redirectionForError("La direcciÃ³n ingresada no es vÃ¡lida");
        }

        if (!validateDate($birthdate)) {
            redirectionForError("La fecha de nacimiento ingresada no es vÃ¡lida");
        }

        if (!validateDoorNumber($door_number)) {
            redirectionForError("El numero de puerta ingresado no es vÃ¡lido");
        }

        if (!validateEmail($email)) {
            redirectionForError("El email ingresado no es vÃ¡lido");
        }

        if ($password !== $repeat_password) {
            redirectionForError("Las contraseÃ±as no coinciden");
        }

        $hash_pass = password_hash($password, PASSWORD_BCRYPT);

        $mysqli = connection_db();
        $employee = findEmployeeWithDocument($mysqli, $document);

        if($employee) {

            redirectWithError($mysqli, "El usuario ya existe");
        }

        $employee_email = findEmployeeWithEmail($mysqli, $email);

        if ($employee_email) {
            redirectWithError($mysqli, "Este email ya estÃ¡ registrado");
        }

        try {

            $mysqli->begin_transaction();

            $funcionary_id = insertEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                            $locality, $address, $door_number, $email, $position, $entry_date, $hash_pass);

            insertCellphone($mysqli, $phone_number, $funcionary_id);

            $employee_id = $position . str_pad($funcionary_id, 8, '0', STR_PAD_LEFT);

            insertRole($position, $funcionary_id, $mysqli, $permissions,
                        $speciality, $license_expiration_date, $license_category, $employee_id);

            $mysqli->commit();
            $mysqli->close();

            $_SESSION["success"] = "Funcionario registrado correctamente";
            header("Location: /php/pages/employee/register.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();
                
            $_SESSION["errors"] = "OcurriÃ³ un error al registrar el funcionario.";
            header("Location: /php/pages/employee/register.php");
            exit();
        }

    }
    
?>
