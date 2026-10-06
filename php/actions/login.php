<?php

session_start();

require_once __DIR__ . "/../functions/employee_functions.php";
require_once __DIR__ . "/../models/employee_model.php";
require_once __DIR__ . "/../functions/validations.php";
require_once __DIR__ . "/../conection.php";

function redirectionForError($message)
{
    $_SESSION["errors"] = $message;
    header("Location: /php/pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /php/pages/login.php");
    exit();
}

$employee_id = strtoupper(trim($_POST["employee_id"] ?? ""));
$password = $_POST["employee_password"] ?? "";

if (validateEmptyData($employee_id) || validateEmptyData($password)) {
    redirectionForError("Todos los campos son obligatorios.");
}

if (!validateEmployeeCode($employee_id)) {
    redirectionForError("El código de funcionario no es válido.");
}

$user_type = getEmployeeType($employee_id);

$mysqli = connection_db();

try {
    $employee = findEmployeeWithCode($user_type, $mysqli, $employee_id);
} finally {
    $mysqli->close();
}

if (!$employee) {
    redirectionForError("El usuario ingresado no existe.");
}

if (!authenticateEmployee($password, $employee["pass"])) {
    redirectionForError("La contraseña es incorrecta.");
}

if ((int)$employee["id_estado_funcionario"] !== 1) {
    redirectionForError("El usuario está bloqueado. Contacte con el administrador.");
}

/* El ID de sesión se regenera después de autenticar
 y antes de guardar el estado autenticado.*/
 
session_regenerate_id();

$_SESSION["logged"] = true;
$_SESSION["username"] = $employee["nombre"];
$_SESSION["employee_id"] = $employee["id_funcionario"];
$_SESSION["employee_code"] = $employee["codigo"];
$_SESSION["user_type"] = null;
$_SESSION["type_user"] = null;

switch ($user_type) {

    case "FA":

        $_SESSION["user_type"] = "admin";
        $_SESSION["type_user"] = "admin";

        header("Location: /php/pages/administrative_pages/administrative_panel.php");
        exit();

    case "CO":

        $_SESSION["user_type"] = "copilot";
        $_SESSION["type_user"] = "copilot";

        header("Location: /php/pages/administrative_pages/administrative_panel.php");
        exit();

    case "DR":

        $_SESSION["user_type"] = "driver";
        $_SESSION["type_user"] = "driver";

        header("Location: /php/pages/administrative_pages/administrative_panel.php");
        exit();

    case "SU":

        $_SESSION["user_type"] = "superuser";
        $_SESSION["type_user"] = "superuser";

        header("Location: /php/pages/super_user_pages/super_user_panel.php");
        exit();

    default:
        $_SESSION["logged"] = false;
        redirectionForError("Tipo de usuario inválido.");
}

?>
