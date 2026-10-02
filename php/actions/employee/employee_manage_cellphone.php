
<?php

    session_start();

    require_once __DIR__ . "/../../functions/employee_functions.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/update_employee.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/manage_cellphone.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $employee_id = trim($_POST['employee_id'] ?? '');
        $action = trim($_POST['employee_cellphone_action'] ?? '');

        $new_code = "";
        $new_number = "";

        $new_fullphone = "";

        if (validateEmptyData($employee_id) || validateEmptyData($action)) {
                redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateEmployeeCode($employee_id)) {
            redirectionForError("El cÃ³digo de funcionario no es vÃ¡lido");
        }

        if (!in_array($action, ["cr", "rm", "up"])) {
            redirectionForError("La acciÃ³n seleccionada no es vÃ¡lida");
        }

        $cellphone_code = trim($_POST['cellphone_code'] ?? '');
        $cellphone_number = trim($_POST['cellphone_number'] ?? '');

        if ($action === "up") {
            $new_code = trim($_POST['other_cellphone_code'] ?? '');
            $new_number = trim($_POST['other_cellphone_number'] ?? '');
        }

        $mysqli = connection_db();
        $employee_type = getEmployeeType($employee_id);
        $employee = findEmployeeWithCode($employee_type, $mysqli, $employee_id);

        if (!$employee) {
            redirectWithError($mysqli, "El funcionario ingresado no existe");
        }

        if (validateEmptyData($cellphone_code) || validateEmptyData($cellphone_number)) {
            redirectWithError($mysqli, "Todos los campos son obligatorios");
        }

        $fullphone = $cellphone_code . $cellphone_number;

        if (!validatePhone($fullphone)) {
            redirectWithError($mysqli, "El TelÃ©fono ingresado no es vÃ¡lido");
        }

        if ($action === "up") {

            if (validateEmptyData($new_code) || validateEmptyData($new_number)) {
                redirectWithError($mysqli, "Todos los campos son obligatorios");
            }

            $new_fullphone = $new_code . $new_number;

            if (!validatePhone($new_fullphone)) {
                redirectWithError($mysqli, "El TelÃ©fono nuevo ingresado no es vÃ¡lido");
            }
        }

        $cellphone = findEmployeeCellphone($mysqli, $employee["id_funcionario"], $fullphone);

        try {

            switch($action) {

                case "rm":

                    if ($cellphone) {

                        deleteCellphone($mysqli, $cellphone["id_telefono"]);
                    }
                    else {
                        redirectWithError($mysqli, "El TelÃ©fono ingresado no existe para este funcionario");
                    }
                    break;
                case "cr":

                    if (!$cellphone) {

                        insertCellphone($mysqli, $fullphone, $employee["id_funcionario"]);
                    }
                    else {
                        redirectWithError($mysqli, "El TelÃ©fono ingresado ya existe para este funcionario");
                    }
                    break;
                case "up":

                    if ($cellphone) {

                        $new_cellphone = findEmployeeCellphone($mysqli, $employee["id_funcionario"], $new_fullphone);

                        if (!$new_cellphone) {
                            updateCellphone($mysqli, $new_fullphone, $cellphone["id_telefono"]);

                        } else {
                            redirectWithError($mysqli, "El TelÃ©fono nuevo ya existe para este funcionario");
                        }

                    } else {
                        redirectWithError($mysqli, "El TelÃ©fono que desea actualizar no existe para este funcionario");
                    }
                    break;
            }

            $mysqli->close();

            $_SESSION["success"] = "TelÃ©fono gestionado correctamente";
            header("Location: /php/pages/employee/manage_cellphone.php");
            exit();

        } catch (mysqli_sql_exception $e) {

            $mysqli->close();

            $_SESSION["errors"] = "Ocurrio al gestionar el telÃ©fono";
            header("Location: /php/pages/employee/manage_cellphone.php");
            exit();
        }

    }

?>
